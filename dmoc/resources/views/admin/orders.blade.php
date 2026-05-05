@extends('layouts.admin')
@section('title','Commandes')
@section('content')
<h1 class='text-3xl font-bold mb-2'>Commandes</h1>
<p class="mb-6 text-[#c4b5d6]">Suivi des commandes, filtrage rapide et acces detaille.</p>

<x-card class="mb-6">
    <form method="GET" action="{{ route('admin.orders') }}" class="grid gap-3 md:grid-cols-5">
        <input
            type="text"
            name="q"
            value="{{ $q }}"
            placeholder="Recherche id/email/client"
            class="rounded border border-[#5a2080] bg-[#120024] px-3 py-2 text-white"
        />
        <select name="status" class="rounded border border-[#5a2080] bg-[#120024] px-3 py-2 text-white">
            <option value="">Tous les statuts</option>
            @foreach($statuses as $statusItem)
                <option value="{{ $statusItem->value }}" @selected($status === $statusItem->value)>{{ $statusItem->label() }}</option>
            @endforeach
        </select>
        <input type="date" name="from_date" value="{{ $fromDate }}" class="rounded border border-[#5a2080] bg-[#120024] px-3 py-2 text-white" />
        <input type="date" name="to_date" value="{{ $toDate }}" class="rounded border border-[#5a2080] bg-[#120024] px-3 py-2 text-white" />
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
                    <th class="py-3">Date</th>
                    <th class="py-3">Total</th>
                    <th class="py-3">Statut</th>
                    <th class="py-3">Livreur</th>
                    <th class="py-3">Suivi / Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($orders as $order)
                    <tr class="border-b border-[#2a0550] hover:bg-[#2a0550]/30 transition-colors">
                        <td class="py-3">#{{ $order->id }}</td>
                        <td class="py-3">{{ $order->user?->name }}<br><span class="text-xs text-[#c4b5d6]">{{ $order->user?->email }}</span></td>
                        <td class="py-3">{{ $order->created_at?->format('d/m/Y H:i') }}</td>
                        <td class="py-3">{{ number_format((int)$order->total_xof, 0, ',', ' ') }} XOF</td>
                        <td class="py-3">
                            <span class="rounded-full border px-3 py-1 text-xs uppercase {{ \App\Support\StatusHelper::orderBadgeClasses($order->status) }}">
                                {{ \App\Support\StatusHelper::orderLabel($order->status) }}
                            </span>
                        </td>
                        <td class="py-3">{{ $order->delivery?->courier?->user?->name ?? '-' }}</td>
                        <td class="py-3">
                            <div class="space-y-2">
                                <a href="{{ route('admin.orders.show', $order->id) }}" class="text-[#d304f4] hover:underline">Voir detail</a>
                                <form method="POST" action="{{ route('admin.orders.status', $order->id) }}" class="flex gap-2">
                                    @csrf
                                    <select name="status" class="rounded border border-[#5a2080] bg-[#120024] px-2 py-1 text-xs text-white">
                                        <option value="">Changer statut</option>
                                        @foreach(\App\Support\StatusHelper::allowedOrderTransitions($order->status) as $next)
                                            <option value="{{ $next }}">{{ \App\Support\StatusHelper::orderLabel($next) }}</option>
                                        @endforeach
                                    </select>
                                    <button class="rounded border border-[#5a2080] px-2 py-1 text-xs">OK</button>
                                </form>
                                @if($order->status === \App\Enums\OrderStatus::Confirmed->value || $order->status === \App\Enums\OrderStatus::Assigned->value)
                                    <form method="POST" action="{{ route('admin.orders.assign-courier', $order->id) }}" class="flex gap-2">
                                        @csrf
                                        <select name="courier_id" class="rounded border border-[#5a2080] bg-[#120024] px-2 py-1 text-xs text-white">
                                            <option value="">Assigner livreur</option>
                                            @foreach($activeCouriers as $courier)
                                                <option value="{{ $courier->id }}">{{ $courier->user?->name ?? 'Livreur' }}</option>
                                            @endforeach
                                        </select>
                                        <button class="rounded border border-[#5a2080] px-2 py-1 text-xs">Assigner</button>
                                    </form>
                                @endif
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="py-4 text-[#c4b5d6]">Aucune commande trouvee.</td>
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