@extends('layouts.client')
@section('title', $product->name)
@section('content')
<div class="mb-8">
    <p class='text-[#c4b5d6] text-sm'><a href='{{ route('client.home') }}' class="hover:text-white transition-colors">Accueil</a> <span class="mx-2">/</span> <a href='{{ route('client.catalog') }}' class="hover:text-white transition-colors">Catalogue</a> <span class="mx-2">/</span> <span class="text-[#d304f4] font-semibold">{{ $product->name }}</span></p>
</div>

<div class='grid lg:grid-cols-[1fr_400px] xl:grid-cols-[1.5fr_1fr] gap-10 mb-12'>
  <!-- Left Column - Image Gallery -->
  <div class="flex flex-col-reverse md:flex-row gap-4">
      <!-- Thumbnails -->
      <div class="flex md:flex-col gap-3 overflow-x-auto md:w-24 shrink-0">
          <div class="w-20 h-20 md:w-24 md:h-24 rounded-xl border-2 border-[#d304f4] bg-[#2a0550] overflow-hidden cursor-pointer relative group">
              <div class="absolute inset-0 bg-[#d304f4]/20 group-hover:bg-transparent transition-colors z-10"></div>
              <div class="w-full h-full text-3xl flex items-center justify-center">🎧</div>
          </div>
          <div class="w-20 h-20 md:w-24 md:h-24 rounded-xl border border-[#5a2080]/50 bg-[#2a0550] overflow-hidden cursor-pointer opacity-70 hover:opacity-100 transition-opacity">
              <div class="w-full h-full text-3xl flex items-center justify-center">📦</div>
          </div>
          <div class="w-20 h-20 md:w-24 md:h-24 rounded-xl border border-[#5a2080]/50 bg-[#2a0550] overflow-hidden cursor-pointer opacity-70 hover:opacity-100 transition-opacity">
              <div class="w-full h-full text-3xl flex items-center justify-center">🔌</div>
          </div>
      </div>
      
      <!-- Main Image -->
      <div class='flex-1 h-[400px] md:h-[600px] rounded-3xl border border-[#5a2080]/50 bg-gradient-to-br from-[#2a0550] to-[#120024] p-8 shadow-2xl relative overflow-hidden flex items-center justify-center group'>
          <div class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-64 h-64 bg-[#d304f4] rounded-full blur-[100px] opacity-20 group-hover:opacity-40 transition-opacity duration-500"></div>
          <div class="text-[12rem] transform group-hover:scale-110 transition-transform duration-700 ease-out z-10 drop-shadow-[0_20px_30px_rgba(211,4,244,0.3)]">🛍️</div>
          <button class="absolute top-4 right-4 w-12 h-12 rounded-full bg-[#1a0035]/80 backdrop-blur-md border border-[#5a2080] flex items-center justify-center text-[#c4b5d6] hover:text-[#d304f4] hover:border-[#d304f4] transition-all z-20">
              <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 8V4m0 0h4M4 4l5 5m11-1V4m0 0h-4m4 0l-5 5M4 16v4m0 0h4m-4 0l5-5m11 5l-5-5m5 5v-4m0 4h-4" /></svg>
          </button>
      </div>
  </div>

  <!-- Right Column - Product info -->
  <div class="flex flex-col">
      <div class="mb-6">
          <div class="flex items-center gap-3 mb-3">
              <span class="bg-gradient-to-r from-[#d304f4] to-[#a855f7] text-white text-[10px] font-bold px-3 py-1 rounded-full uppercase tracking-wider">{{ $product->category?->name ?? 'Produit' }}</span>
              <span class="flex items-center text-yellow-400 text-sm"><svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 fill-current" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" /></svg> {{ number_format((float) $product->rating, 1) }} ({{ $product->reviews->count() }} avis)</span>
          </div>
          <h1 class='text-4xl lg:text-5xl font-black mb-4 leading-tight'>{{ $product->name }}</h1>
          <p class='text-[#c4b5d6] text-lg leading-relaxed'>{{ $product->description }}</p>
      </div>

      <div class="py-6 border-y border-[#5a2080]/30 mb-6">
          <div class="flex items-end gap-4 mb-2">
              <span class='text-5xl text-[#d304f4] font-black'>{{ number_format($product->price_xof, 0, ',', ' ') }} FCFA</span>
          </div>
          <p class="text-green-400 text-sm flex items-center gap-1 font-medium"><svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" /></svg> Stock disponible: {{ $product->stock }}.</p>
      </div>

      <!-- Variants -->
      <div class="mb-8">
          <h3 class="font-semibold mb-3">Variantes</h3>
          @if($product->variants->isNotEmpty())
              <div class="space-y-2">
                  @foreach($product->variants as $variant)
                      <div class="rounded-xl border border-[#5a2080]/40 bg-[#120024] px-3 py-2 text-sm">
                          {{ $variant->name }}: <span class="text-[#d304f4]">{{ $variant->value }}</span>
                          @if($variant->price_delta_xof > 0)
                              (+{{ number_format($variant->price_delta_xof, 0, ',', ' ') }} XOF)
                          @endif
                      </div>
                  @endforeach
              </div>
          @else
              <p class="text-sm text-[#c4b5d6]">Aucune variante specifique pour ce produit.</p>
          @endif
      </div>

      <!-- Actions -->
      <div class='flex flex-col sm:flex-row gap-4 mt-auto'>
          <form method="POST" action="{{ route('client.cart.add') }}" class="flex flex-1 flex-col sm:flex-row gap-4">
              @csrf
              <input type="hidden" name="product_id" value="{{ $product->id }}">
              <div class="flex items-center border border-[#5a2080] rounded-full h-14 bg-[#1a0035] w-fit px-3">
                  <input type="number" name="quantity" min="1" max="20" value="1" class="w-16 bg-transparent text-center font-bold outline-none text-white border-none py-0">
              </div>
              <button class="flex-1 h-14 rounded-xl bg-[#d304f4] text-lg font-semibold shadow-[0_0_20px_rgba(211,4,244,0.3)] hover:brightness-110">
                  Ajouter au panier
              </button>
          </form>
          <button class="w-14 h-14 rounded-full border-2 border-[#5a2080] flex items-center justify-center text-[#c4b5d6] hover:text-[#d304f4] hover:border-[#d304f4] hover:bg-[#d304f4]/10 transition-all shrink-0" onclick="window.location.href='{{ route('client.wishlist') }}'">
              <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" /></svg>
          </button>
      </div>
      
      <!-- Trust features -->
      <div class="grid grid-cols-2 gap-4 mt-8 pt-6 border-t border-[#5a2080]/30">
          <div class="flex items-center gap-3">
              <div class="w-10 h-10 rounded-full bg-[#1a0035] flex items-center justify-center border border-[#5a2080] text-[#d304f4]"><svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" /></svg></div>
              <span class="text-sm font-medium">Paiement 100% Sécurisé</span>
          </div>
          <div class="flex items-center gap-3">
              <div class="w-10 h-10 rounded-full bg-[#1a0035] flex items-center justify-center border border-[#5a2080] text-[#d304f4]"><svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" /></svg></div>
              <span class="text-sm font-medium">Retours Gratuits sous 30 jours</span>
          </div>
      </div>
  </div>
</div>

<!-- Sticky Tabs Section -->
<div class="mb-16">
    <div class="flex border-b border-[#5a2080] mb-8 overflow-x-auto hide-scrollbar">
        <button class='tab text-lg font-bold px-8 py-4 border-b-2 border-[#d304f4] text-white transition-colors whitespace-nowrap' data-t='d'>Description</button>
        <button class='tab text-lg font-bold px-8 py-4 border-b-2 border-transparent text-[#c4b5d6] hover:text-white transition-colors whitespace-nowrap' data-t='c'>Caractéristiques</button>
        <button class='tab text-lg font-bold px-8 py-4 border-b-2 border-transparent text-[#c4b5d6] hover:text-white transition-colors whitespace-nowrap' data-t='a'>Avis Clients (128)</button>
    </div>
    
    <div class="bg-[#1a0035] border border-[#5a2080]/50 rounded-3xl p-8 md:p-12 shadow-xl">
        <div id='d' class='pane text-lg text-[#c4b5d6] leading-relaxed max-w-4xl'>
            <h3 class="text-2xl font-bold text-white mb-4">Description</h3>
            <p class="mb-6">{{ $product->description }}</p>
            <ul class="list-disc pl-6 space-y-2">
                <li>Stock actuel: {{ $product->stock }}</li>
                <li>Categorie: {{ $product->category?->name ?? 'N/A' }}</li>
                <li>Produit digital: {{ $product->is_digital ? 'Oui' : 'Non' }}</li>
            </ul>
        </div>
        <div id='c' class='pane hidden text-[#c4b5d6]'>
            <div class="max-w-2xl">
                <div class="flex justify-between py-3 border-b border-[#5a2080]/30"><span class="font-medium text-white">Prix</span><span>{{ number_format($product->price_xof, 0, ',', ' ') }} XOF</span></div>
                <div class="flex justify-between py-3 border-b border-[#5a2080]/30"><span class="font-medium text-white">Slug</span><span>{{ $product->slug }}</span></div>
                <div class="flex justify-between py-3 border-b border-[#5a2080]/30"><span class="font-medium text-white">Note moyenne</span><span>{{ number_format((float) $product->rating, 1) }}/5</span></div>
            </div>
        </div>
        <div id='a' class='pane hidden text-[#c4b5d6]'>
            @forelse($product->reviews as $review)
                <div class="mb-4 rounded-xl border border-[#5a2080]/30 bg-[#120024] p-4">
                    <div class="mb-1 text-sm font-semibold text-white">{{ $review->user?->name ?? 'Client' }} - {{ $review->rating }}/5</div>
                    <p class="text-sm">{{ $review->comment ?: 'Aucun commentaire.' }}</p>
                </div>
            @empty
                <p>Aucun avis pour le moment.</p>
            @endforelse
        </div>
    </div>
</div>

<section class='mb-20'>
    <h2 class='text-3xl font-black mb-8 border-l-4 border-[#d304f4] pl-4'>Vous aimerez aussi</h2>
    <div class='grid grid-cols-2 lg:grid-cols-4 gap-6'>
        @forelse($relatedProducts as $item)
        <div class="group rounded-2xl bg-[#1a0035] border border-[#5a2080]/50 overflow-hidden hover:border-[#d304f4] transition-all duration-300 hover:shadow-lg hover:shadow-[#d304f4]/10">
            <div class="relative h-64 bg-gradient-to-br from-[#2a0550] to-[#1a0035] flex items-center justify-center overflow-hidden cursor-pointer" onclick="window.location.href='{{ route('client.product', ['slug' => $item->slug]) }}'">
                <div class="text-6xl group-hover:scale-110 transition-transform duration-500">🛍️</div>
            </div>
            <div class="p-5">
                <h3 class="font-bold text-lg mb-1 group-hover:text-[#d304f4] transition-colors"><a href="{{ route('client.product', ['slug' => $item->slug]) }}">{{ $item->name }}</a></h3>
                <p class="text-[#c4b5d6] text-sm mb-3">{{ $item->category?->name ?? 'Produit' }}</p>
                <div class="flex items-center gap-2">
                    <span class="text-[#d304f4] font-black text-xl">{{ number_format($item->price_xof, 0, ',', ' ') }} FCFA</span>
                </div>
            </div>
        </div>
        @empty
            <x-card><p class="text-[#c4b5d6]">Pas encore de produits similaires.</p></x-card>
        @endforelse
    </div>
</section>

<script>
document.querySelectorAll('.tab').forEach(b => b.addEventListener('click', () => {
    document.querySelectorAll('.tab').forEach(x => {
        x.classList.remove('border-[#d304f4]', 'text-white');
        x.classList.add('border-transparent', 'text-[#c4b5d6]');
    });
    b.classList.remove('border-transparent', 'text-[#c4b5d6]');
    b.classList.add('border-[#d304f4]', 'text-white');
    
    document.querySelectorAll('.pane').forEach(p => p.classList.add('hidden'));
    document.getElementById(b.dataset.t)?.classList.remove('hidden');
}));
</script>
@endsection