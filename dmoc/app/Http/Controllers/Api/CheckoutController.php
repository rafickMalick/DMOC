<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Cart;
use App\Models\Order;
use App\Models\Payment;
use App\Models\Zone;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class CheckoutController extends Controller
{
    public function stepOne(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'zone_id' => ['required', 'integer', 'exists:zones,id'],
            'shipping_address' => ['required', 'string', 'max:1000'],
            'shipping_phone' => ['required', 'string', 'max:30'],
            'recipient_name' => ['required', 'string', 'max:255'],
            'weight_kg' => ['nullable', 'numeric', 'min:0'],
            'notes' => ['nullable', 'string', 'max:2000'],
        ]);

        $user = $request->user();
        $cart = Cart::query()
            ->with(['items.product'])
            ->where('user_id', $user->id)
            ->first();

        if (! $cart || $cart->items->isEmpty()) {
            return response()->json([
                'status' => 'error',
                'message' => 'Votre panier est vide.',
            ], 422);
        }

        $zone = Zone::query()->findOrFail($validated['zone_id']);
        $weightKg = (float) ($validated['weight_kg'] ?? 1);

        $subtotal = (int) $cart->items->sum(fn ($item) => $item->quantity * $item->unit_price_xof);
        $deliveryFee = (int) ($zone->base_tariff_xof + ($zone->per_kg_xof * $weightKg));
        $total = $subtotal + $deliveryFee;
        $estimatedDelivery = now()->addDays(2);

        $order = DB::transaction(function () use ($user, $cart, $validated, $zone, $subtotal, $deliveryFee, $total, $estimatedDelivery) {
            $order = Order::query()->create([
                'user_id' => $user->id,
                'total_xof' => $total,
                'delivery_fee_xof' => $deliveryFee,
                'status' => 'pending',
                'delivery_zone_id' => $zone->id,
                'estimated_delivery' => $estimatedDelivery,
                'shipping_address' => $validated['shipping_address'],
                'shipping_phone' => $validated['shipping_phone'],
                'recipient_name' => $validated['recipient_name'],
                'notes' => $validated['notes'] ?? null,
            ]);

            foreach ($cart->items as $cartItem) {
                $order->items()->create([
                    'product_id' => $cartItem->product_id,
                    'quantity' => $cartItem->quantity,
                    'price_xof' => $cartItem->unit_price_xof,
                ]);
            }

            return $order->load(['items.product', 'zone']);
        });

        return response()->json([
            'status' => 'success',
            'message' => 'Etape 1 validee. Commande initialisee.',
            'data' => [
                'order' => $order,
                'summary' => [
                    'subtotal_xof' => $subtotal,
                    'delivery_fee_xof' => $deliveryFee,
                    'total_xof' => $total,
                    'estimated_delivery' => $estimatedDelivery->toIso8601String(),
                ],
            ],
        ], 201);
    }

    public function stepTwo(Request $request, int $orderId): JsonResponse
    {
        $validated = $request->validate([
            'payment_method' => ['required', 'in:cinet,stripe,paypal,cod'],
        ]);

        $user = $request->user();

        $order = Order::query()
            ->where('id', $orderId)
            ->where('user_id', $user->id)
            ->where('status', 'pending')
            ->first();

        if (! $order) {
            return response()->json([
                'status' => 'error',
                'message' => 'Commande introuvable ou non modifiable.',
            ], 404);
        }

        $payment = DB::transaction(function () use ($order, $validated) {
            $order->update([
                'payment_method' => $validated['payment_method'],
            ]);

            return Payment::query()->create([
                'order_id' => $order->id,
                'method' => $validated['payment_method'],
                'amount_xof' => $order->total_xof,
                'status' => 'pending',
                'reference' => $this->generatePaymentReference(),
                'response_data' => [
                    'source' => 'checkout_step_two',
                    'message' => 'Paiement initialise',
                ],
            ]);
        });

        return response()->json([
            'status' => 'success',
            'message' => 'Etape 2 validee. Paiement initialise.',
            'data' => [
                'order' => $order->fresh()->load('items.product', 'zone'),
                'payment' => $payment,
            ],
        ]);
    }

    public function summary(Request $request, int $orderId): JsonResponse
    {
        $order = Order::query()
            ->with(['items.product', 'zone', 'payments'])
            ->where('id', $orderId)
            ->where('user_id', $request->user()->id)
            ->first();

        if (! $order) {
            return response()->json([
                'status' => 'error',
                'message' => 'Commande introuvable.',
            ], 404);
        }

        $subtotal = (int) $order->items->sum(fn ($item) => $item->quantity * $item->price_xof);

        return response()->json([
            'status' => 'success',
            'data' => [
                'order' => $order,
                'summary' => [
                    'subtotal_xof' => $subtotal,
                    'delivery_fee_xof' => (int) $order->delivery_fee_xof,
                    'total_xof' => (int) $order->total_xof,
                ],
            ],
        ]);
    }

    public function confirm(Request $request, int $orderId): JsonResponse
    {
        $user = $request->user();

        $order = Order::query()
            ->with(['payments'])
            ->where('id', $orderId)
            ->where('user_id', $user->id)
            ->first();

        if (! $order) {
            return response()->json([
                'status' => 'error',
                'message' => 'Commande introuvable.',
            ], 404);
        }

        if ($order->status !== 'pending') {
            return response()->json([
                'status' => 'error',
                'message' => 'Cette commande est deja confirmee ou en cours.',
            ], 422);
        }

        $payment = $order->payments->sortByDesc('id')->first();

        if (! $payment) {
            return response()->json([
                'status' => 'error',
                'message' => 'Aucun paiement initialise pour cette commande.',
            ], 422);
        }

        if ($payment->method === 'cod') {
            DB::transaction(function () use ($order, $payment, $user) {
                $payment->update([
                    'status' => 'success',
                    'response_data' => [
                        'source' => 'checkout_step_three',
                        'message' => 'Paiement a la livraison confirme',
                    ],
                ]);

                $order->update([
                    'status' => 'confirmed',
                ]);

                $cart = Cart::query()->where('user_id', $user->id)->first();
                if ($cart) {
                    $cart->items()->delete();
                }
            });

            return response()->json([
                'status' => 'success',
                'message' => 'Commande confirmee avec paiement a la livraison.',
                'data' => [
                    'order' => $order->fresh()->load(['items.product', 'zone', 'payments']),
                ],
            ]);
        }

        $payment->update([
            'status' => 'processing',
            'response_data' => [
                'source' => 'checkout_step_three',
                'message' => 'Paiement en attente de confirmation provider',
            ],
        ]);

        return response()->json([
            'status' => 'success',
            'message' => 'Paiement initie. En attente de confirmation.',
            'data' => [
                'order' => $order->fresh()->load(['items.product', 'zone', 'payments']),
            ],
        ]);
    }

    private function generatePaymentReference(): string
    {
        return 'DMOC-PAY-'.Str::upper(Str::random(12));
    }
}
