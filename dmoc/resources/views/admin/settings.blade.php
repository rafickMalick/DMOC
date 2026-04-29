@extends('layouts.admin')
@section('title','Parametres')
@section('content')
<h1 class='text-3xl font-bold mb-4'>Parametres</h1>
<div class='sticky bottom-4 mt-4'><x-btn-primary class='w-full' onclick="window.location.href='{{ route('admin.settings') }}'">Sauvegarder</x-btn-primary></div>
@endsection