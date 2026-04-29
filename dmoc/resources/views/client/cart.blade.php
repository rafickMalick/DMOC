@extends('layouts.client')
@section('title','Panier')
@section('content')

<x-page-header title='Mon Panier' breadcrumb='DMOC / E-commerce / Panier'>
    <div class="flex items-center gap-2 mt-4 md:mt-0">
        <span class="w-8 h-8 rounded-full bg-[#d304f4] flex items-center justify-center text-white font-bold text-sm">1</span>
        <span class="text-[#c4b5d6] text-xs font-bold uppercase tracking-wide">Panier</span>
        <div class="w-8 h-[2px] bg-[#5a2080]"></div>
        <span class="w-8 h-8 rounded-full bg-[#1a0035] border border-[#5a2080] flex items-center justify-center text-[#c4b5d6] font-bold text-sm">2</span>
        <span class="text-[#c4b5d6] text-xs font-bold uppercase tracking-wide opacity-50">Paiement</span>
    </div>
</x-page-header>

<div class='grid lg:grid-cols-[1fr_400px] gap-8'>
  <!-- Left Side: Cart Items -->
  <section class='flex flex-col gap-6'>
    <!-- Header row -->
    <div class="hidden md:grid grid-cols-12 gap-4 pb-4 border-b border-[#5a2080]/50 text-sm font-semibold text-[#c4b5d6] uppercase tracking-wider px-2">
        <div class="col-span-6">Produit</div>
        <div class="col-span-2 text-center">Prix</div>
        <div class="col-span-2 text-center">Quantité</div>
        <div class="col-span-2 text-right">Total</div>
    </div>
    
    <!-- Items loop -->
    @for($i = 1; $i <= 3; $i++)
    <div class="grid grid-cols-1 md:grid-cols-12 gap-4 items-center bg-[#1a0035] border border-[#5a2080]/50 rounded-2xl p-4 md:p-6 shadow-lg hover:border-[#d304f4]/50 transition-colors group">
        <!-- Product info -->
        <div class="col-span-1 md:col-span-6 flex items-center gap-4">
            <div class="w-24 h-24 rounded-xl border border-[#5a2080]/50 bg-gradient-to-br from-[#2a0550] to-[#120024] flex items-center justify-center text-4xl shrink-0">
                @if($i == 1)🎧@elseif($i==2)⌚@else📱@endif
            </div>
            <div>
                <a href="{{ route('client.product') }}" class="font-bold text-lg text-white hover:text-[#d304f4] transition-colors leading-tight block mb-1">
                    @if($i == 1)Casque Pro X @elseif($i==2)Montre Connectée Sport @elseSmartphone Ultra Edge @endif
                </a>
                <p class="text-[#c4b5d6] text-sm mb-2">Couleur: Noir Titane</p>
                <!-- Mobile only price -->
                <p class="md:hidden text-[#d304f4] font-bold">{{ ($i == 3 ? 129000 : 19900) }} FCFA</p>
                <button class="text-red-400 hover:text-red-300 text-xs font-semibold uppercase tracking-wider flex items-center gap-1 transition-colors">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" /></svg>
                    Supprimer
                </button>
            </div>
        </div>
        
        <!-- Desktop Price -->
        <div class="hidden md:block col-span-2 text-center text-white font-medium">
            {{ ($i == 3 ? 129 : 19) }},900 FCFA
        </div>
        
        <!-- Qty -->
        <div class="col-span-1 md:col-span-2 flex justify-start md:justify-center">
            <div class="flex items-center border border-[#5a2080] rounded-full bg-[#120024]">
                <button class="w-8 h-8 rounded-full flex items-center justify-center text-[#c4b5d6] hover:text-white transition-colors">-</button>
                <input type="text" value="1" class="w-8 bg-transparent text-center text-white border-none py-0 text-sm font-bold">
                <button class="w-8 h-8 rounded-full flex items-center justify-center text-[#c4b5d6] hover:text-white transition-colors">+</button>
            </div>
        </div>
        
        <!-- Desktop Total -->
        <div class="hidden md:block col-span-2 text-right font-bold text-[#d304f4] text-lg">
            {{ ($i == 3 ? 129 : 19) }},900 FCFA
        </div>
    </div>
    @endfor
    
    <div class="mt-4 flex flex-col sm:flex-row justify-between items-center bg-[#1a0035] border border-[#5a2080]/30 rounded-2xl p-6">
        <a href='{{ route('client.catalog') }}' class='inline-flex items-center text-[#c4b5d6] hover:text-white font-medium transition-colors group'>
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2 transform group-hover:-translate-x-1 transition-transform" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" /></svg>
            Continuer les achats
        </a>
        <button class="text-white hover:text-red-400 font-medium text-sm mt-4 sm:mt-0 transition-colors">
            Vider le panier
        </button>
    </div>
  </section>
  
  <!-- Right Side: Order Summary -->
  <aside class="flex flex-col gap-6">
      <div class='rounded-3xl border border-[#5a2080]/50 bg-gradient-to-br from-[#1a0035] to-[#120024] p-8 shadow-xl sticky top-28'>
        <h2 class="text-2xl font-bold mb-6 text-white text-center pb-6 border-b border-[#5a2080]/50">Résumé de la commande</h2>
        
        <div class="space-y-4 mb-8">
            <div class="flex justify-between text-[#c4b5d6]">
                <span>Sous-total (3 articles)</span>
                <span class="font-medium text-white">169 700 FCFA</span>
            </div>
            <div class="flex justify-between text-[#c4b5d6]">
                <span>Livraison</span>
                <span class="text-green-400 font-medium">Gratuite</span>
            </div>
            <div class="flex justify-between text-[#c4b5d6]">
                <span>TVA (18%)</span>
                <span class="font-medium text-white">Incluse</span>
            </div>
            
            <div class="pt-6 mt-4 border-t border-[#5a2080]/50">
                <div class="flex justify-between text-white text-xl font-black items-end">
                    <span>Total</span>
                    <span class="text-3xl text-[#d304f4] tracking-tight">169 700 <span class="text-sm">FCFA</span></span>
                </div>
                <p class="text-xs text-[#c4b5d6] text-right mt-1">Taxes incluses</p>
            </div>
        </div>
        
        <div class="mb-6 relative">
            <div class="flex">
                <input type="text" placeholder="Code promo" class="w-full bg-[#120024] border border-[#5a2080]/50 border-r-0 rounded-l-xl px-4 py-3 text-white focus:outline-none focus:border-[#d304f4]">
                <button class="bg-[#3f047b] border border-[#5a2080]/50 border-l-0 px-4 rounded-r-xl font-bold hover:bg-[#d304f4] hover:border-[#d304f4] transition-colors">Ok</button>
            </div>
        </div>
        
        <x-btn-primary class='w-full py-4 text-lg shadow-[0_0_20px_rgba(211,4,244,0.3)] hover:shadow-[0_0_30px_rgba(211,4,244,0.5)]' onclick="window.location.href='{{ route('client.checkout') }}'">
            Procéder au paiement
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 ml-2" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3" /></svg>
        </x-btn-primary>
        
        <!-- Trust indicators -->
        <div class="mt-6 flex justify-center gap-4">
            <div class="w-10 h-6 bg-white/10 rounded flex items-center justify-center text-[10px] font-bold font-mono">VISA</div>
            <div class="w-10 h-6 bg-white/10 rounded flex items-center justify-center text-[10px] font-bold font-mono">MC</div>
            <div class="w-10 h-6 bg-white/10 rounded flex items-center justify-center text-[10px] font-bold font-mono">AMEX</div>
            <div class="w-10 h-6 bg-white/10 rounded flex items-center justify-center text-[10px] font-bold font-mono">PayPal</div>
        </div>
        <p class="text-center text-xs text-[#c4b5d6] mt-4 flex items-center justify-center gap-1">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" /></svg> Paiement chiffré et sécurisé
        </p>
      </div>
  </aside>
</div>
@endsection