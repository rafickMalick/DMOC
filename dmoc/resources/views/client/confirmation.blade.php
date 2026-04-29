@extends('layouts.client')
@section('title','Confirmation')
@section('content')
<div class='relative min-h-[70vh] rounded-xl border border-[#5a2080] bg-[#1a0035] p-6 flex items-center justify-center text-center'>
  <div><h1 class='mt-4 text-5xl font-black'>Commande confirmee !</h1><p class='mt-2 text-[#c4b5d6]'>Numero <span class='text-[#d304f4] font-bold'>#DMOC-8457</span></p>
  <div class='mt-5 flex gap-2 justify-center'><x-btn-primary onclick="window.location.href='{{ route('client.tracking') }}'">Suivre ma commande</x-btn-primary><x-btn-outline onclick="window.location.href='{{ route('client.home') }}'">Retour a l'accueil</x-btn-outline></div></div>
</div>
@endsection