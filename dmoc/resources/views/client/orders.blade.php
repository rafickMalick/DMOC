@extends('layouts.client')
@section('title','Commandes')
@section('content')
<h1 class='text-3xl font-bold mb-4'>Mes commandes</h1>
<x-card><table class='w-full text-left'><tbody><tr><td>#00124</td><td><a href='{{ route('client.tracking') }}' class='text-[#d304f4]'>Suivre</a> | <a href='{{ route('client.product') }}' class='text-[#d304f4]'>Voir</a></td></tr></tbody></table></x-card>
<div class='mt-4 flex gap-2'><a href='{{ route('client.orders') }}' class='rounded border border-[#5a2080] px-3 py-2'>Prev</a><a href='{{ route('client.orders') }}' class='rounded bg-[#d304f4] px-3 py-2'>1</a><a href='{{ route('client.orders') }}' class='rounded border border-[#5a2080] px-3 py-2'>Next</a></div>
@endsection