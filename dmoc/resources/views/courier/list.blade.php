@extends('layouts.courier')
@section('title','Liste')
@section('content')
<h1 class='text-3xl font-bold mb-4'>Mes livraisons du jour</h1>
<p class="mb-6 text-[#c4b5d6]">Consulte, demarre et termine tes missions depuis cette page.</p>

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

@if(!$courier)
    <article class='rounded-xl border border-[#5a2080] bg-[#1a0035] p-4'>
        Aucun profil livreur n'est associe a votre compte.
    </article>
@elseif($deliveries->isEmpty())
    <article class='rounded-xl border border-[#5a2080] bg-[#1a0035] p-4'>
        Aucune livraison assignee pour le moment.
    </article>
@else
    @foreach($deliveries as $delivery)
        <article class='rounded-2xl border border-[#5a2080]/60 bg-[#1a0035]/90 p-5 shadow-xl shadow-purple-900/20 mb-4'>
            <div class="flex flex-wrap items-center justify-between gap-2">
                <strong>#DEL-{{ $delivery->id }}</strong>
                <span class="rounded-full border border-[#5a2080] px-3 py-1 text-xs uppercase">{{ $delivery->status }}</span>
            </div>
            <p class="mt-2 text-[#c4b5d6]">
                Commande #{{ $delivery->order_id }} - Zone: {{ $delivery->zone?->name ?? '-' }}
            </p>
            <p class="text-[#c4b5d6]">
                Client: {{ $delivery->order?->recipient_name ?? $delivery->order?->user?->name ?? '-' }}
                @if($delivery->order?->shipping_phone)
                    - <a href='tel:{{ $delivery->order->shipping_phone }}' class='text-[#d304f4]'>Appeler</a>
                @endif
            </p>
            <div class="mt-3 flex gap-2">
                <a href="{{ route('courier.detail', $delivery->id) }}" class="rounded-xl bg-[#d304f4] px-4 py-2 text-sm font-semibold hover:bg-[#b003cc] transition-colors">Voir detail</a>
            </div>
        </article>
    @endforeach
@endif
@endsection