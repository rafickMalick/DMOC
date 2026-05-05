@extends('layouts.admin')
@section('title','Detail commande')
@section('content')
<h1 class="text-3xl font-bold mb-2">Commande #{{ $order->id }}</h1>
<p class="mb-6 text-[#c4b5d6]">Vue detaillee de la commande, de la livraison et du paiement.</p>

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

<x-card class="mb-6">
    <div class="grid gap-3 md:grid-cols-2">
        <p><span class="text-[#c4b5d6]">Client:</span> {{ $order->user?->name }} ({{ $order->user?->email }})</p>
        <p><span class="text-[#c4b5d6]">Date:</span> {{ $order->created_at?->format('d/m/Y H:i') }}</p>
        <p><span class="text-[#c4b5d6]">Zone:</span> {{ $order->zone?->name ?? '-' }}</p>
        <p><span class="text-[#c4b5d6]">Paiement:</span> {{ $order->payment_method ?? '-' }}</p>
        <p><span class="text-[#c4b5d6]">Adresse:</span> {{ $order->shipping_address ?? '-' }}</p>
        <p><span class="text-[#c4b5d6]">Telephone:</span> {{ $order->shipping_phone ?? '-' }}</p>
        <p><span class="text-[#c4b5d6]">Frais livraison:</span> {{ number_format((int)$order->delivery_fee_xof, 0, ',', ' ') }} XOF</p>
        <p><span class="text-[#c4b5d6]">Total:</span> {{ number_format((int)$order->total_xof, 0, ',', ' ') }} XOF</p>
    </div>
</x-card>

<x-card class="mb-6">
    <h2 class="text-xl font-semibold mb-3">Changer le statut</h2>
    <form method="POST" action="{{ route('admin.orders.status', $order->id) }}" class="flex flex-wrap gap-3">
        @csrf
        <select name="status" class="rounded border border-[#5a2080] bg-[#120024] px-3 py-2 text-white">
            <option value="">Selectionner une transition</option>
            @foreach($allowedStatuses as $status)
                <option value="{{ $status }}">{{ \App\Support\StatusHelper::orderLabel($status) }}</option>
            @endforeach
        </select>
        <input type="text" name="note" placeholder="Note (optionnelle)" class="rounded border border-[#5a2080] bg-[#120024] px-3 py-2 text-white" />
        <button class="rounded bg-[#d304f4] px-4 py-2 font-semibold">Mettre a jour</button>
    </form>
</x-card>

<x-card class="mb-6">
    <h2 class="text-xl font-semibold mb-3">Assignation livreur</h2>
    <p class="mb-3 text-sm text-[#c4b5d6]">
        Livreur actuel:
        @if($order->delivery?->courier?->user)
            {{ $order->delivery->courier->user->name }} ({{ $order->delivery->courier->user->email }})
        @else
            Non assigne
        @endif
    </p>
    <form method="POST" action="{{ route('admin.orders.assign-courier', $order->id) }}" class="flex flex-wrap gap-3">
        @csrf
        <select name="courier_id" class="rounded border border-[#5a2080] bg-[#120024] px-3 py-2 text-white" required>
            <option value="">Selectionner un livreur</option>
            @foreach($couriers as $courier)
                <option value="{{ $courier->id }}">
                    {{ $courier->user?->name ?? 'Livreur' }} - {{ $courier->vehicle_type }} - note {{ $courier->rating }} - {{ $courier->missions_in_progress }} mission(s) en cours
                </option>
            @endforeach
        </select>
        <button class="rounded bg-[#d304f4] px-4 py-2 font-semibold">Assigner</button>
    </form>
</x-card>

<x-card class="mb-6">
    <h2 class="text-xl font-semibold mb-3">Annuler la commande</h2>
    <form method="POST" action="{{ route('admin.orders.cancel', $order->id) }}" class="flex flex-wrap gap-3" onsubmit="return confirm('Confirmer l annulation ?');">
        @csrf
        <input type="text" name="reason" required placeholder="Motif d annulation (obligatoire)" class="min-w-80 rounded border border-[#5a2080] bg-[#120024] px-3 py-2 text-white" />
        <button class="rounded border border-red-500/60 px-4 py-2 text-red-300">Annuler la commande</button>
    </form>
</x-card>

<x-card class="mb-6">
    <h2 class="text-xl font-semibold mb-3">Statut actuel</h2>
    <span class="rounded-full border px-3 py-1 text-xs uppercase {{ \App\Support\StatusHelper::orderBadgeClasses($order->status) }}">
        {{ \App\Support\StatusHelper::orderLabel($order->status) }}
    </span>
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

<x-card class="mb-6">
    <h2 class="text-xl font-semibold mb-3">Historique des statuts</h2>
    <div class="space-y-3">
        @forelse($order->statusLogs as $log)
            <div class="rounded border border-[#5a2080] px-3 py-2">
                <p class="text-sm">
                    <span class="text-[#c4b5d6]">{{ $log->created_at?->format('d/m/Y H:i') }}</span>
                    -
                    {{ \App\Support\StatusHelper::orderLabel((string) $log->from_status) }}
                    →
                    {{ \App\Support\StatusHelper::orderLabel($log->to_status) }}
                </p>
                <p class="text-xs text-[#c4b5d6]">
                    Par: {{ $log->actor?->name ?? 'Systeme' }}
                    @if($log->note)
                        - {{ $log->note }}
                    @endif
                </p>
            </div>
        @empty
            <p class="text-sm text-[#c4b5d6]">Aucun changement de statut enregistre.</p>
        @endforelse
    </div>
</x-card>

<div class="mt-2">
    <a href="{{ route('admin.orders') }}" class="rounded border border-[#5a2080] px-3 py-2 hover:border-[#d304f4] hover:text-[#d304f4] transition-colors">Retour liste commandes</a>
</div>
@endsection
