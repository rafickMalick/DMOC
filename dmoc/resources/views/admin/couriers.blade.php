@extends('layouts.admin')
@section('title','Livreurs')
@section('content')
<div class="mb-4 flex items-center justify-between gap-3">
    <h1 class='text-3xl font-bold'>Livreurs</h1>
    <button id="openM" class="rounded-xl bg-[#d304f4] px-4 py-2 font-semibold hover:bg-[#b003cc] transition-colors">Creer un livreur</button>
</div>
@if(session('success'))<div class="mb-4 rounded border border-emerald-500/40 bg-emerald-500/10 px-4 py-3 text-emerald-300">{{ session('success') }}</div>@endif
@if(session('error'))<div class="mb-4 rounded border border-red-500/40 bg-red-500/10 px-4 py-3 text-red-300">{{ session('error') }}</div>@endif

<x-card class="mb-6">
    <form method="GET" class="grid gap-3 md:grid-cols-3">
        <input type="text" name="q" value="{{ $q }}" placeholder="Recherche nom" class="rounded border border-[#5a2080] bg-[#120024] px-3 py-2 text-white">
        <select name="status" class="rounded border border-[#5a2080] bg-[#120024] px-3 py-2 text-white">
            <option value="">Tous statuts</option>
            <option value="active" @selected($status === 'active')>Actif</option>
            <option value="inactive" @selected($status === 'inactive')>Inactif</option>
        </select>
        <button class="rounded bg-[#d304f4] px-4 py-2">Filtrer</button>
    </form>
</x-card>

<x-card>
    <div class="grid gap-4 md:grid-cols-2 xl:grid-cols-3">
        @forelse($couriers as $courier)
            <a
                id="courier-card-{{ $courier->id }}"
                href="{{ route('admin.couriers.show', $courier->id) }}"
                class="block rounded-2xl border {{ session('new_courier_id') == $courier->id ? 'border-emerald-400/80 ring-2 ring-emerald-400/50' : 'border-[#5a2080]/70' }} bg-[#120024] p-4 hover:border-[#d304f4] transition-colors"
            >
                <div class="mb-3 flex items-start justify-between">
                    <div class="flex items-start gap-3">
                        @if(!empty($courier->profile_photo_path))
                            <img src="{{ asset('storage/'.$courier->profile_photo_path) }}" alt="{{ $courier->user?->name }}" class="h-12 w-12 rounded-full object-cover border border-[#5a2080]">
                        @else
                            <div class="flex h-12 w-12 items-center justify-center rounded-full border border-[#5a2080] text-sm font-bold text-[#c4b5d6]">
                                {{ strtoupper(substr((string)($courier->user?->name ?? 'L'), 0, 1)) }}
                            </div>
                        @endif
                        <div>
                        <h3 class="text-lg font-semibold">{{ $courier->user?->name }}</h3>
                        <p class="text-xs text-[#c4b5d6]">{{ $courier->user?->email }}</p>
                        </div>
                    </div>
                    <span class="rounded-full border px-3 py-1 text-xs {{ $courier->status === 'active' ? 'border-emerald-500/40 text-emerald-300' : 'border-red-500/40 text-red-300' }}">{{ $courier->status }}</span>
                </div>
                <div class="space-y-1 text-sm">
                    <p><span class="text-[#c4b5d6]">Telephone:</span> {{ $courier->user?->phone ?? '-' }}</p>
                    <p><span class="text-[#c4b5d6]">Zone:</span> {{ $courier->delivery_zone ?? '-' }}</p>
                    <p><span class="text-[#c4b5d6]">Vehicule:</span> {{ $courier->vehicle_type }}</p>
                    <p><span class="text-[#c4b5d6]">Plaque:</span> {{ $courier->vehicle_plate }}</p>
                    <p><span class="text-[#c4b5d6]">Note:</span> {{ number_format((float)$courier->rating, 1) }}/5</p>
                </div>
                <div class="mt-3 grid grid-cols-3 gap-2 text-center text-xs">
                    <div class="rounded border border-[#5a2080] px-2 py-1"><div class="text-[#c4b5d6]">En cours</div><div class="font-semibold">{{ $courier->missions_in_progress }}</div></div>
                    <div class="rounded border border-[#5a2080] px-2 py-1"><div class="text-[#c4b5d6]">Livrees</div><div class="font-semibold">{{ $courier->missions_completed }}</div></div>
                    <div class="rounded border border-[#5a2080] px-2 py-1"><div class="text-[#c4b5d6]">Echecs</div><div class="font-semibold">{{ $courier->missions_failed }}</div></div>
                </div>
                <div class="mt-3 flex gap-2" onclick="event.preventDefault();event.stopPropagation();">
                    <form method="POST" action="{{ route('admin.couriers.toggle', $courier->id) }}">@csrf<button class="rounded border border-[#5a2080] px-3 py-1 text-xs">{{ $courier->status === 'active' ? 'Desactiver' : 'Activer' }}</button></form>
                </div>
            </a>
        @empty
            <p class="text-[#c4b5d6]">Aucun profil livreur trouve.</p>
        @endforelse
    </div>
</x-card>
<div class="mt-4">{{ $couriers->links() }}</div>

<div id='modal' class='fixed inset-0 hidden items-center justify-center bg-black/70 p-4'>
    <div class='w-full max-w-2xl rounded-2xl bg-[#1a0035] border border-[#5a2080] p-6'>
        <h2 class="mb-4 text-2xl font-bold">Creer un livreur</h2>
        <form method="POST" action="{{ route('admin.couriers.store') }}" enctype="multipart/form-data" class="grid gap-3 md:grid-cols-2">@csrf
            <div><label class="mb-1 block text-xs text-[#c4b5d6]">Nom complet *</label><input name="name" required class="w-full rounded border border-[#5a2080] bg-[#120024] px-3 py-2 text-white"></div>
            <div><label class="mb-1 block text-xs text-[#c4b5d6]">Email *</label><input name="email" type="email" required class="w-full rounded border border-[#5a2080] bg-[#120024] px-3 py-2 text-white"></div>
            <div><label class="mb-1 block text-xs text-[#c4b5d6]">Telephone *</label><input name="phone" required class="w-full rounded border border-[#5a2080] bg-[#120024] px-3 py-2 text-white"></div>
            <div><label class="mb-1 block text-xs text-[#c4b5d6]">Mot de passe *</label><input name="password" type="password" required class="w-full rounded border border-[#5a2080] bg-[#120024] px-3 py-2 text-white"></div>
            <div><label class="mb-1 block text-xs text-[#c4b5d6]">Photo de profil (jpg/png/webp)</label><input type="file" name="profile_photo" accept=".jpg,.jpeg,.png,.webp" class="w-full rounded border border-[#5a2080] bg-[#120024] px-3 py-2 text-white"></div>
            <div><label class="mb-1 block text-xs text-[#c4b5d6]">Statut *</label><select name="status" class="w-full rounded border border-[#5a2080] bg-[#120024] px-3 py-2 text-white"><option value="active">Actif</option><option value="inactive">Inactif</option></select></div>
            <div class='md:col-span-2 flex justify-end gap-2'>
                <button type="button" id='closeM' class="rounded-xl border border-[#5a2080] px-4 py-2 hover:border-[#d304f4] transition-colors">Annuler</button>
                <button type="submit" class="rounded-xl bg-[#d304f4] px-4 py-2 font-semibold hover:bg-[#b003cc] transition-colors">Creer le livreur</button>
            </div>
        </form>
    </div>
</div>

<script>
const m = document.getElementById('modal');
const openM = document.getElementById('openM');
const closeM = document.getElementById('closeM');

openM?.addEventListener('click', () => {
    m.classList.remove('hidden');
    m.classList.add('flex');
});

closeM?.addEventListener('click', () => {
    m.classList.add('hidden');
    m.classList.remove('flex');
});

const newCourierId = @json(session('new_courier_id'));
if (newCourierId) {
    const card = document.getElementById(`courier-card-${newCourierId}`);
    card?.scrollIntoView({ behavior: 'smooth', block: 'center' });
}
</script>
@endsection