@extends('layouts.admin')
@section('title','Commandes')
@section('content')
<h1 class='text-3xl font-bold mb-2'>Commandes</h1>
<p class="mb-6 text-[#c4b5d6]">Suivi des commandes, filtrage rapide et acces detaille.</p>

<x-card class="mb-6">
    <form method="GET" action="{{ route('admin.orders') }}" class="grid gap-3 md:grid-cols-3">
        <input
            type="text"
            name="q"
            value="{{ $q }}"
            placeholder="Recherche id/email/client"
            class="rounded border border-[#5a2080] bg-[#120024] px-3 py-2 text-white"
        />
        <select name="status" class="rounded border border-[#5a2080] bg-[#120024] px-3 py-2 text-white">
            <option value="">Tous les statuts</option>
            @foreach(['pending','confirmed','preparing','shipped','delivered'] as $value)
                <option value="{{ $value }}" @selected($status === $value)>{{ $value }}</option>
            @endforeach
        </select>
        <button class="rounded bg-[#d304f4] px-3 py-2 font-semibold hover:bg-[#b003cc] transition-colors">Filtrer</button>
    </form>
</x-card>

<x-card>
    <div class="overflow-x-auto">
        <table class="w-full text-left">
            <thead>
                <tr class="border-b border-[#5a2080] text-[#c4b5d6] text-sm uppercase tracking-wide">
                    <th class="py-3">Commande</th>
                    <th class="py-3">Client</th>
                    <th class="py-3">Zone</th>
                    <th class="py-3">Total</th>
                    <th class="py-3">Statut</th>
                    <th class="py-3">Action</th>
                </tr>
            </thead>
            <tbody>
                @forelse($orders as $order)
                    <tr class="border-b border-[#2a0550] hover:bg-[#2a0550]/30 transition-colors">
                        <td class="py-3">#{{ $order->id }}</td>
                        <td class="py-3">{{ $order->user?->name }}<br><span class="text-xs text-[#c4b5d6]">{{ $order->user?->email }}</span></td>
                        <td class="py-3">{{ $order->zone?->name ?? '-' }}</td>
                        <td class="py-3">{{ number_format((int)$order->total_xof, 0, ',', ' ') }} XOF</td>
                        <td class="py-3"><span class="rounded-full border border-[#5a2080] px-3 py-1 text-xs uppercase">{{ $order->status }}</span></td>
                        <td class="py-3"><a href="{{ route('admin.orders.show', $order->id) }}" class="text-[#d304f4] hover:underline">Voir detail</a></td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="py-4 text-[#c4b5d6]">Aucune commande trouvee.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</x-card>

<div class="mt-4">
    {{ $orders->links() }}
</div>
@endsection