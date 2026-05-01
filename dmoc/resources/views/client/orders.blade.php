@extends('layouts.client')
@section('title','Commandes')
@section('content')
<h1 class='text-3xl font-bold mb-4'>Mes commandes</h1>
<p class="mb-6 text-[#c4b5d6]">Retrouve ici l’historique complet de tes achats et leur statut.</p>

@if($orders->isEmpty())
    <x-card class="mb-4">
        <p class="text-[#c4b5d6]">Aucune commande pour le moment.</p>
    </x-card>
@else
    <x-card>
        <div class="overflow-x-auto">
            <table class="w-full text-left">
                <thead>
                    <tr class="border-b border-[#5a2080] text-[#c4b5d6] text-sm uppercase tracking-wide">
                        <th class="py-3">Commande</th>
                        <th class="py-3">Date</th>
                        <th class="py-3">Zone</th>
                        <th class="py-3">Total</th>
                        <th class="py-3">Statut</th>
                        <th class="py-3">Action</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($orders as $order)
                        <tr class="border-b border-[#2a0550] hover:bg-[#2a0550]/30 transition-colors">
                            <td class="py-3">#{{ $order->id }}</td>
                            <td class="py-3">{{ $order->created_at?->format('d/m/Y H:i') }}</td>
                            <td class="py-3">{{ $order->zone?->name ?? '-' }}</td>
                            <td class="py-3">{{ number_format($order->total_xof, 0, ',', ' ') }} XOF</td>
                            <td class="py-3">
                                <span class="rounded-full border border-[#5a2080] px-3 py-1 text-xs uppercase">
                                    {{ $order->status }}
                                </span>
                            </td>
                            <td class="py-3">
                                <a href="{{ route('client.orders.show', $order->id) }}" class="text-[#d304f4] hover:underline">Voir detail</a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </x-card>

    <div class="mt-4">
        {{ $orders->links() }}
    </div>
@endif
@endsection