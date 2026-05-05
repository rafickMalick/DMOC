<?php

namespace App\Livewire\Checkout;

use App\Enums\OrderStatus;
use App\Models\Cart;
use App\Models\Order;
use App\Models\Payment;
use App\Models\Zone;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Livewire\Component;

class CheckoutWizard extends Component
{
    public Collection $cartItems;

    public Collection $zones;

    public ?Order $order = null;

    public int $subtotal = 0;

    public int $step = 1;

    public ?int $zone_id = null;

    public string $shipping_address = '';

    public string $shipping_phone = '';

    public string $recipient_name = '';

    public ?float $weight_kg = 1;

    public ?string $notes = null;

    public string $delivery_option = '';

    public function mount(): void
    {
        $this->cartItems = collect();
        $this->zones = Zone::query()->orderBy('name')->get();
        $this->refreshState();
    }

    protected function rules(): array
    {
        return match ($this->step) {
            1 => [
                'zone_id' => ['required', 'integer', 'exists:zones,id'],
                'shipping_address' => ['required', 'string', 'max:1000'],
                'shipping_phone' => ['required', 'string', 'max:30'],
                'recipient_name' => ['required', 'string', 'max:255'],
                'weight_kg' => ['nullable', 'numeric', 'min:0'],
                'notes' => ['nullable', 'string', 'max:2000'],
            ],
            2 => [
                'delivery_option' => ['required', 'in:standard,express'],
            ],
            default => [],
        };
    }

    protected function messages(): array
    {
        return [
            'zone_id.required' => 'Veuillez selectionner une zone de livraison.',
            'zone_id.exists' => 'La zone selectionnee est invalide.',
            'shipping_address.required' => 'L adresse de livraison est obligatoire.',
            'shipping_phone.required' => 'Le numero de telephone est obligatoire.',
            'recipient_name.required' => 'Le nom du destinataire est obligatoire.',
            'weight_kg.numeric' => 'Le poids doit etre un nombre valide.',
            'delivery_option.required' => 'Choisissez une option de livraison.',
        ];
    }

    public function updated($property): void
    {
        $this->validateOnly($property);
    }

    public function saveStepOne(): void
    {
        if ($this->cartItems->isEmpty()) {
            $this->dispatch('toast', type: 'error', message: 'Votre panier est vide.');

            return;
        }

        $data = $this->validate();

        $zone = Zone::query()->findOrFail((int) $data['zone_id']);
        $weightKg = (float) ($data['weight_kg'] ?? 1);
        $deliveryFee = (int) ($zone->base_tariff_xof + ($zone->per_kg_xof * $weightKg));
        $total = $this->subtotal + $deliveryFee;

        $order = DB::transaction(function () use ($data, $zone, $deliveryFee, $total) {
            $order = Order::query()->create([
                'user_id' => auth()->id(),
                'total_xof' => $total,
                'delivery_fee_xof' => $deliveryFee,
                'status' => OrderStatus::Pending->value,
                'delivery_zone_id' => $zone->id,
                'estimated_delivery' => now()->addDays(2),
                'shipping_address' => $data['shipping_address'],
                'shipping_phone' => $data['shipping_phone'],
                'recipient_name' => $data['recipient_name'],
                'notes' => $data['notes'] ?? null,
            ]);

            foreach ($this->cartItems as $cartItem) {
                $order->items()->create([
                    'product_id' => $cartItem->product_id,
                    'quantity' => $cartItem->quantity,
                    'price_xof' => $cartItem->unit_price_xof,
                ]);
            }

            return $order;
        });

        $this->order = $order->fresh(['items.product', 'zone', 'payments']);
        $this->step = 2;
        $this->dispatch('toast', type: 'success', message: 'Etape 1 validee.');
    }

    public function saveStepTwo(): void
    {
        if (! $this->order) {
            $this->dispatch('toast', type: 'error', message: 'Veuillez terminer l etape 1.');

            return;
        }

        $data = $this->validate();
        $label = $data['delivery_option'] === 'express' ? 'Livraison express' : 'Livraison standard';

        $currentNotes = trim((string) $this->order->notes);
        $cleanedNotes = preg_replace('/\n?\[Livraison\].*$/', '', $currentNotes) ?? '';
        $updatedNotes = trim($cleanedNotes."\n[Livraison] ".$label);

        $this->order->update([
            'notes' => $updatedNotes,
            // Pas d integration paiement: on garde une valeur technique minimale
            'payment_method' => 'cod',
        ]);

        Payment::query()->updateOrCreate(
            ['order_id' => $this->order->id],
            [
                'method' => 'cod',
                'amount_xof' => $this->order->total_xof,
                'status' => 'pending',
                'reference' => 'DMOC-PAY-'.Str::upper(Str::random(12)),
                'response_data' => ['source' => 'livewire_checkout_step_two', 'delivery_option' => $data['delivery_option']],
            ]
        );

        $this->step = 3;
        $this->dispatch('toast', type: 'success', message: 'Etape 2 validee.');
    }

    public function confirm(): void
    {
        if (! $this->order || $this->step < 3) {
            $this->dispatch('toast', type: 'error', message: 'Le parcours de commande est incomplet.');

            return;
        }

        DB::transaction(function () {
            // Conserve le statut pending, conformement a la demande sans paiement.
            $this->order->update(['status' => OrderStatus::Pending->value]);
            $cart = Cart::query()->where('user_id', auth()->id())->first();
            if ($cart) {
                $cart->items()->delete();
            }
        });

        session()->flash('success', 'Commande enregistree avec succes.');
        session()->flash('last_order_id', $this->order->id);
        $this->redirectRoute('client.confirmation', navigate: true);
    }

    protected function refreshState(): void
    {
        $cart = Cart::query()
            ->with(['items.product'])
            ->where('user_id', auth()->id())
            ->first();

        $this->cartItems = $cart?->items ?? collect();
        $this->subtotal = (int) $this->cartItems->sum(fn ($item) => $item->quantity * $item->unit_price_xof);

        $order = Order::query()
            ->with(['items.product', 'zone', 'payments'])
            ->where('user_id', auth()->id())
            ->where('status', OrderStatus::Pending->value)
            ->latest()
            ->first();

        if (! $order) {
            return;
        }

        $this->order = $order;
        $this->zone_id = $order->delivery_zone_id;
        $this->shipping_address = (string) $order->shipping_address;
        $this->shipping_phone = (string) $order->shipping_phone;
        $this->recipient_name = (string) $order->recipient_name;
        $this->notes = $order->notes;

        $this->step = 2;
        if (! empty($order->payment_method)) {
            $this->step = 3;
        }
    }

    public function render()
    {
        return view('livewire.checkout.checkout-wizard');
    }
}
