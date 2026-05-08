@extends('layouts.client')
@section('title', 'Panier')
@section('content')
<h1 class="mb-4 text-3xl font-bold">Mon panier</h1>

<div class="grid lg:grid-cols-[1fr_360px] gap-6">
    <section class="space-y-4">
        @if(!$cart || $cart->items->isEmpty())
            <x-card>
                <p class="text-[#c4b5d6]">Ton panier est vide.</p>
                <a href="{{ route('client.catalog') }}" class="inline-block mt-4 rounded-xl bg-[#d304f4] px-4 py-2 font-semibold">Aller au catalogue</a>
            </x-card>
        @else
            @foreach($cart->items as $item)
                <x-card class="flex items-center justify-between gap-4">
                    <div class="flex-1">
                        <h3 class="text-lg font-semibold">{{ $item->product?->name ?? 'Produit supprime' }}</h3>
                        <p class="text-sm text-[#c4b5d6]">Prix unitaire : {{ number_format((int)$item->unit_price_xof, 0, ',', ' ') }} XOF</p>
                        <div class="mt-3 flex items-center gap-2">
                            <form method="POST" action="{{ route('client.cart.items.update', ['itemId' => $item->id]) }}" class="flex items-center gap-2">
                                @csrf
                                @method('PUT')
                                <input type="number" min="1" max="99" name="quantity" value="{{ $item->quantity }}"
                                       class="w-20 rounded border border-[#5a2080] bg-[#120024] px-2 py-1 text-white">
                                <button class="rounded-lg border border-[#5a2080] px-2 py-1 text-xs">Mettre a jour</button>
                            </form>
                            <form method="POST" action="{{ route('client.cart.items.delete', ['itemId' => $item->id]) }}">
                                @csrf
                                @method('DELETE')
                                <button class="rounded-lg border border-red-500/40 px-2 py-1 text-xs text-red-300">Supprimer</button>
                            </form>
                        </div>
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
            <h2 class="text-xl font-bold mb-4">Resume</h2>
            <div class="space-y-2 text-sm">
                <div class="flex justify-between"><span class="text-[#c4b5d6]">Articles</span><span>{{ $itemsCount }}</span></div>
                <div class="flex justify-between"><span class="text-[#c4b5d6]">Sous-total</span><span>{{ number_format($subtotal, 0, ',', ' ') }} XOF</span></div>
                <div class="flex justify-between"><span class="text-[#c4b5d6]">Reduction</span><span>-{{ number_format($discount, 0, ',', ' ') }} XOF</span></div>
                <div class="flex justify-between border-t border-[#5a2080]/40 pt-2 font-semibold"><span>Total</span><span>{{ number_format($total, 0, ',', ' ') }} XOF</span></div>
            </div>

            <form method="POST" action="{{ route('client.cart.promo.apply') }}" class="mt-4 space-y-2">
                @csrf
                <label class="text-sm text-[#c4b5d6]">Code promo</label>
                <div class="flex gap-2">
                    <input type="text" name="promo_code" placeholder="DMC5" class="w-full rounded-xl border border-[#9a5a2c] bg-[#140c06] px-3 py-2 text-sm text-white">
                    <button class="rounded-xl border border-[#5a2080] px-3 py-2 text-xs">Appliquer</button>
                </div>
                @if($promo)
                    <div class="flex items-center justify-between rounded-lg bg-[#120024] px-3 py-2 text-xs">
                        <span>Code actif: <strong>{{ $promo['code'] }}</strong></span>
                        <button type="submit" form="remove-promo-form" class="text-red-300">Retirer</button>
                    </div>
                @endif
            </form>
            <form id="remove-promo-form" method="POST" action="{{ route('client.cart.promo.remove') }}">
                @csrf
                @method('DELETE')
            </form>

            @if($subtotal > 0)
                <a href="{{ route('client.checkout') }}" class="mt-5 inline-flex w-full justify-center rounded-xl bg-[#d304f4] px-4 py-3 font-semibold">
                    Continuer vers checkout
                </a>
            @endif
        </x-card>
    </aside>
</div>

<script>
    const cartSnapshot = {
        items: {{ (int) $itemsCount }},
        subtotal: {{ (int) $subtotal }},
        updated_at: new Date().toISOString(),
    };
    localStorage.setItem('dmc_cart_snapshot', JSON.stringify(cartSnapshot));
</script>
@endsection