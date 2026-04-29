@extends('layouts.courier')
@section('title','Detail')
@section('content')
<div class='mb-4 flex items-center justify-between'><h1 class='text-3xl font-bold'>Commande #DMOC-00124</h1><x-btn-outline onclick="window.location.href='{{ route('courier.list') }}'">Retour liste</x-btn-outline></div>
<div class='grid lg:grid-cols-2 gap-4'><x-card>Infos client</x-card><x-card>Articles</x-card></div>
<div class='sticky bottom-0 mt-4 flex flex-wrap gap-2 rounded-xl border border-[#5a2080] bg-[#1a0035] p-3'><button class='flex-1 rounded-xl bg-[#22c55e] px-4 py-3' onclick="window.location.href='{{ route('courier.list') }}'">Confirmer la livraison</button><button class='rounded-xl border border-[#f59e0b] px-4 py-3 text-[#f59e0b]' onclick="window.location.href='{{ route('courier.list') }}'">Signaler probleme</button><button class='rounded-xl border border-white px-4 py-3' onclick="window.location.href='tel:+221770000000'">Contacter client</button></div>
@endsection