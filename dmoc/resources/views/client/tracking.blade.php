@extends('layouts.client')
@section('title','Tracking')
@section('content')
<h1 class='text-3xl font-bold mb-4'>Suivre ma commande</h1>
<div class='flex gap-2 mb-4'><x-input placeholder='Numero commande'/><x-btn-primary onclick="window.location.href='{{ route('client.tracking') }}'">Rechercher</x-btn-primary></div>
<x-btn-outline class='mt-4' onclick="window.location.href='{{ route('courier.detail') }}'">Contacter le livreur</x-btn-outline>
@endsection