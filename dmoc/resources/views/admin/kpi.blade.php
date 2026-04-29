@extends('layouts.admin')
@section('title','KPI')
@section('content')
<h1 class='text-3xl font-bold mb-4'>KPI</h1>
<div class='grid md:grid-cols-2 xl:grid-cols-4 gap-4'><x-card><a href='{{ route('admin.orders') }}'>Commandes</a></x-card><x-card><a href='{{ route('admin.products') }}'>Produits</a></x-card><x-card><a href='{{ route('admin.couriers') }}'>Livreurs</a></x-card><x-card><a href='{{ route('admin.zones') }}'>Zones</a></x-card></div>
@endsection