@extends('layouts.admin')
@section('title','Commandes')
@section('content')
<h1 class='text-3xl font-bold mb-4'>Commandes</h1>
<x-card><table class='w-full text-left'><tbody><tr><td>#00124</td><td><a href='{{ route('admin.orders') }}' class='text-[#d304f4]'>Voir detail</a> | <a href='{{ route('admin.couriers') }}' class='text-[#d304f4]'>Assigner livreur</a></td></tr></tbody></table></x-card>
@endsection