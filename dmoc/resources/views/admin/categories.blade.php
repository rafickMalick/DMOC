@extends('layouts.admin')
@section('title','Categories')
@section('content')
<div class="mb-6 flex items-center justify-between">
    <h1 class="text-3xl font-bold">Categories</h1>
    <a href="{{ route('admin.products') }}" class="rounded border border-[#5a2080] px-4 py-2">Retour produits</a>
</div>
@if(session('success'))<div class="mb-4 rounded border border-emerald-500/40 bg-emerald-500/10 px-4 py-3 text-emerald-300">{{ session('success') }}</div>@endif
@if(session('error'))<div class="mb-4 rounded border border-red-500/40 bg-red-500/10 px-4 py-3 text-red-300">{{ session('error') }}</div>@endif
@if($errors->any())<div class="mb-4 rounded border border-red-500/40 bg-red-500/10 px-4 py-3 text-red-300">{{ $errors->first() }}</div>@endif

<x-card class="mb-6">
    <form method="POST" action="{{ route('admin.categories.store') }}" class="grid gap-3 md:grid-cols-3">@csrf
        <input name="name" placeholder="Nom categorie" class="rounded border border-[#5a2080] bg-[#120024] px-3 py-2 text-white" required />
        <input name="slug" placeholder="Slug (optionnel)" class="rounded border border-[#5a2080] bg-[#120024] px-3 py-2 text-white" />
        <button class="rounded bg-[#d304f4] px-4 py-2 font-semibold">Ajouter</button>
    </form>
</x-card>

<x-card>
    <div class="overflow-x-auto">
        <table class="w-full text-left">
            <thead><tr class="border-b border-[#5a2080] text-sm uppercase text-[#c4b5d6]"><th class="py-3">Nom</th><th class="py-3">Slug</th><th class="py-3">Produits lies</th><th class="py-3">Actions</th></tr></thead>
            <tbody>
            @forelse($categories as $category)
                <tr class="border-b border-[#2a0550]">
                    <td class="py-3">{{ $category->name }}</td>
                    <td class="py-3">{{ $category->slug }}</td>
                    <td class="py-3">{{ $category->products_count }}</td>
                    <td class="py-3">
                        <form method="POST" action="{{ route('admin.categories.update', $category->id) }}" class="inline-flex gap-2">@csrf @method('PUT')
                            <input name="name" value="{{ $category->name }}" class="w-36 rounded border border-[#5a2080] bg-[#120024] px-2 py-1 text-sm text-white" />
                            <input name="slug" value="{{ $category->slug }}" class="w-36 rounded border border-[#5a2080] bg-[#120024] px-2 py-1 text-sm text-white" />
                            <button class="rounded border border-[#5a2080] px-2 py-1 text-xs">Editer</button>
                        </form>
                        <form method="POST" action="{{ route('admin.categories.destroy', $category->id) }}" class="inline" onsubmit="return confirm('Supprimer cette categorie ?');">@csrf @method('DELETE')<button class="rounded border border-red-500/60 px-2 py-1 text-xs text-red-300">Supprimer</button></form>
                    </td>
                </tr>
            @empty
                <tr><td colspan="4" class="py-4 text-[#c4b5d6]">Aucune categorie.</td></tr>
            @endforelse
            </tbody>
        </table>
    </div>
</x-card>
<div class="mt-4">{{ $categories->links() }}</div>
@endsection
