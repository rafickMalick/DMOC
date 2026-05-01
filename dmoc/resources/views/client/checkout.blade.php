@extends('layouts.client')
@section('title','Checkout')
@section('content')
<h1 class="text-3xl font-bold mb-2">Checkout</h1>
<p class="mb-6 text-[#c4b5d6]">Complete the 3 steps to place your order.</p>

@if(session('success'))
    <div class="mb-4 rounded border border-emerald-500/40 bg-emerald-500/10 px-4 py-3 text-emerald-300">
        {{ session('success') }}
    </div>
@endif

@if(session('error'))
    <div class="mb-4 rounded border border-red-500/40 bg-red-500/10 px-4 py-3 text-red-300">
        {{ session('error') }}
    </div>
@endif

@if($errors->any())
    <div class="mb-4 rounded border border-red-500/40 bg-red-500/10 px-4 py-3 text-red-300">
        {{ $errors->first() }}
    </div>
@endif

@php
    $currentStep = ! $hasStepOne ? 1 : (! $hasStepTwo ? 2 : 3);
@endphp

<div class="mb-4 inline-flex items-center gap-2 rounded-full border border-[#5a2080] bg-[#120024] px-4 py-2 text-sm">
    <span class="text-[#c4b5d6]">Current step:</span>
    <span class="font-semibold text-[#d304f4]">{{ $currentStep }}</span>
</div>

<div class="mb-6 flex flex-wrap items-center gap-2">
    <span class="rounded-full px-3 py-1 text-sm {{ $hasStepOne ? 'bg-emerald-500/20 border border-emerald-500/40 text-emerald-300' : 'bg-[#120024] border border-[#5a2080] text-[#c4b5d6]' }}">1 Shipping</span>
    <span class="text-[#5a2080]">→</span>
    <span class="rounded-full px-3 py-1 text-sm {{ $hasStepTwo ? 'bg-emerald-500/20 border border-emerald-500/40 text-emerald-300' : 'bg-[#120024] border border-[#5a2080] text-[#c4b5d6]' }}">2 Payment</span>
    <span class="text-[#5a2080]">→</span>
    <span class="rounded-full px-3 py-1 text-sm {{ $canConfirm ? 'bg-emerald-500/20 border border-emerald-500/40 text-emerald-300' : 'bg-[#120024] border border-[#5a2080] text-[#c4b5d6]' }}">3 Confirm</span>
</div>

<div class="grid lg:grid-cols-[1fr_360px] gap-6">
    <section class="space-y-6">
        @if(!$cart || $cart->items->isEmpty())
            <x-card>
                <p class="text-[#c4b5d6]">Your cart is empty. Add products first.</p>
                <a href="{{ route('client.catalog') }}" class="inline-block mt-4 rounded-xl bg-[#d304f4] px-4 py-2 font-semibold">Go to catalog</a>
            </x-card>
        @else
            <x-card>
                <div id="step-1-anchor" class="h-0"></div>
                <h2 class="text-xl font-semibold mb-4">Step 1 - Shipping details</h2>
                <form method="POST" action="{{ route('client.checkout.step1') }}" class="grid gap-3 md:grid-cols-2">
                    @csrf
                    <input type="text" name="recipient_name" value="{{ old('recipient_name', $order?->recipient_name) }}" placeholder="Recipient name" required class="rounded border border-[#5a2080] bg-[#120024] px-3 py-2 text-white">
                    <input type="text" name="shipping_phone" value="{{ old('shipping_phone', $order?->shipping_phone) }}" placeholder="Phone" required class="rounded border border-[#5a2080] bg-[#120024] px-3 py-2 text-white">
                    <select name="zone_id" required class="rounded border border-[#5a2080] bg-[#120024] px-3 py-2 text-white">
                        <option value="">Select zone</option>
                        @foreach($zones as $zone)
                            <option value="{{ $zone->id }}" @selected((string)old('zone_id', $order?->delivery_zone_id) === (string)$zone->id)>
                                {{ $zone->name }} (base {{ number_format((int)$zone->base_tariff_xof, 0, ',', ' ') }} XOF)
                            </option>
                        @endforeach
                    </select>
                    <input type="number" step="0.1" min="0" name="weight_kg" value="{{ old('weight_kg', 1) }}" placeholder="Estimated weight (kg)" class="rounded border border-[#5a2080] bg-[#120024] px-3 py-2 text-white">
                    <textarea name="shipping_address" placeholder="Shipping address" required class="rounded border border-[#5a2080] bg-[#120024] px-3 py-2 text-white md:col-span-2">{{ old('shipping_address', $order?->shipping_address) }}</textarea>
                    <textarea name="notes" placeholder="Notes (optional)" class="rounded border border-[#5a2080] bg-[#120024] px-3 py-2 text-white md:col-span-2">{{ old('notes', $order?->notes) }}</textarea>
                    <button class="rounded-xl bg-[#d304f4] px-4 py-2 font-semibold md:col-span-2">Save step 1</button>
                </form>
            </x-card>

            <x-card class="{{ $hasStepOne ? '' : 'opacity-60' }}">
                <div id="step-2-anchor" class="h-0"></div>
                <h2 class="text-xl font-semibold mb-4">Step 2 - Payment method</h2>
                @if(!$hasStepOne)
                    <p class="text-[#c4b5d6]">Complete step 1 first to unlock payment options.</p>
                @else
                    <form method="POST" action="{{ route('client.checkout.step2', $order->id) }}" class="space-y-3">
                        @csrf
                        <label class="flex items-center gap-2"><input type="radio" name="payment_method" value="stripe" @checked($order->payment_method === 'stripe')> Stripe</label>
                        <label class="flex items-center gap-2"><input type="radio" name="payment_method" value="cinet" @checked($order->payment_method === 'cinet')> CinetPay</label>
                        <label class="flex items-center gap-2"><input type="radio" name="payment_method" value="paypal" @checked($order->payment_method === 'paypal')> PayPal</label>
                        <label class="flex items-center gap-2"><input type="radio" name="payment_method" value="cod" @checked($order->payment_method === 'cod')> Cash on Delivery</label>
                        <button class="rounded-xl border border-[#d304f4] px-4 py-2 font-semibold text-[#d304f4] hover:bg-[#d304f4] hover:text-white transition-colors">Save step 2</button>
                    </form>
                @endif
            </x-card>

            <x-card class="{{ $canConfirm ? '' : 'opacity-60' }}">
                <div id="step-3-anchor" class="h-0"></div>
                <h2 class="text-xl font-semibold mb-4">Step 3 - Confirm</h2>
                @if(!$canConfirm)
                    <p class="text-[#c4b5d6]">Select a payment method before confirming the order.</p>
                @else
                    <form method="POST" action="{{ route('client.checkout.confirm', $order->id) }}">
                        @csrf
                        <button class="rounded-xl bg-[#22c55e] px-4 py-2 font-semibold hover:bg-[#16a34a] transition-colors">Confirm order</button>
                    </form>
                @endif
            </x-card>
        @endif
    </section>

    <aside>
        <x-card class="sticky top-28">
            <h3 class="text-xl font-bold mb-4">Order summary</h3>
            <div class="space-y-2 text-sm">
                @forelse($cart?->items ?? [] as $item)
                    <div class="flex justify-between gap-2">
                        <span class="text-[#c4b5d6]">{{ $item->product?->name ?? 'Product' }} x{{ $item->quantity }}</span>
                        <span>{{ number_format((int)($item->quantity * $item->unit_price_xof), 0, ',', ' ') }} XOF</span>
                    </div>
                @empty
                    <p class="text-[#c4b5d6]">No items.</p>
                @endforelse
            </div>
            <div class="mt-4 border-t border-[#5a2080] pt-3 space-y-2">
                <div class="flex justify-between"><span class="text-[#c4b5d6]">Subtotal</span><span>{{ number_format($subtotal, 0, ',', ' ') }} XOF</span></div>
                @if($order)
                    <div class="flex justify-between"><span class="text-[#c4b5d6]">Delivery</span><span>{{ number_format((int)$order->delivery_fee_xof, 0, ',', ' ') }} XOF</span></div>
                    <div class="flex justify-between text-lg font-semibold"><span>Total</span><span class="text-[#d304f4]">{{ number_format((int)$order->total_xof, 0, ',', ' ') }} XOF</span></div>
                @endif
            </div>
        </x-card>
    </aside>
</div>

<script>
    (function () {
        const currentStep = {{ $currentStep }};
        const target = document.getElementById(`step-${currentStep}-anchor`);
        if (!target) return;

        requestAnimationFrame(() => {
            const yOffset = -110;
            const y = target.getBoundingClientRect().top + window.pageYOffset + yOffset;
            window.scrollTo({ top: Math.max(y, 0), behavior: 'smooth' });
        });
    })();
</script>
@endsection
