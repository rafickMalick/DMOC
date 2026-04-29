@extends('layouts.client')
@section('title','Profil')
@section('content')
<h1 class='text-3xl font-bold mb-4'>Mon profil</h1>
<div class='grid lg:grid-cols-[260px_1fr] gap-6'>
  <aside class='rounded-xl border border-[#5a2080] bg-[#1a0035] p-4'>
    <a href='{{ route('client.profile') }}' class='block rounded bg-[#d304f4] px-3 py-2 mb-2'>Mes informations</a>
    <a href='{{ route('client.profile') }}' class='block rounded px-3 py-2 hover:bg-[#5a2080] mb-2'>Securite</a>
    <a href='{{ route('client.profile') }}' class='block rounded px-3 py-2 hover:bg-[#5a2080] mb-2'>Adresses</a>
    <a href='{{ route('client.profile') }}' class='block rounded px-3 py-2 hover:bg-[#5a2080] mb-2'>Notifications</a>
    <a href='{{ route('client.auth') }}' class='block rounded px-3 py-2 hover:bg-[#5a2080]'>Supprimer mon compte</a>
  </aside>
  <x-card><x-btn-primary class='mt-4' onclick="window.location.href='{{ route('client.dashboard') }}'">Sauvegarder les modifications</x-btn-primary></x-card>
</div>
@endsection