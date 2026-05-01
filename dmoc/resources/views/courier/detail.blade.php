@extends('layouts.courier')
@section('title','Detail')
@section('content')
<div class='mb-4 flex items-center justify-between'>
    <h1 class='text-3xl font-bold'>Livraison #DEL-{{ $delivery->id }}</h1>
    <a href='{{ route('courier.list') }}' class='rounded-xl border border-[#5a2080] px-4 py-2 hover:border-[#d304f4] hover:text-[#d304f4] transition-colors'>Retour liste</a>
</div>
<p class="mb-6 text-[#c4b5d6]">Gere cette livraison: navigation, statut, incident et paiement COD.</p>

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

<div class='grid lg:grid-cols-2 gap-4'>
    <x-card>
        <h2 class="mb-3 text-lg font-semibold">Infos client</h2>
        <p><span class="text-[#c4b5d6]">Commande:</span> #{{ $delivery->order_id }}</p>
        <p><span class="text-[#c4b5d6]">Statut livraison:</span> {{ $delivery->status }}</p>
        <p><span class="text-[#c4b5d6]">Nom:</span> {{ $delivery->order?->recipient_name ?? $delivery->order?->user?->name ?? '-' }}</p>
        <p><span class="text-[#c4b5d6]">Telephone:</span> {{ $delivery->order?->shipping_phone ?? '-' }}</p>
        <p><span class="text-[#c4b5d6]">Adresse:</span> {{ $delivery->order?->shipping_address ?? '-' }}</p>
        <p><span class="text-[#c4b5d6]">Zone:</span> {{ $delivery->zone?->name ?? '-' }}</p>
        @if($delivery->order?->shipping_address)
            <a
                class="mt-2 inline-block rounded border border-[#38bdf8] px-3 py-2 text-sm text-[#7dd3fc]"
                href="https://www.google.com/maps/search/?api=1&query={{ urlencode($delivery->order->shipping_address) }}"
                target="_blank"
                rel="noopener noreferrer"
            >
                Ouvrir navigation GPS
            </a>
        @endif
    </x-card>

    <x-card>
        <h2 class="mb-3 text-lg font-semibold">Articles</h2>
        @forelse($delivery->order?->items ?? [] as $item)
            <div class="mb-2 border-b border-[#2a0550] pb-2">
                <p>{{ $item->product?->name ?? 'Produit supprime' }}</p>
                <p class="text-sm text-[#c4b5d6]">Qte: {{ $item->quantity }} | Prix: {{ number_format((int)$item->price_xof, 0, ',', ' ') }} XOF</p>
            </div>
        @empty
            <p class="text-[#c4b5d6]">Aucun article.</p>
        @endforelse
    </x-card>
</div>

<div class='sticky bottom-0 mt-4 flex flex-wrap gap-2 rounded-2xl border border-[#5a2080]/70 bg-[#1a0035]/95 p-3 backdrop-blur-md'>
    <form method="POST" action="{{ route('courier.deliveries.start', $delivery->id) }}">
        @csrf
        <button class='rounded-xl border border-[#0ea5e9] px-4 py-3 text-[#7dd3fc] hover:bg-[#0ea5e9]/10 transition-colors'>Demarrer</button>
    </form>

    <form method="POST" action="{{ route('courier.deliveries.complete', $delivery->id) }}">
        @csrf
        <button class='rounded-xl bg-[#22c55e] px-4 py-3 font-semibold hover:bg-[#16a34a] transition-colors'>Confirmer livraison</button>
    </form>

    @if($delivery->order?->payment_method === 'cod')
        <form method="POST" action="{{ route('courier.deliveries.confirm-cod', $delivery->id) }}">
            @csrf
            <button class='rounded-xl border border-[#a78bfa] px-4 py-3 text-[#ddd6fe] hover:bg-[#a78bfa]/10 transition-colors'>Confirmer paiement COD</button>
        </form>
    @endif

    <form method="POST" action="{{ route('courier.deliveries.fail', $delivery->id) }}" class="flex flex-wrap gap-2">
        @csrf
        <input name="reason" placeholder="Raison echec (optionnel)" class="rounded-xl border border-[#f59e0b] bg-transparent px-3 py-2 text-sm" />
        <button class='rounded-xl border border-[#f59e0b] px-4 py-3 text-[#f59e0b] hover:bg-[#f59e0b]/10 transition-colors'>Signaler probleme</button>
    </form>

    @if($delivery->order?->shipping_phone)
        <a class='rounded-xl border border-white px-4 py-3 hover:bg-white/10 transition-colors' href='tel:{{ $delivery->order->shipping_phone }}'>Contacter client</a>
    @endif

    @if($delivery->order?->payment_method === 'cod')
        <a class='rounded-xl border border-[#facc15] px-4 py-3 text-[#facc15] hover:bg-[#facc15]/10 transition-colors' href='{{ route('courier.deliveries.receipt', $delivery->id) }}'>Voir recu</a>
    @endif
</div>
@endsection