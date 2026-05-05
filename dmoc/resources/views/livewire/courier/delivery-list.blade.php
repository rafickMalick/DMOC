<div>
    <div class="mb-4">
        <h1 class="text-2xl font-black md:text-3xl">Mes livraisons</h1>
        <p class="text-sm text-[#c4b5d6]">Vue mobile optimisee pour demarrer, livrer et encaisser rapidement.</p>
    </div>

    @if(!$courier)
        <article class="rounded-xl border border-[#5a2080] bg-[#1a0035] p-4">
            Aucun profil livreur n est associe a votre compte.
        </article>
    @else
        <div class="mb-4 grid grid-cols-2 gap-3 md:grid-cols-4">
            <x-card class="p-3">
                <p class="text-xs text-[#c4b5d6]">Aujourd hui</p>
                <p class="text-xl font-bold">{{ $this->stats['today'] }}</p>
            </x-card>
            <x-card class="p-3">
                <p class="text-xs text-[#c4b5d6]">Livrees</p>
                <p class="text-xl font-bold text-emerald-300">{{ $this->stats['delivered'] }}</p>
            </x-card>
            <x-card class="p-3">
                <p class="text-xs text-[#c4b5d6]">Echecs</p>
                <p class="text-xl font-bold text-red-300">{{ $this->stats['failed'] }}</p>
            </x-card>
            <x-card class="p-3">
                <p class="text-xs text-[#c4b5d6]">Cash collecte</p>
                <p class="text-lg font-bold text-amber-300">{{ number_format($this->stats['cash'], 0, ',', ' ') }} XOF</p>
            </x-card>
        </div>

        <div class="mb-4 grid grid-cols-1 gap-3 md:grid-cols-3">
            <select wire:model.live="statusFilter" class="rounded-xl border border-[#5a2080] bg-[#1a0035] px-3 py-3 text-sm">
                <option value="all">Tous les statuts</option>
                <option value="pending">pending</option>
                <option value="assigned">assigned</option>
                <option value="in_delivery">in_delivery</option>
                <option value="delivered">delivered</option>
                <option value="failed">failed</option>
            </select>
            <input wire:model.live="dateFilter" type="date" class="rounded-xl border border-[#5a2080] bg-[#1a0035] px-3 py-3 text-sm">
            <div class="rounded-xl border border-[#5a2080] bg-[#1a0035] px-3 py-3 text-sm text-[#c4b5d6]">
                {{ $deliveries->count() }} livraison(s)
            </div>
        </div>

        <div class="space-y-3">
            @forelse($deliveries as $delivery)
                @php
                    $statusClass = match($delivery->status) {
                        'delivered' => 'border-emerald-500/40 bg-emerald-500/10 text-emerald-200',
                        'failed' => 'border-red-500/40 bg-red-500/10 text-red-200',
                        'in_delivery' => 'border-blue-500/40 bg-blue-500/10 text-blue-200',
                        default => 'border-gray-500/40 bg-gray-500/10 text-gray-200',
                    };
                @endphp
                <article class="rounded-2xl border border-[#5a2080]/60 bg-[#1a0035]/90 p-4 shadow-xl shadow-purple-900/10">
                    <div class="mb-2 flex items-start justify-between gap-2">
                        <div>
                            <p class="font-bold">Commande #{{ $delivery->order_id }}</p>
                            <p class="text-sm text-[#c4b5d6]">
                                {{ $delivery->order?->recipient_name ?? $delivery->order?->user?->name ?? '-' }} - {{ $delivery->zone?->name ?? '-' }}
                            </p>
                        </div>
                        <span class="rounded-full border px-3 py-1 text-xs uppercase {{ $statusClass }}">
                            {{ $delivery->status }}
                        </span>
                    </div>

                    <div class="mb-3 text-sm">
                        <p class="text-[#c4b5d6]">COD attendu: <span class="font-semibold text-white">{{ number_format((int)$delivery->amount_expected, 0, ',', ' ') }} XOF</span></p>
                        @if($delivery->order?->shipping_phone)
                            <a href="tel:{{ $delivery->order->shipping_phone }}" class="text-[#d304f4]">Appeler client</a>
                        @endif
                    </div>

                    <div class="grid grid-cols-2 gap-2 sm:grid-cols-4">
                        <button wire:click="start({{ $delivery->id }})" wire:loading.attr="disabled"
                            class="rounded-xl border border-blue-500/40 px-3 py-3 text-sm font-semibold text-blue-200 disabled:opacity-50">
                            Demarrer
                        </button>
                        <button wire:click="openCodModal({{ $delivery->id }})" wire:loading.attr="disabled"
                            class="rounded-xl border border-emerald-500/40 px-3 py-3 text-sm font-semibold text-emerald-200 disabled:opacity-50">
                            Livre
                        </button>
                        <button wire:click="openFailModal({{ $delivery->id }})" wire:loading.attr="disabled"
                            class="rounded-xl border border-red-500/40 px-3 py-3 text-sm font-semibold text-red-200 disabled:opacity-50">
                            Echec
                        </button>
                        <a href="{{ route('courier.detail', $delivery->id) }}"
                            class="rounded-xl border border-[#5a2080] px-3 py-3 text-center text-sm font-semibold text-[#ddd6fe]">
                            Voir
                        </a>
                    </div>
                </article>
            @empty
                <article class="rounded-xl border border-[#5a2080] bg-[#1a0035] p-4 text-[#c4b5d6]">
                    Aucune livraison trouvee pour ce filtre.
                </article>
            @endforelse
        </div>
    @endif

    @if($showCodModal)
        <div class="fixed inset-0 z-50 flex items-end justify-center bg-black/60 p-4 md:items-center">
            <div class="w-full max-w-md rounded-2xl border border-[#5a2080] bg-[#14002a] p-4">
                <h3 class="mb-2 text-lg font-bold">Confirmer le paiement COD</h3>
                <p class="mb-3 text-sm text-[#c4b5d6]">Saisissez le montant recu avant validation.</p>
                <input wire:model.live="amountReceived" type="number" min="0"
                    class="w-full rounded-xl border border-[#5a2080] bg-[#1a0035] px-3 py-3">
                @error('amountReceived') <p class="mt-1 text-xs text-red-300">{{ $message }}</p> @enderror
                <div class="mt-4 grid grid-cols-2 gap-2">
                    <button wire:click="$set('showCodModal', false)" class="rounded-xl border border-[#5a2080] px-3 py-3 text-sm">Annuler</button>
                    <button wire:click="completeCod" wire:loading.attr="disabled"
                        class="rounded-xl bg-emerald-600 px-3 py-3 text-sm font-semibold disabled:opacity-50">
                        Valider
                    </button>
                </div>
            </div>
        </div>
    @endif

    @if($showFailModal)
        <div class="fixed inset-0 z-50 flex items-end justify-center bg-black/60 p-4 md:items-center">
            <div class="w-full max-w-md rounded-2xl border border-[#5a2080] bg-[#14002a] p-4">
                <h3 class="mb-2 text-lg font-bold">Marquer en echec</h3>
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
