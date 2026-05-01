<?php

namespace App\Http\Controllers;

use App\Models\Courier;
use App\Models\Delivery;
use App\Models\Payment;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Support\Str;

class CourierController extends Controller
{
    public function list(Request $request)
    {
        $courier = Courier::query()
            ->where('user_id', $request->user()->id)
            ->first();

        $deliveries = collect();

        if ($courier) {
            $deliveries = Delivery::query()
                ->with(['order.user', 'zone'])
                ->where('courier_id', $courier->id)
                ->latest()
                ->get();
        }

        return view('courier.list', compact('courier', 'deliveries'));
    }

    public function detail(Request $request, int $deliveryId): View
    {
        $delivery = $this->getCourierDelivery($request, $deliveryId, ['order.items.product', 'order.user', 'order.payments', 'zone']);

        return view('courier.detail', compact('delivery'));
    }

    public function start(Request $request, int $deliveryId): RedirectResponse
    {
        $courier = $this->getCourier($request);
        $delivery = $this->getCourierDelivery($request, $deliveryId);

        if (! in_array($delivery->status, ['pending', 'assigned', 'picked_up'], true)) {
            return back()->with('error', 'Cette livraison ne peut pas demarrer dans son etat actuel.');
        }

        $delivery->update([
            'status' => 'in_transit',
            'assigned_at' => $delivery->assigned_at ?? now(),
            'picked_up_at' => $delivery->picked_up_at ?? now(),
        ]);

        $delivery->order?->update(['status' => 'shipped']);

        return back()->with('success', 'Livraison demarree avec succes.');
    }

    public function complete(Request $request, int $deliveryId): RedirectResponse
    {
        $courier = $this->getCourier($request);
        $delivery = $this->getCourierDelivery($request, $deliveryId);

        if (! in_array($delivery->status, ['in_transit', 'picked_up'], true)) {
            return back()->with('error', 'Cette livraison ne peut pas etre marquee livree.');
        }

        $delivery->update([
            'status' => 'delivered',
            'delivered_at' => now(),
        ]);

        $delivery->order?->update(['status' => 'delivered']);

        $courier->increment('completed_deliveries');

        return back()->with('success', 'Livraison confirmee.');
    }

    public function fail(Request $request, int $deliveryId): RedirectResponse
    {
        $validated = $request->validate([
            'reason' => ['nullable', 'string', 'max:1000'],
        ]);

        $delivery = $this->getCourierDelivery($request, $deliveryId);

        if ($delivery->status === 'delivered') {
            return back()->with('error', 'Une livraison deja terminee ne peut pas etre en echec.');
        }

        $delivery->update([
            'status' => 'failed',
            'notes' => $validated['reason'] ?? $delivery->notes,
        ]);

        return back()->with('success', 'Echec de livraison enregistre.');
    }

    public function confirmCodPayment(Request $request, int $deliveryId): RedirectResponse
    {
        $delivery = $this->getCourierDelivery($request, $deliveryId, ['order.payments']);
        $order = $delivery->order;

        if (! $order || $order->payment_method !== 'cod') {
            return back()->with('error', 'Cette commande n utilise pas le paiement a la livraison.');
        }

        $payment = $order->payments->sortByDesc('id')->first();

        if (! $payment) {
            $payment = Payment::query()->create([
                'order_id' => $order->id,
                'method' => 'cod',
                'amount_xof' => $order->total_xof,
                'status' => 'success',
                'reference' => 'DMOC-COD-'.Str::upper(Str::random(10)),
                'response_data' => ['source' => 'courier_cod_confirmation'],
            ]);
        } else {
            $payment->update([
                'status' => 'success',
                'response_data' => array_merge($payment->response_data ?? [], [
                    'source' => 'courier_cod_confirmation',
                    'confirmed_at' => now()->toDateTimeString(),
                ]),
            ]);
        }

        $receiptNumber = 'RCPT-'.now()->format('Ymd').'-'.Str::upper(Str::random(6));
        $tracking = $delivery->tracking_updates ?? [];
        $tracking[] = [
            'event' => 'cod_payment_confirmed',
            'receipt_number' => $receiptNumber,
            'amount_xof' => (int) $payment->amount_xof,
            'timestamp' => now()->toIso8601String(),
        ];

        $delivery->update([
            'tracking_updates' => $tracking,
        ]);

        return redirect()->route('courier.deliveries.receipt', $delivery->id)
            ->with('success', 'Paiement COD confirme et recu genere.');
    }

    public function receipt(Request $request, int $deliveryId): View
    {
        $delivery = $this->getCourierDelivery($request, $deliveryId, ['order.user', 'order.payments', 'zone']);
        $payment = $delivery->order?->payments?->sortByDesc('id')->first();
        $tracking = collect($delivery->tracking_updates ?? []);
        $codEvent = $tracking->firstWhere('event', 'cod_payment_confirmed');

        return view('courier.receipt', compact('delivery', 'payment', 'codEvent'));
    }

    private function getCourier(Request $request): Courier
    {
        return Courier::query()
            ->where('user_id', $request->user()->id)
            ->firstOrFail();
    }

    private function getCourierDelivery(Request $request, int $deliveryId, array $with = []): Delivery
    {
        $courier = $this->getCourier($request);

        return Delivery::query()
            ->with($with)
            ->where('courier_id', $courier->id)
            ->findOrFail($deliveryId);
    }
}
