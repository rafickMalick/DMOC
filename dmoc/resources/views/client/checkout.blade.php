@extends('layouts.client')
@section('title','Paiement Securise')
@section('content')

<x-page-header title='Paiement Sécurisé' breadcrumb='DMOC / E-commerce / Checkout'>
    <div class="flex items-center gap-2 mt-4 md:mt-0">
        <a href="{{ route('client.cart') }}" class="w-8 h-8 rounded-full bg-green-500/20 border border-green-500 flex items-center justify-center text-green-400 font-bold text-sm hover:bg-green-500 hover:text-white transition-colors">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" /></svg>
        </a>
        <span class="text-[#c4b5d6] text-xs font-bold uppercase tracking-wide">Panier</span>
        <div class="w-8 h-[2px] bg-green-500"></div>
        <span class="w-8 h-8 rounded-full bg-[#d304f4] flex items-center justify-center text-white font-bold text-sm">2</span>
        <span class="text-white text-xs font-bold uppercase tracking-wide">Paiement</span>
    </div>
</x-page-header>

<div class='grid lg:grid-cols-[1fr_400px] gap-8'>
  <section class="space-y-8">
      <!-- Livraison details -->
      <div class="bg-[#1a0035] border border-[#5a2080]/50 rounded-2xl overflow-hidden shadow-lg">
          <div class="px-6 py-4 border-b border-[#5a2080]/30 bg-[#120024] flex items-center gap-3">
              <span class="w-8 h-8 rounded-full bg-[#3f047b] text-white flex items-center justify-center font-bold font-mono">1</span>
              <h2 class="text-xl font-bold">Informations de livraison</h2>
          </div>
          <div class="p-6">
              <div class="grid md:grid-cols-2 gap-6">
                  <div class="space-y-2">
                      <label class="text-sm text-[#c4b5d6] font-medium">Prénom</label>
                      <input type="text" class="w-full bg-[#120024] border border-[#5a2080]/50 rounded-xl px-4 py-3 text-white focus:outline-none focus:border-[#d304f4] focus:ring-1 focus:ring-[#d304f4]" placeholder="Votre prénom">
                  </div>
                  <div class="space-y-2">
                      <label class="text-sm text-[#c4b5d6] font-medium">Nom</label>
                      <input type="text" class="w-full bg-[#120024] border border-[#5a2080]/50 rounded-xl px-4 py-3 text-white focus:outline-none focus:border-[#d304f4] focus:ring-1 focus:ring-[#d304f4]" placeholder="Votre nom">
                  </div>
                  <div class="col-span-2 space-y-2">
                      <label class="text-sm text-[#c4b5d6] font-medium">Adresse</label>
                      <input type="text" class="w-full bg-[#120024] border border-[#5a2080]/50 rounded-xl px-4 py-3 text-white focus:outline-none focus:border-[#d304f4] focus:ring-1 focus:ring-[#d304f4]" placeholder="Adresse, Appartement, etc.">
                  </div>
                  <div class="space-y-2">
                      <label class="text-sm text-[#c4b5d6] font-medium">Code postal</label>
                      <input type="text" class="w-full bg-[#120024] border border-[#5a2080]/50 rounded-xl px-4 py-3 text-white focus:outline-none focus:border-[#d304f4] focus:ring-1 focus:ring-[#d304f4]" placeholder="Code postal">
                  </div>
                  <div class="space-y-2">
                      <label class="text-sm text-[#c4b5d6] font-medium">Ville</label>
                      <input type="text" class="w-full bg-[#120024] border border-[#5a2080]/50 rounded-xl px-4 py-3 text-white focus:outline-none focus:border-[#d304f4] focus:ring-1 focus:ring-[#d304f4]" placeholder="Ville">
                  </div>
                  <div class="col-span-2 space-y-2">
                      <label class="text-sm text-[#c4b5d6] font-medium">Téléphone</label>
                      <input type="tel" class="w-full bg-[#120024] border border-[#5a2080]/50 rounded-xl px-4 py-3 text-white focus:outline-none focus:border-[#d304f4] focus:ring-1 focus:ring-[#d304f4]" placeholder="+221 XX XXX XX XX">
                  </div>
              </div>
          </div>
      </div>

      <!-- Paiement details -->
      <div class="bg-[#1a0035] border border-[#5a2080]/50 rounded-2xl overflow-hidden shadow-lg">
          <div class="px-6 py-4 border-b border-[#5a2080]/30 bg-[#120024] flex items-center justify-between">
              <div class="flex items-center gap-3">
                  <span class="w-8 h-8 rounded-full bg-[#3f047b] text-white flex items-center justify-center font-bold font-mono">2</span>
                  <h2 class="text-xl font-bold">Moyen de paiement</h2>
              </div>
              <div class="flex gap-2 text-xl">💳</div>
          </div>
          <div class="p-6">
              <div class="space-y-4">
                  <!-- Card Option -->
                  <label class="flex items-center justify-between p-4 border border-[#d304f4] rounded-xl bg-[#2a0550]/30 cursor-pointer">
                      <div class="flex items-center gap-4">
                          <input type="radio" name="payment" class="w-5 h-5 text-[#d304f4] bg-[#1a0035] border-[#5a2080] focus:ring-[#d304f4] focus:ring-offset-[#1a0035]" checked>
                          <span class="font-bold">Carte Bancaire (Stripe)</span>
                      </div>
                      <div class="flex gap-2">
                          <div class="w-8 h-5 bg-white/10 rounded flex items-center justify-center text-[8px] font-bold">VISA</div>
                          <div class="w-8 h-5 bg-white/10 rounded flex items-center justify-center text-[8px] font-bold">MC</div>
                      </div>
                  </label>
                  
                  <!-- Form Card -->
                  <div class="p-4 bg-[#120024] rounded-xl border border-[#5a2080]/30 ml-9 space-y-4">
                      <div class="space-y-2">
                          <label class="text-sm text-[#c4b5d6] font-medium">Titulaire de la carte</label>
                          <input type="text" class="w-full bg-[#1a0035] border border-[#5a2080]/50 rounded-lg px-4 py-2 text-white placeholder-[#c4b5d6]/50 focus:outline-none focus:border-[#d304f4]" placeholder="Nom gravé sur la carte">
                      </div>
                      <div class="space-y-2">
                          <label class="text-sm text-[#c4b5d6] font-medium">Numéro de carte</label>
                          <div class="relative">
                              <input type="text" class="w-full bg-[#1a0035] border border-[#5a2080]/50 rounded-lg pl-4 pr-12 py-2 text-white placeholder-[#c4b5d6]/50 focus:outline-none focus:border-[#d304f4]" placeholder="0000 0000 0000 0000">
                              <svg xmlns="http://www.w3.org/2000/svg" class="absolute right-3 top-1/2 -translate-y-1/2 h-6 w-6 text-[#c4b5d6]" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z" /></svg>
                          </div>
                      </div>
                      <div class="grid grid-cols-2 gap-4">
                          <div class="space-y-2">
                              <label class="text-sm text-[#c4b5d6] font-medium">Date d'expiration</label>
                              <input type="text" class="w-full bg-[#1a0035] border border-[#5a2080]/50 rounded-lg px-4 py-2 text-white placeholder-[#c4b5d6]/50 focus:outline-none focus:border-[#d304f4]" placeholder="MM/AA">
                          </div>
                          <div class="space-y-2">
                              <label class="text-sm text-[#c4b5d6] font-medium">CVC</label>
                              <div class="relative">
                                  <input type="text" class="w-full bg-[#1a0035] border border-[#5a2080]/50 rounded-lg pl-4 pr-10 py-2 text-white placeholder-[#c4b5d6]/50 focus:outline-none focus:border-[#d304f4]" placeholder="123">
                                  <div class="absolute right-3 top-1/2 -translate-y-1/2 w-4 h-4 rounded-full border border-[#c4b5d6] flex items-center justify-center text-[10px] text-[#c4b5d6] hover:text-white cursor-help tooltip tooltip-top" data-tip="3 chiffres au dos de votre carte">?</div>
                              </div>
                          </div>
                      </div>
                  </div>

                  <!-- Other Options -->
                  <label class="flex items-center p-4 border border-[#5a2080]/50 rounded-xl bg-[#1a0035] cursor-pointer hover:border-[#d304f4]/50 transition-colors">
                      <input type="radio" name="payment" class="w-5 h-5 text-[#d304f4] bg-[#1a0035] border-[#5a2080] focus:ring-[#d304f4] focus:ring-offset-[#1a0035]">
                      <span class="ml-4 font-bold">Paiement à la livraison</span>
                  </label>
              </div>
          </div>
      </div>
  </section>

  <!-- Right sidebar -->
  <aside>
      <div class='sticky top-28 rounded-3xl border border-[#5a2080]/50 bg-gradient-to-br from-[#1a0035] to-[#120024] p-6 md:p-8 shadow-xl'>
        <h3 class="text-xl font-bold mb-6 text-white border-b border-[#5a2080]/30 pb-4">Résumé de la commande</h3>
        
        <!-- Items minimal list -->
        <div class="space-y-4 mb-6 pt-2 h-40 overflow-y-auto pr-2 custom-scrollbar">
            <div class="flex gap-4">
                <div class="w-16 h-16 rounded-lg bg-[#2a0550] flex items-center justify-center border border-[#5a2080]/50 relative">
                    <span class="text-2xl">🎧</span>
                    <span class="absolute -top-2 -right-2 w-5 h-5 bg-[#3f047b] rounded-full text-white text-[10px] flex items-center justify-center font-bold">1</span>
                </div>
                <div class="flex-1 text-sm">
                    <p class="font-bold text-white truncate">Casque Pro X</p>
                    <p class="text-[#d304f4] font-bold mt-1">19 900 FCFA</p>
                </div>
            </div>
            <!-- More items can be iterated here from backend -->
        </div>
        
        <div class="space-y-3 mb-6 pt-6 border-t border-[#5a2080]/30">
            <div class="flex justify-between text-sm text-[#c4b5d6]">
                <span>Sous-total</span>
                <span>169 700 FCFA</span>
            </div>
            <div class="flex justify-between text-sm text-[#c4b5d6]">
                <span>Livraison</span>
                <span class="text-green-400">Gratuite</span>
            </div>
        </div>
            
        <div class="flex justify-between text-white text-xl font-black items-end mb-8 pt-4 border-t border-[#5a2080]/50">
            <span>Total</span>
            <span class="text-3xl text-[#d304f4] tracking-tight">169 700 <span class="text-sm">FCFA</span></span>
        </div>
        
        <x-btn-primary class='w-full py-4 text-lg shadow-[0_0_20px_rgba(211,4,244,0.3)] hover:shadow-[0_0_30px_rgba(211,4,244,0.5)] flex items-center justify-center gap-2' onclick="window.location.href='{{ route('client.confirmation') }}'">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M5 9V7a5 5 0 0110 0v2a2 2 0 012 2v5a2 2 0 01-2 2H5a2 2 0 01-2-2v-5a2 2 0 012-2zm8-2v2H7V7a3 3 0 016 0z" clip-rule="evenodd" /></svg>
            Payer 169 700 FCFA
        </x-btn-primary>
        
        <p class="text-center text-xs text-[#c4b5d6] mt-4 leading-relaxed">
            Vos données personnelles seront utilisées pour le traitement de votre commande et conformément à notre politique de confidentialité.
        </p>
      </div>
  </aside>
</div>
@endsection