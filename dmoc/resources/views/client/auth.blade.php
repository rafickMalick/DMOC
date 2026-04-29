@extends('layouts.client')
@section('title','Auth')
@section('content')
<div class='mx-auto max-w-md rounded-xl border border-[#5a2080] bg-[#1a0035] p-6'>
  <div id='c' class='ap'><label>Email</label><x-input/><label class='mt-2 block'>Mot de passe</label><x-input id='pw' type='password'/><x-btn-primary class='mt-3 w-full' onclick="window.location.href='{{ route('client.dashboard') }}'">Se connecter</x-btn-primary><x-btn-outline class='w-full mt-2' onclick="window.location.href='{{ route('client.dashboard') }}'">Google</x-btn-outline></div>
  <div id='i' class='ap mt-4'><x-input placeholder='Prenom'/><x-btn-primary class='mt-3 w-full' onclick="window.location.href='{{ route('client.dashboard') }}'">Creer mon compte</x-btn-primary></div>
</div>
@endsection