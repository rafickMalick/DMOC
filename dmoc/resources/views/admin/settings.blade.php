@extends('layouts.admin')
@section('title','Parametres')
@section('content')
<h1 class='text-3xl font-bold mb-4'>Parametres</h1>
@if(session('success'))<div class="mb-4 rounded border border-emerald-500/40 bg-emerald-500/10 px-4 py-3 text-emerald-300">{{ session('success') }}</div>@endif
@if($errors->any())<div class="mb-4 rounded border border-red-500/40 bg-red-500/10 px-4 py-3 text-red-300">{{ $errors->first() }}</div>@endif
<x-card>
    <form method="POST" action="{{ route('admin.settings.save') }}" class="grid gap-3 md:grid-cols-2">@csrf
        <input name="shop_name" value="{{ old('shop_name', $settings['shop_name'] ?? '') }}" placeholder="Nom boutique" class="rounded border border-[#5a2080] bg-[#120024] px-3 py-2 text-white">
        <input name="shop_email" value="{{ old('shop_email', $settings['shop_email'] ?? '') }}" placeholder="Email contact" class="rounded border border-[#5a2080] bg-[#120024] px-3 py-2 text-white">
        <input name="shop_phone" value="{{ old('shop_phone', $settings['shop_phone'] ?? '') }}" placeholder="Telephone contact" class="rounded border border-[#5a2080] bg-[#120024] px-3 py-2 text-white">
        <input name="default_delivery_fee_xof" type="number" min="0" value="{{ old('default_delivery_fee_xof', $settings['default_delivery_fee_xof'] ?? 0) }}" placeholder="Frais livraison par defaut" class="rounded border border-[#5a2080] bg-[#120024] px-3 py-2 text-white">
        <textarea name="shop_address" placeholder="Adresse" class="rounded border border-[#5a2080] bg-[#120024] px-3 py-2 text-white md:col-span-2">{{ old('shop_address', $settings['shop_address'] ?? '') }}</textarea>
        <button class="rounded bg-[#d304f4] px-4 py-2 font-semibold md:col-span-2">Sauvegarder</button>
    </form>
</x-card>
<x-card class="mt-6">
    <h2 class="text-xl font-semibold mb-3">Zones configurees</h2>
    <ul class="space-y-2">
        @foreach($zones as $zone)
            <li class="rounded border border-[#5a2080] px-3 py-2">{{ $zone->name }} — {{ number_format((int)$zone->base_tariff_xof, 0, ',', ' ') }} + {{ number_format((int)$zone->per_kg_xof, 0, ',', ' ') }}/kg</li>
        @endforeach
    </ul>
</x-card>
@endsection