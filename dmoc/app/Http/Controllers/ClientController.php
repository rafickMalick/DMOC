<?php

namespace App\Http\Controllers;

use App\Enums\OrderStatus;
use App\Models\Cart;
use App\Models\Order;
use App\Models\Payment;
use App\Models\Zone;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Http\RedirectResponse;

class ClientController extends Controller
{
    public function home()
    {
        return view('client.home');
    }

    public function catalog()
    {
        return view('client.catalog');
    }

    public function product()
    {
        return view('client.product');
    }

    public function cart()
    {
        $cart = Cart::query()
            ->with(['items.product'])
            ->where('user_id', auth()->id())
            ->first();

        $subtotal = (int) ($cart?->items->sum(fn ($item) => $item->quantity * $item->unit_price_xof) ?? 0);
        $itemsCount = (int) ($cart?->items->sum('quantity') ?? 0);

        return view('client.cart', compact('cart', 'subtotal', 'itemsCount'));
    }

    public function checkout()
    {
        $requestedOrderId = request()->integer('order');

        $cart = Cart::query()
            ->with(['items.product'])
            ->where('user_id', auth()->id())
            ->first();

        $zones = Zone::query()->orderBy('name')->get();

        $orderQuery = Order::query()
            ->with(['items.product', 'zone', 'payments'])
            ->where('user_id', auth()->id())
            ->where('status', OrderStatus::Pending->value);

        if ($requestedOrderId) {
            $orderQuery->where('id', $requestedOrderId);
        }

        $order = $orderQuery->latest()->first();

        $subtotal = (int) ($cart?->items->sum(fn ($item) => $item->quantity * $item->unit_price_xof) ?? 0);
        $hasStepOne = (bool) $order;
        $hasStepTwo = $hasStepOne && ! empty($order->payment_method);
        $canConfirm = $hasStepTwo;

        return view('client.checkout', compact('cart', 'zones', 'order', 'subtotal', 'hasStepOne', 'hasStepTwo', 'canConfirm'));
    }

    public function checkoutStepOne(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'zone_id' => ['required', 'integer', 'exists:zones,id'],
            'shipping_address' => ['required', 'string', 'max:1000'],
            'shipping_phone' => ['required', 'string', 'max:30'],
            'recipient_name' => ['required', 'string', 'max:255'],
            'weight_kg' => ['nullable', 'numeric', 'min:0'],
            'notes' => ['nullable', 'string', 'max:2000'],
        ]);

        $cart = Cart::query()
            ->with('items')
            ->where('user_id', $request->user()->id)
            ->first();

        if (! $cart || $cart->items->isEmpty()) {
            return back()->with('error', 'Your cart is empty.');
        }

        $zone = Zone::query()->findOrFail($validated['zone_id']);
        $weightKg = (float) ($validated['weight_kg'] ?? 1);
        $subtotal = (int) $cart->items->sum(fn ($item) => $item->quantity * $item->unit_price_xof);
        $deliveryFee = (int) ($zone->base_tariff_xof + ($zone->per_kg_xof * $weightKg));
        $total = $subtotal + $deliveryFee;

        $order = DB::transaction(function () use ($request, $cart, $validated, $zone, $deliveryFee, $total) {
            $order = Order::query()->create([
                'user_id' => $request->user()->id,
                'total_xof' => $total,
                'delivery_fee_xof' => $deliveryFee,
                'status' => OrderStatus::Pending->value,
                'delivery_zone_id' => $zone->id,
                'estimated_delivery' => now()->addDays(2),
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

            return $order;
        });

        return redirect()
            ->route('client.checkout', ['order' => $order->id])
            ->with('success', 'Step 1 completed. Continue with payment method.');
    }

    public function checkoutStepTwo(Request $request, int $orderId): RedirectResponse
    {
        $validated = $request->validate([
            'payment_method' => ['required', 'in:cinet,stripe,paypal,cod'],
        ]);

        $order = Order::query()
            ->where('id', $orderId)
            ->where('user_id', $request->user()->id)
            ->where('status', OrderStatus::Pending->value)
            ->firstOrFail();

        $order->update([
            'payment_method' => $validated['payment_method'],
        ]);

        Payment::query()->updateOrCreate(
            ['order_id' => $order->id],
            [
                'method' => $validated['payment_method'],
                'amount_xof' => $order->total_xof,
                'status' => 'pending',
                'reference' => 'DMOC-PAY-'.Str::upper(Str::random(12)),
                'response_data' => ['source' => 'web_checkout_step_two'],
            ]
        );

        return redirect()
            ->route('client.checkout', ['order' => $order->id])
            ->with('success', 'Step 2 completed. You can now confirm your order.');
    }

    public function checkoutConfirm(Request $request, int $orderId): RedirectResponse
    {
        $order = Order::query()
            ->with('payments')
            ->where('id', $orderId)
            ->where('user_id', $request->user()->id)
            ->where('status', OrderStatus::Pending->value)
            ->firstOrFail();

        $payment = $order->payments->sortByDesc('id')->first();
        if (! $payment) {
            return back()->with('error', 'Select a payment method first.');
        }

        DB::transaction(function () use ($request, $order, $payment) {
            if ($payment->method === 'cod') {
                $payment->update([
                    'status' => 'success',
                    'response_data' => ['source' => 'web_checkout_confirm', 'message' => 'COD confirmed'],
                ]);
                $order->update(['status' => OrderStatus::Confirmed->value]);
            } else {
                $payment->update([
                    'status' => 'processing',
                    'response_data' => ['source' => 'web_checkout_confirm', 'message' => 'Waiting provider confirmation'],
                ]);
            }

            $cart = Cart::query()->where('user_id', $request->user()->id)->first();
            if ($cart) {
                $cart->items()->delete();
            }
        });

        return redirect()
            ->route('client.confirmation')
            ->with('success', 'Order confirmed successfully.')
            ->with('last_order_id', $order->id);
    }

    public function tracking()
    {
        return view('client.tracking');
    }

    public function confirmation()
    {
        $orderId = session('last_order_id');
        $order = null;

        if ($orderId && auth()->check()) {
            $order = Order::query()
                ->with('payments')
                ->where('id', $orderId)
                ->where('user_id', auth()->id())
                ->first();
        }

        return view('client.confirmation', compact('order'));
    }

    public function auth()
    {
        return view('client.auth');
    }

    public function dashboard()
    {
        return view('client.dashboard');
    }

    public function orders(Request $request)
    {
        $orders = Order::query()
            ->with('zone')
            ->where('user_id', $request->user()->id)
            ->latest()
            ->paginate(10);

        return view('client.orders', compact('orders'));
    }

    public function orderShow(Request $request, int $orderId)
    {
        $order = Order::query()
            ->with(['items.product', 'zone', 'payments'])
            ->where('id', $orderId)
            ->where('user_id', $request->user()->id)
            ->firstOrFail();

        return view('client.order-show', compact('order'));
    }

    public function wishlist()
    {
        return view('client.wishlist');
    }

    public function profile()
    {
        return view('client.profile');
    }
}
