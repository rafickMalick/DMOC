@extends('layouts.admin')
@section('title','Zones')
@section('content')
<div class='mb-4 flex items-center justify-between'><h1 class='text-3xl font-bold'>Gestion des zones de livraison</h1><x-btn-primary onclick="window.location.href='{{ route('admin.zones') }}'">+ Nouvelle zone</x-btn-primary></div>
<div class='grid md:grid-cols-2 xl:grid-cols-3 gap-4'><x-card>Zone Nord <div class='mt-2'><a href='{{ route('admin.zones') }}' class='text-[#d304f4]'>Editer</a> | <a href='{{ route('admin.zones') }}' class='text-[#ef4444]'>Desactiver</a></div></x-card><x-card>Zone Centre <div class='mt-2'><a href='{{ route('admin.zones') }}' class='text-[#d304f4]'>Editer</a> | <a href='{{ route('admin.zones') }}' class='text-[#ef4444]'>Desactiver</a></div></x-card></div>
@endsection