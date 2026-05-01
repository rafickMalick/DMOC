@extends('layouts.courier')
@section('title','Recu COD')
@section('content')
<div class="mb-4 flex items-center justify-between">
    <h1 class="text-3xl font-bold">Recu paiement COD</h1>
    <a href="{{ route('courier.detail', $delivery->id) }}" class="rounded-xl border border-[#5a2080] px-4 py-2 hover:border-[#d304f4] hover:text-[#d304f4] transition-colors">Retour detail</a>
</div>
<p class="mb-6 text-[#c4b5d6]">Justificatif de paiement collecte a la livraison.</p>

<x-card>
    <div class="grid gap-3 md:grid-cols-2">
        <p><span class="text-[#c4b5d6]">Livraison:</span> #DEL-{{ $delivery->id }}</p>
        <p><span class="text-[#c4b5d6]">Commande:</span> #{{ $delivery->order_id }}</p>
        <p><span class="text-[#c4b5d6]">Client:</span> {{ $delivery->order?->recipient_name ?? $delivery->order?->user?->name ?? '-' }}</p>
        <p><span class="text-[#c4b5d6]">Telephone:</span> {{ $delivery->order?->shipping_phone ?? '-' }}</p>
        <p><span class="text-[#c4b5d6]">Montant recu:</span> {{ number_format((int)($payment?->amount_xof ?? 0), 0, ',', ' ') }} XOF</p>
        <p><span class="text-[#c4b5d6]">Reference paiement:</span> {{ $payment?->reference ?? '-' }}</p>
        <p><span class="text-[#c4b5d6]">No recu:</span> {{ $codEvent['receipt_number'] ?? 'Non genere' }}</p>
        <p><span class="text-[#c4b5d6]">Date confirmation:</span> {{ isset($codEvent['timestamp']) ? \Illuminate\Support\Carbon::parse($codEvent['timestamp'])->format('d/m/Y H:i') : '-' }}</p>
    </div>
</x-card>

<div class="mt-4">
    <button onclick="window.print()" class="rounded-xl bg-[#d304f4] px-4 py-2 font-semibold hover:bg-[#b003cc] transition-colors">Imprimer le recu</button>
</div>
@endsection
