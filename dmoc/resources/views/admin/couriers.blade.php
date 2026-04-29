@extends('layouts.admin')
@section('title','Livreurs')
@section('content')
<h1 class='text-3xl font-bold mb-4'>Livreurs</h1>
<x-card><table class='w-full text-left'><tbody><tr><td>Moussa</td><td><a href='{{ route('admin.couriers') }}' class='text-[#d304f4]'>Voir profil</a> | <a href='{{ route('admin.zones') }}' class='text-[#d304f4]'>Modifier zone</a> | <a href='{{ route('admin.couriers') }}' class='text-[#ef4444]'>Suspendre</a></td></tr></tbody></table></x-card>
<x-card class='mt-6'><x-btn-primary onclick="window.location.href='{{ route('admin.couriers') }}'">Ajouter livreur</x-btn-primary></x-card>
@endsection