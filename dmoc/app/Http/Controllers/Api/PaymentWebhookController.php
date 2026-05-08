<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Payment;
use App\Services\OrderStatusService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PaymentWebhookController extends Controller
{
    public function __construct(private readonly OrderStatusService $orderStatusService)
    {
    }

    public function cinetpay(Request $request): JsonResponse
    {
        return $this->handleProviderWebhook($request, 'cinet');
    }

    public function stripe(Request $request): JsonResponse
    {
        return $this->handleProviderWebhook($request, 'stripe');
    }

    public function paypal(Request $request): JsonResponse
    {
        return $this->handleProviderWebhook($request, 'paypal');
    }

    private function handleProviderWebhook(Request $request, string $provider): JsonResponse
    {
        $validated = $request->validate([
            'reference' => ['required', 'string'],
            'event' => ['required', 'in:payment_success,payment_failed'],
            'transaction_id' => ['nullable', 'string'],
            'payload' => ['nullable', 'array'],
        ]);

        $payment = Payment::query()
            ->with('order')
            ->where('reference', $validated['reference'])
            ->where('method', $provider)
            ->first();

        if (! $payment) {
            return response()->json([
                'status' => 'error',
                'message' => 'Paiement introuvable.',
            ], 404);
        }

        DB::transaction(function () use ($payment, $validated): void {
            $isSuccess = $validated['event'] === 'payment_success';
            $payment->update([
                'status' => $isSuccess ? 'success' : 'failed',
                'transaction_id' => $validated['transaction_id'] ?? $payment->transaction_id,
                'response_data' => array_merge($payment->response_data ?? [], [
                    'webhook_event' => $validated['event'],
                    'payload' => $validated['payload'] ?? [],
                    'received_at' => now()->toIso8601String(),
                ]),
            ]);

            if ($isSuccess) {
                $this->orderStatusService->transition(
                    $payment->order,
                    'confirmed',
                    null,
                    'payment_webhook',
                    'Paiement confirme par webhook provider.'
                );
            }
        });

        return response()->json([
            'status' => 'success',
            'message' => 'Webhook traite.',
        ]);
    }
}
