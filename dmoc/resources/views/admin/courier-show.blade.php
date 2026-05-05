@extends('layouts.admin')
@section('title','Fiche livreur')
@section('content')
<div class="mb-6 flex items-center justify-between">
    <h1 class="text-3xl font-bold">Fiche livreur</h1>
    <a href="{{ route('admin.couriers') }}" class="rounded border border-[#5a2080] px-4 py-2">Retour</a>
</div>

<x-card class="mb-6">
    <div class="mb-4">
        @if(!empty($courier->profile_photo_path))
            <img src="{{ asset('storage/'.$courier->profile_photo_path) }}" alt="{{ $courier->user?->name }}" class="h-24 w-24 rounded-full object-cover border border-[#5a2080]">
        @else
            <div class="flex h-24 w-24 items-center justify-center rounded-full border border-[#5a2080] text-2xl font-bold text-[#c4b5d6]">
                {{ strtoupper(substr((string)($courier->user?->name ?? 'L'), 0, 1)) }}
            </div>
        @endif
    </div>
    <p><span class="text-[#c4b5d6]">Nom:</span> {{ $courier->user?->name }}</p>
    <p><span class="text-[#c4b5d6]">Email:</span> {{ $courier->user?->email }}</p>
    <p><span class="text-[#c4b5d6]">Telephone:</span> {{ $courier->user?->phone }}</p>
    <p><span class="text-[#c4b5d6]">Zone:</span> {{ $courier->delivery_zone ?? '-' }}</p>
    <p><span class="text-[#c4b5d6]">Statut:</span> {{ $courier->status }}</p>
</x-card>

<div class="grid gap-4 md:grid-cols-3 mb-6">
    <x-card><p class="text-[#c4b5d6]">Total livre</p><p class="text-2xl font-bold">{{ $stats['delivered'] }}</p></x-card>
    <x-card><p class="text-[#c4b5d6]">Total echoue</p><p class="text-2xl font-bold">{{ $stats['failed'] }}</p></x-card>
    <x-card><p class="text-[#c4b5d6]">COD collecte</p><p class="text-2xl font-bold">{{ number_format((int)$stats['cod'], 0, ',', ' ') }} XOF</p></x-card>
</div>

<x-card>
    <h2 class="text-xl font-semibold mb-3">Missions</h2>
    <div class="overflow-x-auto">
        <table class="w-full text-left">
            <thead><tr class="border-b border-[#5a2080] text-sm uppercase text-[#c4b5d6]"><th class="py-3">Commande</th><th class="py-3">Client</th><th class="py-3">Statut</th><th class="py-3">Date</th></tr></thead>
            <tbody>
                @forelse($missions as $mission)
                    <tr class="border-b border-[#2a0550]">
                        <td class="py-3">#{{ $mission->order_id }}</td>
                        <td class="py-3">{{ $mission->order?->user?->name ?? '-' }}</td>
                        <td class="py-3">{{ $mission->status }}</td>
                        <td class="py-3">{{ $mission->updated_at?->format('d/m/Y H:i') }}</td>
                    </tr>
                @empty
                    <tr><td colspan="4" class="py-4 text-[#c4b5d6]">Aucune mission.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</x-card>
<div class="mt-4">{{ $missions->links() }}</div>
@endsection
