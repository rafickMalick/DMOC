@extends('layouts.courier')
@section('title','Liste')
@section('content')
<h1 class='text-3xl font-bold mb-4'>Mes livraisons du jour</h1>
<article class='rounded-xl border-l-4 border-[#f59e0b] bg-[#1a0035] p-4 shadow-lg shadow-purple-900/40 mb-3'>#DMOC-00124 - Entrepot -> Client<br>Client: Awa - <a href='tel:+221770000000' class='text-[#d304f4]'>Appeler</a><br><x-btn-primary class='mt-2' onclick="window.location.href='{{ route('courier.detail') }}'">Demarrer</x-btn-primary></article>
<article class='rounded-xl border-l-4 border-[#d304f4] bg-[#1a0035] p-4 shadow-lg shadow-purple-900/40'>#DMOC-00122 - En cours<br><x-btn-primary class='mt-2' onclick="window.location.href='{{ route('courier.detail') }}'">Confirmer livraison</x-btn-primary></article>
@endsection