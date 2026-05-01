@extends('layouts.admin')
@section('title','Produits')
@section('content')
<div class="mb-6 flex items-center justify-between">
    <div>
        <h1 class='text-3xl font-bold'>Products</h1>
        <p class="text-[#c4b5d6] mt-1">Create and manage your catalog.</p>
    </div>
    <button id="openM" class="rounded-xl bg-[#d304f4] px-4 py-2 font-semibold hover:bg-[#b003cc] transition-colors">+ Add product</button>
</div>

@if(session('success'))
    <div class="mb-4 rounded border border-emerald-500/40 bg-emerald-500/10 px-4 py-3 text-emerald-300">
        {{ session('success') }}
    </div>
@endif

@if(session('error'))
    <div class="mb-4 rounded border border-red-500/40 bg-red-500/10 px-4 py-3 text-red-300">
        {{ session('error') }}
    </div>
@endif

@if($errors->any())
    <div class="mb-4 rounded border border-red-500/40 bg-red-500/10 px-4 py-3 text-red-300">
        {{ $errors->first() }}
    </div>
@endif

<x-card>
    <div class="overflow-x-auto">
        <table class="w-full text-left">
            <thead>
                <tr class="border-b border-[#5a2080] text-sm uppercase tracking-wide text-[#c4b5d6]">
                    <th class="py-3">Image</th>
                    <th class="py-3">Name</th>
                    <th class="py-3">Category</th>
                    <th class="py-3">Price</th>
                    <th class="py-3">Stock</th>
                    <th class="py-3">Type</th>
                    <th class="py-3">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($products as $product)
                    <tr class="border-b border-[#2a0550] hover:bg-[#2a0550]/30 transition-colors">
                        <td class="py-3">
                            @if($product->image_path)
                                <img src="{{ asset('storage/'.$product->image_path) }}" alt="{{ $product->name }}" class="h-12 w-12 rounded object-cover border border-[#5a2080]">
                            @else
                                <div class="h-12 w-12 rounded border border-[#5a2080] bg-[#120024] flex items-center justify-center text-xs text-[#c4b5d6]">N/A</div>
                            @endif
                        </td>
                        <td class="py-3">{{ $product->name }}</td>
                        <td class="py-3">{{ $product->category?->name ?? '-' }}</td>
                        <td class="py-3">{{ number_format((int)$product->price_xof, 0, ',', ' ') }} XOF</td>
                        <td class="py-3">{{ $product->stock }}</td>
                        <td class="py-3">{{ $product->is_digital ? 'Digital' : 'Physical' }}</td>
                        <td class="py-3">
                            <div class="flex items-center gap-2">
                                <button
                                    type="button"
                                    class="rounded border border-[#5a2080] px-3 py-1 text-xs hover:border-[#d304f4] hover:text-[#d304f4] transition-colors edit-open"
                                    data-target="edit-modal-{{ $product->id }}"
                                >
                                    Edit
                                </button>
                                <form method="POST" action="{{ route('admin.products.destroy', $product->id) }}" onsubmit="return confirm('Delete this product?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="rounded border border-red-500/60 px-3 py-1 text-xs text-red-300 hover:bg-red-500/10 transition-colors">
                                        Delete
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="py-4 text-[#c4b5d6]">No products yet.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</x-card>

<div class="mt-4">
    {{ $products->links() }}
</div>

@foreach($products as $product)
    <div id="edit-modal-{{ $product->id }}" class="fixed inset-0 hidden items-center justify-center bg-black/70 p-4 edit-modal">
        <div class="w-full max-w-2xl rounded-2xl bg-[#1a0035] border border-[#5a2080] p-6">
            <h2 class="text-2xl font-bold mb-4">Edit product</h2>

            <form method="POST" action="{{ route('admin.products.update', $product->id) }}" enctype="multipart/form-data" class="grid gap-3 md:grid-cols-2">
                @csrf
                @method('PUT')
                <input name="name" value="{{ $product->name }}" placeholder="Product name" required class="rounded border border-[#5a2080] bg-[#120024] px-3 py-2 text-white md:col-span-2" />
                <textarea name="description" placeholder="Description" class="rounded border border-[#5a2080] bg-[#120024] px-3 py-2 text-white md:col-span-2">{{ $product->description }}</textarea>
                <input type="number" name="price_xof" value="{{ $product->price_xof }}" placeholder="Price XOF" min="0" required class="rounded border border-[#5a2080] bg-[#120024] px-3 py-2 text-white" />
                <input type="number" name="stock" value="{{ $product->stock }}" placeholder="Stock" min="0" required class="rounded border border-[#5a2080] bg-[#120024] px-3 py-2 text-white" />
                <select name="category_id" required class="rounded border border-[#5a2080] bg-[#120024] px-3 py-2 text-white">
                    @foreach($categories as $category)
                        <option value="{{ $category->id }}" @selected((int)$product->category_id === (int)$category->id)>
                            {{ $category->name }}
                        </option>
                    @endforeach
                </select>
                <input type="file" name="image" accept=".jpg,.jpeg,.png,.webp" class="rounded border border-[#5a2080] bg-[#120024] px-3 py-2 text-white" />
                <label class="flex items-center gap-2 text-sm text-[#c4b5d6] md:col-span-2">
                    <input type="checkbox" name="is_digital" value="1" @checked($product->is_digital) />
                    Digital product
                </label>

                <div class="mt-2 flex justify-end gap-2 md:col-span-2">
                    <button type="button" class="rounded-xl border border-[#5a2080] px-4 py-2 hover:border-[#d304f4] transition-colors edit-close" data-target="edit-modal-{{ $product->id }}">
                        Cancel
                    </button>
                    <button type="submit" class="rounded-xl bg-[#d304f4] px-4 py-2 font-semibold hover:bg-[#b003cc] transition-colors">
                        Save changes
                    </button>
                </div>
            </form>
        </div>
    </div>
@endforeach

<div id='modal' class='fixed inset-0 hidden items-center justify-center bg-black/70 p-4'>
    <div class='w-full max-w-2xl rounded-2xl bg-[#1a0035] border border-[#5a2080] p-6'>
        <h2 class='text-2xl font-bold mb-4'>Add product</h2>

        <form method="POST" action="{{ route('admin.products.store') }}" enctype="multipart/form-data" class="grid gap-3 md:grid-cols-2">
            @csrf
            <input name="name" value="{{ old('name') }}" placeholder="Product name" required class="rounded border border-[#5a2080] bg-[#120024] px-3 py-2 text-white md:col-span-2" />
            <textarea name="description" placeholder="Description" class="rounded border border-[#5a2080] bg-[#120024] px-3 py-2 text-white md:col-span-2">{{ old('description') }}</textarea>
            <input type="number" name="price_xof" value="{{ old('price_xof') }}" placeholder="Price XOF" min="0" required class="rounded border border-[#5a2080] bg-[#120024] px-3 py-2 text-white" />
            <input type="number" name="stock" value="{{ old('stock', 0) }}" placeholder="Stock" min="0" required class="rounded border border-[#5a2080] bg-[#120024] px-3 py-2 text-white" />
            <select name="category_id" required class="rounded border border-[#5a2080] bg-[#120024] px-3 py-2 text-white">
                <option value="">Select category</option>
                @foreach($categories as $category)
                    <option value="{{ $category->id }}" @selected((string)old('category_id') === (string)$category->id)>
                        {{ $category->name }}
                    </option>
                @endforeach
            </select>
            <input type="file" name="image" accept=".jpg,.jpeg,.png,.webp" class="rounded border border-[#5a2080] bg-[#120024] px-3 py-2 text-white" />
            <label class="flex items-center gap-2 text-sm text-[#c4b5d6] md:col-span-2">
                <input type="checkbox" name="is_digital" value="1" @checked(old('is_digital')) />
                Digital product
            </label>

            <div class='mt-2 flex justify-end gap-2 md:col-span-2'>
                <button type="button" id='closeM' class="rounded-xl border border-[#5a2080] px-4 py-2 hover:border-[#d304f4] transition-colors">Cancel</button>
                <button type="submit" class="rounded-xl bg-[#d304f4] px-4 py-2 font-semibold hover:bg-[#b003cc] transition-colors">Save product</button>
            </div>
        </form>
    </div>
</div>

<script>
const m = document.getElementById('modal');
const openM = document.getElementById('openM');
const closeM = document.getElementById('closeM');

openM?.addEventListener('click', () => {
    m.classList.remove('hidden');
    m.classList.add('flex');
});

closeM?.addEventListener('click', () => {
    m.classList.add('hidden');
    m.classList.remove('flex');
});

document.querySelectorAll('.edit-open').forEach((button) => {
    button.addEventListener('click', () => {
        const id = button.getAttribute('data-target');
        const modal = document.getElementById(id);
        modal?.classList.remove('hidden');
        modal?.classList.add('flex');
    });
});

document.querySelectorAll('.edit-close').forEach((button) => {
    button.addEventListener('click', () => {
        const id = button.getAttribute('data-target');
        const modal = document.getElementById(id);
        modal?.classList.add('hidden');
        modal?.classList.remove('flex');
    });
});
</script>
@endsection