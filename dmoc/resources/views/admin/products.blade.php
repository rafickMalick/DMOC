@extends('layouts.admin')
@section('title','Produits')
@section('content')
<div class="mb-6 flex flex-wrap items-center justify-between gap-3">
    <div>
        <h1 class='text-3xl font-bold'>Produits</h1>
        <p class="text-[#c4b5d6] mt-1">Gestion du catalogue avec vraies donnees.</p>
    </div>
    <div class="flex gap-2">
        <a href="{{ route('admin.categories') }}" class="rounded-xl border border-[#5a2080] px-4 py-2 hover:border-[#d304f4]">Categories</a>
        <button id="openM" class="rounded-xl bg-[#d304f4] px-4 py-2 font-semibold hover:bg-[#b003cc] transition-colors">Ajouter un produit</button>
    </div>
</div>

@if(session('success'))<div class="mb-4 rounded border border-emerald-500/40 bg-emerald-500/10 px-4 py-3 text-emerald-300">{{ session('success') }}</div>@endif
@if(session('error'))<div class="mb-4 rounded border border-red-500/40 bg-red-500/10 px-4 py-3 text-red-300">{{ session('error') }}</div>@endif
@if($errors->any())<div class="mb-4 rounded border border-red-500/40 bg-red-500/10 px-4 py-3 text-red-300">{{ $errors->first() }}</div>@endif

<x-card class="mb-6">
    <form method="GET" class="grid gap-3 md:grid-cols-4">
        <input type="text" name="q" value="{{ $q }}" placeholder="Recherche nom ou slug" class="rounded border border-[#5a2080] bg-[#120024] px-3 py-2 text-white" />
        <select name="category_id" class="rounded border border-[#5a2080] bg-[#120024] px-3 py-2 text-white">
            <option value="">Toutes categories</option>
            @foreach($categories as $category)
                <option value="{{ $category->id }}" @selected((string)$categoryId === (string)$category->id)>{{ $category->name }}</option>
            @endforeach
        </select>
        @if($supportsActiveStatus)
            <select name="active" class="rounded border border-[#5a2080] bg-[#120024] px-3 py-2 text-white">
                <option value="">Tous statuts</option>
                <option value="1" @selected((string)$active === '1')>Actif</option>
                <option value="0" @selected((string)$active === '0')>Inactif</option>
            </select>
        @else
            <div class="rounded border border-amber-500/40 bg-amber-500/10 px-3 py-2 text-xs text-amber-300">Statut actif/inactif indisponible: migration manquante.</div>
        @endif
        <button class="rounded bg-[#d304f4] px-4 py-2 font-semibold">Filtrer</button>
    </form>
</x-card>

<x-card>
    <div class="overflow-x-auto">
        <table class="w-full text-left">
            <thead>
                <tr class="border-b border-[#5a2080] text-sm uppercase tracking-wide text-[#c4b5d6]">
                    <th class="py-3">Image</th><th class="py-3">Nom</th><th class="py-3">Categorie</th><th class="py-3">Prix</th><th class="py-3">Stock</th><th class="py-3">Statut</th><th class="py-3">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($products as $product)
                    <tr class="border-b border-[#2a0550]">
                        <td class="py-3">@if($product->image_path)<img src="{{ asset('storage/'.$product->image_path) }}" class="h-12 w-12 rounded object-cover">@else<div class="h-12 w-12 rounded border border-[#5a2080]"></div>@endif</td>
                        <td class="py-3">{{ $product->name }}<div class="text-xs text-[#c4b5d6]">{{ $product->slug }}</div></td>
                        <td class="py-3">{{ $product->category?->name ?? '-' }}</td>
                        <td class="py-3">{{ number_format((int)$product->price_xof, 0, ',', ' ') }} XOF</td>
                        <td class="py-3">{{ $product->stock }}</td>
                        <td class="py-3">
                            @if($supportsActiveStatus)
                                <span class="rounded-full border px-3 py-1 text-xs {{ $product->is_active ? 'border-emerald-500/40 text-emerald-300' : 'border-red-500/40 text-red-300' }}">{{ $product->is_active ? 'Actif' : 'Inactif' }}</span>
                            @else
                                <span class="text-xs text-[#c4b5d6]">N/A</span>
                            @endif
                        </td>
                        <td class="py-3 flex gap-2">
                            <button type="button" class="rounded border border-[#5a2080] px-3 py-1 text-xs edit-open" data-target="edit-modal-{{ $product->id }}">Editer</button>
                            <form method="POST" action="{{ route('admin.products.destroy', $product->id) }}" onsubmit="return confirm('Confirmer la suppression ?');">@csrf @method('DELETE')<button class="rounded border border-red-500/60 px-3 py-1 text-xs text-red-300">Supprimer</button></form>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="7" class="py-4 text-[#c4b5d6]">Aucun produit trouve.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</x-card>
<div class="mt-4">{{ $products->links() }}</div>

@foreach($products as $product)
<div id="edit-modal-{{ $product->id }}" class="fixed inset-0 hidden items-center justify-center bg-black/70 p-4">
    <div class="w-full max-w-2xl rounded-2xl bg-[#1a0035] border border-[#5a2080] p-6">
        <form method="POST" action="{{ route('admin.products.update', $product->id) }}" enctype="multipart/form-data" class="grid gap-3 md:grid-cols-2">@csrf @method('PUT')
            <label class="md:col-span-2 text-sm text-[#c4b5d6]">Nom du produit *</label>
            <input name="name" value="{{ $product->name }}" placeholder="Ex: Powerbank 10000mAh" required class="rounded border border-[#5a2080] bg-[#120024] px-3 py-2 text-white md:col-span-2" />
            <p class="md:col-span-2 text-xs text-[#c4b5d6]">Slug genere automatiquement a partir du nom.</p>
            <label class="md:col-span-2 text-sm text-[#c4b5d6]">Description *</label>
            <textarea name="description" placeholder="Description commerciale du produit" class="rounded border border-[#5a2080] bg-[#120024] px-3 py-2 text-white md:col-span-2">{{ $product->description }}</textarea>
            <label class="text-sm text-[#c4b5d6]">Prix (XOF) *</label>
            <label class="text-sm text-[#c4b5d6]">Stock *</label>
            <input type="number" name="price_xof" value="{{ $product->price_xof }}" min="0" required class="rounded border border-[#5a2080] bg-[#120024] px-3 py-2 text-white" />
            <input type="number" name="stock" value="{{ $product->stock }}" min="0" required class="rounded border border-[#5a2080] bg-[#120024] px-3 py-2 text-white" />
            <label class="text-sm text-[#c4b5d6]">Categorie *</label>
            <label class="text-sm text-[#c4b5d6]">Image (jpg/png/webp, max 2MB)</label>
            <select name="category_id" required class="rounded border border-[#5a2080] bg-[#120024] px-3 py-2 text-white">@foreach($categories as $category)<option value="{{ $category->id }}" @selected((int)$product->category_id === (int)$category->id)>{{ $category->name }}</option>@endforeach</select>
            <input type="file" name="image" accept=".jpg,.jpeg,.png,.webp" class="rounded border border-[#5a2080] bg-[#120024] px-3 py-2 text-white" />
            @if($supportsActiveStatus)<label class="text-sm md:col-span-2"><input type="checkbox" name="is_active" value="1" @checked($product->is_active)> Produit actif</label>@endif
            <label class="text-sm md:col-span-2"><input type="checkbox" name="is_digital" value="1" @checked($product->is_digital)> Produit digital</label>
            <div class="md:col-span-2 flex justify-end gap-2"><button type="button" class="rounded border border-[#5a2080] px-3 py-2 edit-close" data-target="edit-modal-{{ $product->id }}">Annuler</button><button class="rounded bg-[#d304f4] px-4 py-2">Enregistrer</button></div>
        </form>
    </div>
</div>
@endforeach

<div id='modal' class='fixed inset-0 hidden items-center justify-center bg-black/70 p-4'><div class='w-full max-w-2xl rounded-2xl bg-[#1a0035] border border-[#5a2080] p-6'>
    <form method="POST" action="{{ route('admin.products.store') }}" enctype="multipart/form-data" class="grid gap-3 md:grid-cols-2">@csrf
        <label class="md:col-span-2 text-sm text-[#c4b5d6]">Nom du produit *</label>
        <input name="name" value="{{ old('name') }}" placeholder="Ex: Powerbank 10000mAh" required class="rounded border border-[#5a2080] bg-[#120024] px-3 py-2 text-white md:col-span-2" />
        <p class="md:col-span-2 text-xs text-[#c4b5d6]">Slug genere automatiquement a partir du nom.</p>
        <label class="md:col-span-2 text-sm text-[#c4b5d6]">Description *</label>
        <textarea name="description" placeholder="Description commerciale du produit" class="rounded border border-[#5a2080] bg-[#120024] px-3 py-2 text-white md:col-span-2">{{ old('description') }}</textarea>
        <label class="text-sm text-[#c4b5d6]">Prix (XOF) *</label>
        <label class="text-sm text-[#c4b5d6]">Stock *</label>
        <input type="number" name="price_xof" value="{{ old('price_xof') }}" min="0" required class="rounded border border-[#5a2080] bg-[#120024] px-3 py-2 text-white" />
        <input type="number" name="stock" value="{{ old('stock', 0) }}" min="0" required class="rounded border border-[#5a2080] bg-[#120024] px-3 py-2 text-white" />
        <label class="text-sm text-[#c4b5d6]">Categorie *</label>
        <label class="text-sm text-[#c4b5d6]">Image (jpg/png/webp, max 2MB)</label>
        <select name="category_id" required class="rounded border border-[#5a2080] bg-[#120024] px-3 py-2 text-white"><option value="">Selectionner</option>@foreach($categories as $category)<option value="{{ $category->id }}" @selected((string)old('category_id') === (string)$category->id)>{{ $category->name }}</option>@endforeach</select>
        <input type="file" name="image" accept=".jpg,.jpeg,.png,.webp" class="rounded border border-[#5a2080] bg-[#120024] px-3 py-2 text-white" />
        @if($supportsActiveStatus)<label class="text-sm md:col-span-2"><input type="checkbox" name="is_active" value="1" checked> Produit actif</label>@endif
        <label class="text-sm md:col-span-2"><input type="checkbox" name="is_digital" value="1" @checked(old('is_digital'))> Produit digital</label>
        <div class='md:col-span-2 flex justify-end gap-2'><button type="button" id='closeM' class="rounded border border-[#5a2080] px-4 py-2">Annuler</button><button type="submit" class="rounded bg-[#d304f4] px-4 py-2 font-semibold">Ajouter</button></div>
    </form>
</div></div>

<script>
const m=document.getElementById('modal');document.getElementById('openM')?.addEventListener('click',()=>{m.classList.remove('hidden');m.classList.add('flex');});document.getElementById('closeM')?.addEventListener('click',()=>{m.classList.add('hidden');m.classList.remove('flex');});document.querySelectorAll('.edit-open').forEach((b)=>b.addEventListener('click',()=>{const modal=document.getElementById(b.dataset.target);modal?.classList.remove('hidden');modal?.classList.add('flex');}));document.querySelectorAll('.edit-close').forEach((b)=>b.addEventListener('click',()=>{const modal=document.getElementById(b.dataset.target);modal?.classList.add('hidden');modal?.classList.remove('flex');}));
</script>
@endsection