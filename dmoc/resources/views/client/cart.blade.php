@extends('layouts.client')
@section('title','Panier')
@section('content')
<h1 class="text-3xl font-bold mb-4">My Cart</h1>

<div class="grid lg:grid-cols-[1fr_360px] gap-6">
    <section class="space-y-4">
        @if(!$cart || $cart->items->isEmpty())
            <x-card>
                <p class="text-[#c4b5d6]">Your cart is empty.</p>
                <a href="{{ route('client.catalog') }}" class="inline-block mt-4 rounded-xl bg-[#d304f4] px-4 py-2 font-semibold">Go to catalog</a>
            </x-card>
        @else
            @foreach($cart->items as $item)
                <x-card class="flex items-center justify-between gap-4">
                    <div>
                        <h3 class="text-lg font-semibold">{{ $item->product?->name ?? 'Product removed' }}</h3>
                        <p class="text-sm text-[#c4b5d6]">Quantity: {{ $item->quantity }}</p>
                        <p class="text-sm text-[#c4b5d6]">Unit price: {{ number_format((int)$item->unit_price_xof, 0, ',', ' ') }} XOF</p>
                    </div>
                    <div class="text-right">
                        <p class="text-[#d304f4] text-lg font-bold">{{ number_format((int)($item->quantity * $item->unit_price_xof), 0, ',', ' ') }} XOF</p>
                    </div>
                </x-card>
            @endforeach
        @endif
    </section>

    <aside>
        <x-card class="sticky top-28">
            <h2 class="text-xl font-bold mb-4">Summary</h2>
            <div class="space-y-2 text-sm">
                <div class="flex justify-between"><span class="text-[#c4b5d6]">Items</span><span>{{ $itemsCount }}</span></div>
                <div class="flex justify-between"><span class="text-[#c4b5d6]">Subtotal</span><span>{{ number_format($subtotal, 0, ',', ' ') }} XOF</span></div>
            </div>
            @if($subtotal > 0)
                <a href="{{ route('client.checkout') }}" class="mt-5 inline-flex w-full justify-center rounded-xl bg-[#d304f4] px-4 py-3 font-semibold">
                    Proceed to checkout
                </a>
            @endif
        </x-card>
    </aside>
</div>
@endsection