@extends('layouts.client')
@section('title','Wishlist')
@section('content')
<div class='mb-4 flex items-center justify-between'><h1 class='text-3xl font-bold'>Ma wishlist (8 articles)</h1><x-btn-primary onclick="window.location.href='{{ route('client.cart') }}'">Tout ajouter au panier</x-btn-primary></div>
<div class='grid grid-cols-2 lg:grid-cols-4 gap-4'>
  <x-card>💖 Produit 1<div class='mt-2'><x-btn-primary onclick="window.location.href='{{ route('client.cart') }}'">Ajouter</x-btn-primary> <x-btn-outline onclick="window.location.href='{{ route('client.wishlist') }}'">Supprimer</x-btn-outline></div></x-card>
  <x-card>💖 Produit 2<div class='mt-2'><x-btn-primary onclick="window.location.href='{{ route('client.cart') }}'">Ajouter</x-btn-primary> <x-btn-outline onclick="window.location.href='{{ route('client.wishlist') }}'">Supprimer</x-btn-outline></div></x-card>
</div>
@endsection