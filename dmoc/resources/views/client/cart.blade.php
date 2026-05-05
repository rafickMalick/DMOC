@extends('layouts.client')
@section('title','Panier')
@section('content')
<h1 class="mb-4 text-3xl font-bold">Mon panier</h1>
<livewire:cart.cart-manager />
@endsection