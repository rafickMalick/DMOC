<div>
    <h1 class="mb-2 text-3xl font-bold">Checkout</h1>
    <p class="mb-6 text-[#c4b5d6]">Finalisez votre commande en 3 etapes.</p>

    @if($errors->any())
        <div class="mb-4 rounded border border-red-500/40 bg-red-500/10 px-4 py-3 text-red-300">
            {{ $errors->first() }}
        </div>
    @endif

    <div class="mb-6 flex flex-wrap items-center gap-2">
        <span class="rounded-full border px-3 py-1 text-sm {{ $step >= 1 ? 'border-emerald-500/40 bg-emerald-500/20 text-emerald-300' : 'border-[#5a2080] bg-[#120024] text-[#c4b5d6]' }}">1 Adresse</span>
        <span class="text-[#5a2080]">→</span>
        <span class="rounded-full border px-3 py-1 text-sm {{ $step >= 2 ? 'border-emerald-500/40 bg-emerald-500/20 text-emerald-300' : 'border-[#5a2080] bg-[#120024] text-[#c4b5d6]' }}">2 Livraison</span>
        <span class="text-[#5a2080]">→</span>
        <span class="rounded-full border px-3 py-1 text-sm {{ $step >= 3 ? 'border-emerald-500/40 bg-emerald-500/20 text-emerald-300' : 'border-[#5a2080] bg-[#120024] text-[#c4b5d6]' }}">3 Confirmation</span>
    </div>

    <div class="grid gap-6 lg:grid-cols-[1fr_360px]">
        <section class="space-y-6">
            @if($cartItems->isEmpty())
                <x-card>
                    <p class="text-[#c4b5d6]">Votre panier est vide. Ajoutez des produits avant de continuer.</p>
                    <a href="{{ route('client.catalog') }}" class="mt-4 inline-block rounded-xl bg-[#d304f4] px-4 py-2 font-semibold">
                        Voir le catalogue
                    </a>
                </x-card>
            @else
                <x-card>
                    <h2 class="mb-4 text-xl font-semibold">Etape 1 - Adresse</h2>
                    <form wire:submit.prevent="saveStepOne" class="grid gap-3 md:grid-cols-2">
                        <div class="md:col-span-1">
                            <input wire:model.live="recipient_name" type="text" placeholder="Nom du destinataire"
                                class="w-full rounded border border-[#5a2080] bg-[#120024] px-3 py-2 text-white">
                            @error('recipient_name') <p class="mt-1 text-xs text-red-300">{{ $message }}</p> @enderror
                        </div>
                        <div class="md:col-span-1">
                            <input wire:model.live="shipping_phone" type="text" placeholder="Telephone"
                                class="w-full rounded border border-[#5a2080] bg-[#120024] px-3 py-2 text-white">
                            @error('shipping_phone') <p class="mt-1 text-xs text-red-300">{{ $message }}</p> @enderror
                        </div>
                        <div class="md:col-span-1">
                            <select wire:model.live="zone_id" class="w-full rounded border border-[#5a2080] bg-[#120024] px-3 py-2 text-white">
                                <option value="">Selectionner une zone</option>
                                @foreach($zones as $zone)
                                    <option value="{{ $zone->id }}">
                                        {{ $zone->name }} (base {{ number_format((int)$zone->base_tariff_xof, 0, ',', ' ') }} XOF)
                                    </option>
                                @endforeach
                            </select>
                            @error('zone_id') <p class="mt-1 text-xs text-red-300">{{ $message }}</p> @enderror
                        </div>
                        <div class="md:col-span-1">
                            <input wire:model.live="weight_kg" type="number" step="0.1" min="0" placeholder="Poids estime (kg)"
                                class="w-full rounded border border-[#5a2080] bg-[#120024] px-3 py-2 text-white">
                            @error('weight_kg') <p class="mt-1 text-xs text-red-300">{{ $message }}</p> @enderror
                        </div>
                        <div class="md:col-span-2">
                            <textarea wire:model.live="shipping_address" placeholder="Adresse de livraison"
                                class="w-full rounded border border-[#5a2080] bg-[#120024] px-3 py-2 text-white"></textarea>
                            @error('shipping_address') <p class="mt-1 text-xs text-red-300">{{ $message }}</p> @enderror
                        </div>
                        <div class="md:col-span-2">
                            <textarea wire:model.live="notes" placeholder="Notes (optionnel)"
                                class="w-full rounded border border-[#5a2080] bg-[#120024] px-3 py-2 text-white"></textarea>
                            @error('notes') <p class="mt-1 text-xs text-red-300">{{ $message }}</p> @enderror
                        </div>
                        <button type="submit" wire:loading.attr="disabled"
                            class="md:col-span-2 inline-flex items-center justify-center gap-2 rounded-xl bg-[#d304f4] px-4 py-2 font-semibold">
                            <span wire:loading.remove wire:target="saveStepOne">Valider l etape 1</span>
                            <span wire:loading wire:target="saveStepOne">Validation...</span>
                        </button>
                    </form>
                </x-card>

                <x-card class="{{ $order ? '' : 'opacity-60' }}">
                    <h2 class="mb-4 text-xl font-semibold">Etape 2 - Option de livraison</h2>
                    @if(!$order)
                        <p class="text-[#c4b5d6]">Validez d abord l etape 1.</p>
                    @else
                        <form wire:submit.prevent="saveStepTwo" class="space-y-3">
                            <label class="flex items-center gap-2">
                                <input wire:model.live="delivery_option" type="radio" value="standard">
                                Livraison standard (J+2)
                            </label>
                            <label class="flex items-center gap-2">
                                <input wire:model.live="delivery_option" type="radio" value="express">
                                Livraison express (J+1)
                            </label>
                            @error('delivery_option') <p class="text-xs text-red-300">{{ $message }}</p> @enderror

                            <button type="submit" wire:loading.attr="disabled"
                                class="inline-flex items-center gap-2 rounded-xl border border-[#d304f4] px-4 py-2 font-semibold text-[#d304f4] hover:bg-[#d304f4] hover:text-white">
                                <span wire:loading.remove wire:target="saveStepTwo">Valider l etape 2</span>
                                <span wire:loading wire:target="saveStepTwo">Validation...</span>
                            </button>
                        </form>
                    @endif
                </x-card>

                <x-card class="{{ $step >= 3 ? '' : 'opacity-60' }}">
                    <h2 class="mb-4 text-xl font-semibold">Etape 3 - Confirmation</h2>
                    @if($step < 3)
                        <p class="text-[#c4b5d6]">Validez l etape 2 pour confirmer la commande.</p>
                    @else
                        <button wire:click="confirm" wire:loading.attr="disabled"
                            class="inline-flex items-center gap-2 rounded-xl bg-[#22c55e] px-4 py-2 font-semibold hover:bg-[#16a34a]">
                            <span wire:loading.remove wire:target="confirm">Confirmer la commande</span>
                            <span wire:loading wire:target="confirm">Traitement...</span>
                        </button>
                    @endif
                </x-card>
            @endif
        </section>

        <aside>
            <x-card class="sticky top-28">
                <h3 class="mb-4 text-xl font-bold">Resume de commande</h3>
                <div class="space-y-2 text-sm">
                    @forelse($cartItems as $item)
                        <div class="flex justify-between gap-2">
                            <span class="text-[#c4b5d6]">{{ $item->product?->name ?? 'Produit' }} x{{ $item->quantity }}</span>
                            <span>{{ number_format((int)($item->quantity * $item->unit_price_xof), 0, ',', ' ') }} XOF</span>
                        </div>
                    @empty
                        <p class="text-[#c4b5d6]">Aucun article.</p>
                    @endforelse
                </div>
                <div class="mt-4 space-y-2 border-t border-[#5a2080] pt-3">
                    <div class="flex justify-between">
                        <span class="text-[#c4b5d6]">Sous-total</span>
                        <span>{{ number_format($subtotal, 0, ',', ' ') }} XOF</span>
                    </div>
                    @if($order)
                        <div class="flex justify-between">
                            <span class="text-[#c4b5d6]">Livraison</span>
                            <span>{{ number_format((int)$order->delivery_fee_xof, 0, ',', ' ') }} XOF</span>
                        </div>
                        <div class="flex justify-between text-lg font-semibold">
                            <span>Total</span>
                            <span class="text-[#d304f4]">{{ number_format((int)$order->total_xof, 0, ',', ' ') }} XOF</span>
                        </div>
                    @endif
                </div>
            </x-card>
        </aside>
    </div>
</div>
