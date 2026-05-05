<div class="grid gap-6 lg:grid-cols-[1fr_360px]">
    <section class="space-y-4">
        @if($items->isEmpty())
            <x-card>
                <p class="text-[#c4b5d6]">Votre panier est vide.</p>
                <a href="{{ route('client.catalog') }}" class="mt-4 inline-block rounded-xl bg-[#d304f4] px-4 py-2 font-semibold">
                    Voir le catalogue
                </a>
            </x-card>
        @else
            @foreach($items as $item)
                <x-card class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
                    <div>
                        <h3 class="text-lg font-semibold">{{ $item->product?->name ?? 'Produit supprime' }}</h3>
                        <p class="text-sm text-[#c4b5d6]">Prix unitaire : {{ number_format((int)$item->unit_price_xof, 0, ',', ' ') }} XOF</p>
                        <p class="text-sm text-[#c4b5d6]">Stock : {{ $item->product?->stock ?? 0 }}</p>
                    </div>

                    <div class="flex items-center gap-3">
                        <button wire:click="decrease({{ $item->id }})" wire:loading.attr="disabled"
                            class="h-9 w-9 rounded-lg border border-[#5a2080] bg-[#120024] text-lg">
                            -
                        </button>
                        <span class="min-w-10 text-center font-semibold">{{ $item->quantity }}</span>
                        <button wire:click="increase({{ $item->id }})" wire:loading.attr="disabled"
                            class="h-9 w-9 rounded-lg border border-[#5a2080] bg-[#120024] text-lg">
                            +
                        </button>
                        <button wire:click="remove({{ $item->id }})" wire:loading.attr="disabled"
                            class="ml-2 rounded-lg border border-red-500/40 px-3 py-2 text-sm text-red-300 hover:bg-red-500/10">
                            Retirer
                        </button>
                    </div>

                    <div class="text-right">
                        <p class="text-lg font-bold text-[#d304f4]">
                            {{ number_format((int)($item->quantity * $item->unit_price_xof), 0, ',', ' ') }} XOF
                        </p>
                    </div>
                </x-card>
            @endforeach
        @endif
    </section>

    <aside>
        <x-card class="sticky top-28">
            <h2 class="mb-4 text-xl font-bold">Resume</h2>
            <div class="space-y-2 text-sm">
                <div class="flex justify-between">
                    <span class="text-[#c4b5d6]">Articles</span>
                    <span>{{ $itemsCount }}</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-[#c4b5d6]">Sous-total</span>
                    <span>{{ number_format($subtotal, 0, ',', ' ') }} XOF</span>
                </div>
            </div>

            @if($subtotal > 0)
                <a href="{{ route('client.checkout') }}" class="mt-5 inline-flex w-full justify-center rounded-xl bg-[#d304f4] px-4 py-3 font-semibold">
                    Passer au checkout
                </a>
            @endif
        </x-card>
    </aside>
</div>
