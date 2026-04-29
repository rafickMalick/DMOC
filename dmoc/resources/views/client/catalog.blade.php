@extends('layouts.client')
@section('title','Catalogue')
@section('content')
<x-page-header title='Catalogue' breadcrumb='DMOC / Client / Catalogue'>
    <p class='text-[#c4b5d6] text-lg mt-2'>124 produits trouvés avec nos filtres exclusifs.</p>
</x-page-header>

<div class='grid lg:grid-cols-[280px_1fr] gap-8'>
  {{-- SECTION FILTRES --}}
  <aside class='rounded-3xl border border-[#5a2080]/50 bg-[#120024] p-6 shadow-xl sticky top-28 h-fit backdrop-blur-md hidden lg:block'>
      <h3 class="text-xl font-bold mb-6 text-white border-b border-[#5a2080]/50 pb-4">Filtres</h3>
      
      <div class="mb-6">
          <h4 class="font-semibold text-[#c4b5d6] mb-3 uppercase text-sm tracking-wider">Catégories</h4>
          <div class="space-y-3">
              <label class="flex items-center gap-3 cursor-pointer group">
                  <input type="checkbox" class="w-5 h-5 rounded border-[#5a2080] bg-[#1a0035] text-[#d304f4] focus:ring-[#d304f4] focus:ring-offset-[#120024]">
                  <span class="text-white group-hover:text-[#d304f4] transition-colors">Electronique (42)</span>
              </label>
              <label class="flex items-center gap-3 cursor-pointer group">
                  <input type="checkbox" class="w-5 h-5 rounded border-[#5a2080] bg-[#1a0035] text-[#d304f4] focus:ring-[#d304f4] focus:ring-offset-[#120024]">
                  <span class="text-white group-hover:text-[#d304f4] transition-colors">Mode (56)</span>
              </label>
              <label class="flex items-center gap-3 cursor-pointer group">
                  <input type="checkbox" class="w-5 h-5 rounded border-[#5a2080] bg-[#1a0035] text-[#d304f4] focus:ring-[#d304f4] focus:ring-offset-[#120024]" checked>
                  <span class="text-[#d304f4]">Premium (12)</span>
              </label>
          </div>
      </div>
      
      <div class="mb-6">
          <h4 class="font-semibold text-[#c4b5d6] mb-3 uppercase text-sm tracking-wider">Prix</h4>
          <input type="range" class="w-full h-2 bg-[#1a0035] rounded-lg appearance-none cursor-pointer accent-[#d304f4]">
          <div class="flex justify-between mt-2 text-sm text-[#c4b5d6]">
              <span>0 FCFA</span>
              <span>150k FCFA</span>
          </div>
      </div>
      
      <x-btn-primary class="w-full">Appliquer les filtres</x-btn-primary>
  </aside>

  <section>
    {{-- SECTION HEADER --}}
    <div class='mb-6 flex flex-col md:flex-row gap-4 justify-between bg-[#120024] p-4 rounded-2xl border border-[#5a2080]/30 shadow-lg'>
        <div class="relative w-full md:w-96">
            <svg xmlns="http://www.w3.org/2000/svg" class="absolute left-3 top-1/2 -translate-y-1/2 h-5 w-5 text-[#c4b5d6]" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" /></svg>
            <input type="text" placeholder='Rechercher un produit...' class="w-full pl-10 pr-4 py-3 rounded-xl bg-[#1a0035] border border-[#5a2080]/50 text-white focus:border-[#d304f4] focus:ring-1 focus:ring-[#d304f4] outline-none transition-all placeholder-[#c4b5d6]/50">
        </div>
        <div class="flex gap-4">
            <select class='rounded-xl bg-[#1a0035] border border-[#5a2080]/50 px-4 py-3 text-[#c4b5d6] focus:border-[#d304f4] outline-none appearance-none pr-10 relative'>
                <option>Pertinence</option>
                <option>Prix croissant</option>
                <option>Prix décroissant</option>
                <option>Nouveautés</option>
            </select>
            <div class='flex gap-1 bg-[#1a0035] p-1 rounded-xl border border-[#5a2080]/30'>
                <button id='grid' class='rounded-lg bg-gradient-to-r from-[#d304f4] to-[#3f047b] text-white w-10 h-10 flex items-center justify-center shadow-lg transition-transform hover:scale-105'>
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z" /></svg>
                </button>
                <button id='list' class='rounded-lg text-[#c4b5d6] hover:bg-[#3f047b] w-10 h-10 flex items-center justify-center transition-colors'>
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" /></svg>
                </button>
            </div>
        </div>
    </div>

    {{-- SECTION GRILLE --}}
    <div id='wrap' class='grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6'>
      @for($i = 1; $i <= 6; $i++)
      <x-card class="group flex flex-col p-0">
          <div class="relative h-64 bg-gradient-to-br from-[#2a0550] to-[#1a0035] flex items-center justify-center overflow-hidden">
              <div class="text-6xl group-hover:scale-110 transition-transform duration-500 z-10 w-32 h-32 bg-[#3f047b]/80 rounded-full flex items-center justify-center border border-[#d304f4]/30 shadow-[0_0_30px_rgba(211,4,244,0.3)]">🛍️</div>
              <div class="absolute inset-0 bg-[#d304f4]/5 opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
              
              @if($i == 1)
              <span class="absolute top-4 left-4 bg-gradient-to-r from-[#d304f4] to-[#a855f7] text-white text-[10px] font-bold px-3 py-1 rounded-full uppercase tracking-wider z-20">PROMO</span>
              @endif
              
              <div class="absolute inset-x-0 bottom-0 p-4 translate-y-full group-hover:translate-y-0 transition-transform duration-300 z-20 bg-gradient-to-t from-[#120024] to-transparent pt-12 flex gap-2 justify-center">
                  <button class="bg-white text-[#1a0035] w-12 h-12 rounded-full flex items-center justify-center hover:bg-[#d304f4] hover:text-white shadow-[0_0_15px_rgba(0,0,0,0.5)] transition-colors" onclick="window.location.href='{{ route('client.product') }}'">
                      <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" /></svg>
                  </button>
                  <button class="bg-white text-[#1a0035] w-12 h-12 rounded-full flex items-center justify-center hover:bg-[#d304f4] hover:text-white shadow-[0_0_15px_rgba(0,0,0,0.5)] transition-colors">
                      <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z" /></svg>
                  </button>
              </div>
          </div>
          <div class="p-5 flex-1 flex flex-col relative z-20 bg-[#1a0035]">
              <h3 class="text-xl font-bold mb-2 group-hover:text-[#d304f4] transition-colors"><a href='{{ route('client.product') }}' class='block'>Produit Premium {{ $i }}</a></h3>
              <p class="text-[#c4b5d6] text-sm mb-4 line-clamp-2">Le meilleur choix pour votre expérience e-commerce premium avec DMOC.</p>
              
              <div class="mt-auto">
                  <div class="flex items-end gap-3 mb-4">
                      @if($i == 1)
                      <span class='line-through text-[#c4b5d6]/70 text-sm'>25 000 FCFA</span>
                      @endif
                      <span class='text-2xl text-[#d304f4] font-black'>19 000 FCFA</span>
                  </div>
                  <x-btn-primary class='w-full' onclick="window.location.href='{{ route('client.product') }}'">Plus de détails</x-btn-primary>
              </div>
          </div>
      </x-card>
      @endfor
    </div>
    
    <div class='mt-12 flex justify-center gap-2'>
        <button class='w-10 h-10 rounded-full border border-[#5a2080] text-[#c4b5d6] hover:bg-[#3f047b] hover:text-white transition-colors flex items-center justify-center'><svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" /></svg></button>
        <button class='w-10 h-10 rounded-full bg-gradient-to-r from-[#d304f4] to-[#3f047b] text-white shadow-lg shadow-[#d304f4]/20 flex items-center justify-center font-bold'>1</button>
        <button class='w-10 h-10 rounded-full border border-[#5a2080] text-[#c4b5d6] hover:bg-[#3f047b] hover:text-white transition-colors flex items-center justify-center font-bold'>2</button>
        <button class='w-10 h-10 rounded-full border border-[#5a2080] text-[#c4b5d6] hover:bg-[#3f047b] hover:text-white transition-colors flex items-center justify-center font-bold'>3</button>
        <span class="w-10 h-10 flex items-center justify-center text-[#c4b5d6]">...</span>
        <button class='w-10 h-10 rounded-full border border-[#5a2080] text-[#c4b5d6] hover:bg-[#3f047b] hover:text-white transition-colors flex items-center justify-center font-bold'>8</button>
        <button class='w-10 h-10 rounded-full border border-[#5a2080] text-[#c4b5d6] hover:bg-[#3f047b] hover:text-white transition-colors flex items-center justify-center'><svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" /></svg></button>
    </div>
  </section>
</div>
<script>
const w=document.getElementById('wrap');
const btnGrid = document.getElementById('grid');
const btnList = document.getElementById('list');

if(btnGrid && btnList) {
    btnGrid.addEventListener('click',()=>{
        w.className='grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6';
        btnGrid.classList.replace('bg-transparent', 'bg-gradient-to-r');
        btnGrid.classList.replace('text-[#c4b5d6]', 'text-white');
        btnList.classList.replace('bg-gradient-to-r', 'bg-transparent');
        btnList.classList.replace('text-white', 'text-[#c4b5d6]');
    });
    btnList.addEventListener('click',()=>{
        w.className='grid grid-cols-1 gap-6';
        btnList.classList.add('bg-gradient-to-r', 'from-[#d304f4]', 'to-[#3f047b]', 'text-white');
        btnList.classList.remove('bg-transparent', 'text-[#c4b5d6]');
        btnGrid.classList.remove('bg-gradient-to-r', 'from-[#d304f4]', 'to-[#3f047b]', 'text-white');
        btnGrid.classList.add('bg-transparent', 'text-[#c4b5d6]');
    });
}
</script>
@endsection