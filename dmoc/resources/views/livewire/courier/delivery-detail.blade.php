<div>
    @if(!$delivery)
        <x-card>Livraison introuvable.</x-card>
    @else
        @php
            $statusClass = match($delivery->status) {
                'delivered' => 'border-emerald-500/40 bg-emerald-500/10 text-emerald-200',
                'failed' => 'border-red-500/40 bg-red-500/10 text-red-200',
                'in_delivery' => 'border-blue-500/40 bg-blue-500/10 text-blue-200',
                default => 'border-gray-500/40 bg-gray-500/10 text-gray-200',
            };
        @endphp
        <div class="mb-4 flex items-center justify-between">
            <h1 class="text-2xl font-black md:text-3xl">Livraison #DEL-{{ $delivery->id }}</h1>
            <a href="{{ route('courier.list') }}" class="rounded-xl border border-[#5a2080] px-4 py-2 text-sm">Retour</a>
        </div>

        <div class="mb-4 flex items-center gap-2">
            <span class="rounded-full border px-3 py-1 text-xs uppercase {{ $statusClass }}">
                {{ $delivery->status }}
            </span>
            <span class="text-sm text-[#c4b5d6]">Commande #{{ $delivery->order_id }}</span>
        </div>

        <div class="grid gap-4 lg:grid-cols-2">
            <x-card>
                <h2 class="mb-3 text-lg font-semibold">Infos client</h2>
                <p><span class="text-[#c4b5d6]">Nom:</span> {{ $delivery->order?->recipient_name ?? $delivery->order?->user?->name ?? '-' }}</p>
                <p><span class="text-[#c4b5d6]">Telephone:</span> {{ $delivery->order?->shipping_phone ?? '-' }}</p>
                <p><span class="text-[#c4b5d6]">Adresse:</span> {{ $delivery->order?->shipping_address ?? '-' }}</p>
                <p><span class="text-[#c4b5d6]">Zone:</span> {{ $delivery->zone?->name ?? '-' }}</p>
                <p><span class="text-[#c4b5d6]">Montant COD attendu:</span> {{ number_format((int)$delivery->amount_expected, 0, ',', ' ') }} XOF</p>
                @if($delivery->failed_reason)
                    <p><span class="text-[#c4b5d6]">Raison echec:</span> {{ $delivery->failed_reason }}</p>
                @endif
            </x-card>

            <x-card>
                <h2 class="mb-3 text-lg font-semibold">Articles</h2>
                @forelse($delivery->order?->items ?? [] as $item)
                    <div class="mb-2 border-b border-[#2a0550] pb-2">
                        <p>{{ $item->product?->name ?? 'Produit supprime' }}</p>
                        <p class="text-sm text-[#c4b5d6]">Qte: {{ $item->quantity }} | Prix: {{ number_format((int)$item->price_xof, 0, ',', ' ') }} XOF</p>
                    </div>
                @empty
                    <p class="text-[#c4b5d6]">Aucun article.</p>
                @endforelse
            </x-card>
        </div>

        <div class="sticky bottom-0 mt-4 grid grid-cols-2 gap-2 rounded-2xl border border-[#5a2080]/70 bg-[#1a0035]/95 p-3 backdrop-blur-md sm:grid-cols-4">
            <button wire:click="start" wire:loading.attr="disabled"
                class="rounded-xl border border-blue-500/40 px-3 py-3 text-sm font-semibold text-blue-200 disabled:opacity-50">
                Commencer
            </button>
            <button wire:click="openCodModal" wire:loading.attr="disabled"
                class="rounded-xl border border-emerald-500/40 px-3 py-3 text-sm font-semibold text-emerald-200 disabled:opacity-50">
                Marquer livre
            </button>
            <button wire:click="$set('showFailModal', true)" wire:loading.attr="disabled"
                class="rounded-xl border border-red-500/40 px-3 py-3 text-sm font-semibold text-red-200 disabled:opacity-50">
                Echec
            </button>
            @if($delivery->order?->shipping_phone)
                <a href="tel:{{ $delivery->order->shipping_phone }}" class="rounded-xl border border-[#5a2080] px-3 py-3 text-center text-sm font-semibold">
                    Appeler
                </a>
            @endif
        </div>
    @endif

    @if($showCodModal)
        <div class="fixed inset-0 z-50 flex items-end justify-center bg-black/60 p-4 md:items-center">
            <div class="w-full max-w-md rounded-2xl border border-[#5a2080] bg-[#14002a] p-4">
                <h3 class="mb-2 text-lg font-bold">Confirmer livraison et encaissement COD</h3>
                <input wire:model.live="amountReceived" type="number" min="0"
                    class="w-full rounded-xl border border-[#5a2080] bg-[#1a0035] px-3 py-3">
                @error('amountReceived') <p class="mt-1 text-xs text-red-300">{{ $message }}</p> @enderror
                <div class="mt-4 grid grid-cols-2 gap-2">
                    <button wire:click="$set('showCodModal', false)" class="rounded-xl border border-[#5a2080] px-3 py-3 text-sm">Annuler</button>
                    <button wire:click="completeCod" wire:loading.attr="disabled"
                        class="rounded-xl bg-emerald-600 px-3 py-3 text-sm font-semibold disabled:opacity-50">
                        Confirmer
                    </button>
                </div>
            </div>
        </div>
    @endif

    @if($showFailModal)
        <div class="fixed inset-0 z-50 flex items-end justify-center bg-black/60 p-4 md:items-center">
            <div class="w-full max-w-md rounded-2xl border border-[#5a2080] bg-[#14002a] p-4">
                <h3 class="mb-2 text-lg font-bold">Echec de livraison</h3>
                <select wire:model.live="failedReason" class="w-full rounded-xl border border-[#5a2080] bg-[#1a0035] px-3 py-3">
                    <option value="client absent">client absent</option>
                    <option value="adresse incorrecte">adresse incorrecte</option>
                    <option value="refus client">refus client</option>
                </select>
                @error('failedReason') <p class="mt-1 text-xs text-red-300">{{ $message }}</p> @enderror
                <div class="mt-4 grid grid-cols-2 gap-2">
                    <button wire:click="$set('showFailModal', false)" class="rounded-xl border border-[#5a2080] px-3 py-3 text-sm">Annuler</button>
                    <button wire:click="failDelivery" wire:loading.attr="disabled"
                        class="rounded-xl bg-red-600 px-3 py-3 text-sm font-semibold disabled:opacity-50">
                        Confirmer
                    </button>
                </div>
            </div>
        </div>
    @endif
</div>
