@extends('layouts.client')
@section('title','Detail commande')
@section('content')
<h1 class="text-3xl font-bold mb-4">Commande #{{ $order->id }}</h1>
<p class="mb-6 text-[#c4b5d6]">Details de ta commande, du paiement et des articles.</p>

<x-card class="mb-6">
    <div class="grid gap-3 md:grid-cols-2">
        <p><span class="text-[#c4b5d6]">Statut:</span> {{ $order->status }}</p>
        <p><span class="text-[#c4b5d6]">Date:</span> {{ $order->created_at?->format('d/m/Y H:i') }}</p>
        <p><span class="text-[#c4b5d6]">Zone:</span> {{ $order->zone?->name ?? '-' }}</p>
        <p><span class="text-[#c4b5d6]">Paiement:</span> {{ $order->payment_method ?? '-' }}</p>
        <p><span class="text-[#c4b5d6]">Frais livraison:</span> {{ number_format((int)$order->delivery_fee_xof, 0, ',', ' ') }} XOF</p>
        <p><span class="text-[#c4b5d6]">Total:</span> {{ number_format((int)$order->total_xof, 0, ',', ' ') }} XOF</p>
    </div>
</x-card>

<x-card class="mb-6">
    <h2 class="text-xl font-semibold mb-3">Articles</h2>
    <div class="overflow-x-auto">
        <table class="w-full text-left">
            <thead>
                <tr class="border-b border-[#5a2080] text-[#c4b5d6]">
                    <th class="py-2">Produit</th>
                    <th class="py-2">Qte</th>
                    <th class="py-2">Prix</th>
                    <th class="py-2">Sous-total</th>
                </tr>
            </thead>
            <tbody>
                @foreach($order->items as $item)
                    <tr class="border-b border-[#2a0550]">
                        <td class="py-2">{{ $item->product?->name ?? 'Produit supprime' }}</td>
                        <td class="py-2">{{ $item->quantity }}</td>
                        <td class="py-2">{{ number_format((int)$item->price_xof, 0, ',', ' ') }} XOF</td>
                        <td class="py-2">{{ number_format((int)($item->quantity * $item->price_xof), 0, ',', ' ') }} XOF</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</x-card>

<div class="mt-2">
    <a href="{{ route('client.orders') }}" class="rounded border border-[#5a2080] px-3 py-2 hover:border-[#d304f4] hover:text-[#d304f4] transition-colors">Retour aux commandes</a>
</div>
@endsection
