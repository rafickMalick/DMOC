@extends('layouts.admin')
@section('title','Produits')
@section('content')
<div class='mb-4 flex items-center justify-between'><h1 class='text-3xl font-bold'>Produits</h1><x-btn-primary id='openM'>+ Ajouter un produit</x-btn-primary></div>
<x-card><table class='w-full text-left'><tbody><tr><td>Casque Pro</td><td><a href='{{ route('admin.products') }}' class='text-[#d304f4]'>Editer</a> | <a href='{{ route('admin.products') }}' class='text-[#d304f4]'>Dupliquer</a> | <a href='{{ route('admin.products') }}' class='text-[#ef4444]'>Supprimer</a></td></tr></tbody></table></x-card>
<div id='modal' class='fixed inset-0 hidden items-center justify-center bg-black/60 p-4'><div class='w-full max-w-2xl rounded-xl bg-[#1a0035] border border-[#5a2080] p-6'><h2 class='text-2xl font-bold mb-3'>Ajouter un produit</h2><div class='mt-3 flex justify-end gap-2'><x-btn-outline id='closeM'>Annuler</x-btn-outline><x-btn-primary onclick="window.location.href='{{ route('admin.products') }}'">Sauvegarder</x-btn-primary></div></div></div>
<script>const m=document.getElementById('modal');document.getElementById('openM')?.addEventListener('click',()=>{m.classList.remove('hidden');m.classList.add('flex')});document.getElementById('closeM')?.addEventListener('click',()=>{m.classList.add('hidden');m.classList.remove('flex')});</script>
@endsection