@extends('layouts.client')
@section('title','Dashboard')
@section('content')
<x-page-header title='Bonjour, Amina 👋' breadcrumb='DMOC / Dashboard'><a href='{{ route('client.orders') }}' class='text-[#d304f4]'>Voir commandes</a></x-page-header>
<x-card>Dernieres commandes <x-btn-outline class='mt-2' onclick="window.location.href='{{ route('client.orders') }}'">Voir toutes mes commandes</x-btn-outline></x-card>
<x-card class='mt-4'>Produits recemment vus <x-btn-primary class='mt-2' onclick="window.location.href='{{ route('client.catalog') }}'">Retour catalogue</x-btn-primary></x-card>
@endsection