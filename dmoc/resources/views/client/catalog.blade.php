@extends('layouts.client')
@section('title', 'Catalogue')
@section('content')
<x-page-header title="Catalogue" breadcrumb="DMC / Client / Catalogue">
    <p class="mt-2 text-lg text-[#c4b5d6]">{{ $products->total() }} produit(s) trouves.</p>
</x-page-header>

<form method="GET" class="grid gap-8 lg:grid-cols-[280px_1fr]">
    <aside class="h-fit rounded-3xl border border-[#5a2080]/50 bg-[#120024] p-6 shadow-xl lg:sticky lg:top-28">
        <h3 class="mb-5 text-xl font-bold text-white">Filtres</h3>

        <div class="mb-5">
            <label class="mb-2 block text-xs uppercase tracking-wider text-[#c4b5d6]">Recherche</label>
            <div class="relative">
                <input id="search-input" type="text" name="search" value="{{ $search }}" placeholder="Nom ou mot-cle..."
                       class="w-full rounded-xl border border-[#5a2080]/50 bg-[#1a0035] px-3 py-2 text-white outline-none focus:border-[#d304f4]">
                <div id="search-suggestions" class="absolute z-20 mt-1 hidden w-full rounded-xl border border-[#5a2080]/50 bg-[#120024] p-2"></div>
            </div>
        </div>

        <div class="mb-5">
            <label class="mb-2 block text-xs uppercase tracking-wider text-[#c4b5d6]">Categories</label>
            <div class="space-y-2">
                @foreach($categories as $category)
                    <label class="flex items-center gap-2">
                        <input type="checkbox" name="category[]" value="{{ $category->id }}"
                               @checked(in_array($category->id, $selectedCategories, true))
                               class="rounded border-[#5a2080] bg-[#1a0035] text-[#d304f4]">
                        <span class="text-sm">{{ $category->name }} ({{ $category->products_count }})</span>
                    </label>
                @endforeach
            </div>
        </div>

        <div class="mb-5 grid grid-cols-2 gap-2">
            <div>
                <label class="mb-1 block text-xs uppercase tracking-wider text-[#c4b5d6]">Prix min</label>
                <input type="number" name="min_price" value="{{ request('min_price', 0) }}" min="0"
                       class="w-full rounded-xl border border-[#5a2080]/50 bg-[#1a0035] px-3 py-2 text-white">
            </div>
            <div>
                <label class="mb-1 block text-xs uppercase tracking-wider text-[#c4b5d6]">Prix max</label>
                <input type="number" name="max_price" value="{{ request('max_price', $maxPrice) }}" min="0"
                       class="w-full rounded-xl border border-[#5a2080]/50 bg-[#1a0035] px-3 py-2 text-white">
            </div>
        </div>

        <div class="mb-5">
            <label class="mb-1 block text-xs uppercase tracking-wider text-[#c4b5d6]">Tri</label>
            <select name="sort" class="w-full rounded-xl border border-[#5a2080]/50 bg-[#1a0035] px-3 py-2 text-white">
                <option value="relevance" @selected($sort === 'relevance')>Pertinence</option>
                <option value="newest" @selected($sort === 'newest')>Nouveautes</option>
                <option value="price_asc" @selected($sort === 'price_asc')>Prix croissant</option>
                <option value="price_desc" @selected($sort === 'price_desc')>Prix decroissant</option>
                <option value="rating" @selected($sort === 'rating')>Mieux notes</option>
            </select>
        </div>

        <div class="flex gap-2">
            <button class="flex-1 rounded-xl bg-[#d304f4] px-4 py-2 font-semibold">Appliquer</button>
            <a href="{{ route('client.catalog') }}" class="rounded-xl border border-[#5a2080] px-4 py-2 text-sm">Reset</a>
        </div>
    </aside>

    <section>
        <div class="mb-4 flex items-center justify-between">
            <p class="text-sm text-[#c4b5d6]">Pagination server-side + chargement leger.</p>
            <div class="flex items-center gap-2">
                <button type="button" id="grid-view" class="rounded-lg bg-[#3f047b] p-2">▦</button>
                <button type="button" id="list-view" class="rounded-lg border border-[#5a2080] p-2">☰</button>
            </div>
        </div>

        <div id="products-wrap" class="grid grid-cols-1 gap-6 md:grid-cols-2 xl:grid-cols-3">
            @forelse($products as $product)
                <x-card class="group flex flex-col p-0">
                    <div class="flex h-44 items-center justify-center rounded-t-2xl bg-[#2a0550] text-5xl">🛍️</div>
                    <div class="flex flex-1 flex-col p-4">
                        <x-badge>{{ $product->category?->name ?? 'Sans categorie' }}</x-badge>
                        <h3 class="mt-2 text-lg font-bold">
                            <a href="{{ route('client.product', ['slug' => $product->slug]) }}" class="hover:text-[#d304f4]">
                                {{ $product->name }}
                            </a>
                        </h3>
                        <p class="mt-1 line-clamp-2 text-sm text-[#c4b5d6]">{{ $product->description }}</p>
                        <div class="mt-auto flex items-center justify-between pt-4">
                            <span class="text-xl font-black text-[#d304f4]">{{ number_format($product->price_xof, 0, ',', ' ') }} XOF</span>
                            <form method="POST" action="{{ route('client.cart.add') }}">
                                @csrf
                                <input type="hidden" name="product_id" value="{{ $product->id }}">
                                <button class="rounded-xl bg-[#d304f4] px-3 py-2 text-sm font-semibold">Ajouter</button>
                            </form>
                        </div>
                    </div>
                </x-card>
            @empty
                <x-card>
                    <p class="text-[#c4b5d6]">Aucun produit ne correspond aux filtres actuels.</p>
                </x-card>
            @endforelse
        </div>

        <div class="mt-8">{{ $products->links() }}</div>
    </section>
</form>

<script>
    const wrap = document.getElementById('products-wrap');
    document.getElementById('grid-view')?.addEventListener('click', () => {
        wrap.className = 'grid grid-cols-1 gap-6 md:grid-cols-2 xl:grid-cols-3';
    });
    document.getElementById('list-view')?.addEventListener('click', () => {
        wrap.className = 'grid grid-cols-1 gap-6';
    });

    const input = document.getElementById('search-input');
    const suggestions = document.getElementById('search-suggestions');
    let timer = null;

    input?.addEventListener('input', () => {
        clearTimeout(timer);
        const value = input.value.trim();
        if (value.length < 2) {
            suggestions.classList.add('hidden');
            return;
        }

        timer = setTimeout(async () => {
            const response = await fetch(`{{ route('client.search.suggestions') }}?q=${encodeURIComponent(value)}`);
            const data = await response.json();
            suggestions.innerHTML = (data.data || []).map((item) =>
                `<a class="block rounded-lg px-2 py-2 text-sm hover:bg-[#1a0035]" href="/dmoc/product/${item.slug}">${item.name}</a>`
            ).join('');
            suggestions.classList.remove('hidden');
        }, 250);
    });
</script>
@endsection