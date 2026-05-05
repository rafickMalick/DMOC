@extends('layouts.admin')
@section('title','Zones')
@section('content')
<h1 class='text-3xl font-bold mb-4'>Gestion des zones de livraison</h1>
@if(session('success'))<div class="mb-4 rounded border border-emerald-500/40 bg-emerald-500/10 px-4 py-3 text-emerald-300">{{ session('success') }}</div>@endif
@if(session('error'))<div class="mb-4 rounded border border-red-500/40 bg-red-500/10 px-4 py-3 text-red-300">{{ session('error') }}</div>@endif
<x-card class="mb-6">
    <form method="POST" action="{{ route('admin.zones.store') }}" class="grid gap-3 md:grid-cols-4">@csrf
        <input name="name" placeholder="Nom zone" required class="rounded border border-[#5a2080] bg-[#120024] px-3 py-2 text-white">
        <input type="number" name="base_tariff_xof" placeholder="Tarif fixe XOF" required class="rounded border border-[#5a2080] bg-[#120024] px-3 py-2 text-white">
        <input type="number" name="per_kg_xof" placeholder="Tarif /kg XOF" required class="rounded border border-[#5a2080] bg-[#120024] px-3 py-2 text-white">
        <button class="rounded bg-[#d304f4] px-4 py-2">Ajouter</button>
    </form>
</x-card>
<x-card>
    <div class="overflow-x-auto">
        <table class="w-full text-left">
            <thead><tr class="border-b border-[#5a2080] text-sm uppercase text-[#c4b5d6]"><th class="py-3">Nom</th><th class="py-3">Tarif fixe</th><th class="py-3">/kg</th><th class="py-3">Commandes</th><th class="py-3">Actions</th></tr></thead>
            <tbody>
                @forelse($zones as $zone)
                    <tr class="border-b border-[#2a0550]">
                        <td class="py-3">{{ $zone->name }}</td>
                        <td class="py-3">{{ number_format((int)$zone->base_tariff_xof, 0, ',', ' ') }}</td>
                        <td class="py-3">{{ number_format((int)$zone->per_kg_xof, 0, ',', ' ') }}</td>
                        <td class="py-3">{{ $zone->orders_count }}</td>
                        <td class="py-3 flex gap-2">
                            <form method="POST" action="{{ route('admin.zones.update', $zone->id) }}" class="flex gap-1">@csrf @method('PUT')
                                <input type="hidden" name="name" value="{{ $zone->name }}"><input type="hidden" name="base_tariff_xof" value="{{ $zone->base_tariff_xof }}"><input type="hidden" name="per_kg_xof" value="{{ $zone->per_kg_xof }}">
                                <button class="rounded border border-[#5a2080] px-3 py-1 text-xs">Maj</button>
                            </form>
                            <form method="POST" action="{{ route('admin.zones.destroy', $zone->id) }}" onsubmit="return confirm('Supprimer cette zone ?');">@csrf @method('DELETE')<button class="rounded border border-red-500/60 px-3 py-1 text-xs text-red-300">Supprimer</button></form>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="5" class="py-4 text-[#c4b5d6]">Aucune zone.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</x-card>
<div class="mt-4">{{ $zones->links() }}</div>
@endsection