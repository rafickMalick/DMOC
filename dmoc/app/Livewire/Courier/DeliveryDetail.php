<?php

namespace App\Livewire\Courier;

use App\Enums\DeliveryStatus;
use App\Enums\OrderStatus;
use App\Models\Courier;
use App\Models\Delivery;
use App\Models\OrderStatusLog;
use App\Models\Payment;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class DeliveryDetail extends Component
{
    public int $deliveryId;

    public ?Delivery $delivery = null;

    public ?int $amountReceived = null;

    public bool $showCodModal = false;

    public bool $showFailModal = false;

    public string $failedReason = 'client absent';

    public function mount(int $deliveryId): void
    {
        $this->deliveryId = $deliveryId;
        $this->loadDelivery();
    }

    public function start(): void
    {
        if (! $this->delivery) {
            return;
        }

        if (! in_array($this->delivery->status, [DeliveryStatus::Pending->value, DeliveryStatus::Assigned->value], true)) {
            $this->dispatch('toast', type: 'error', message: 'Cette livraison ne peut pas etre demarree.');

            return;
        }

        $this->delivery->update([
            'status' => DeliveryStatus::InDelivery->value,
            'started_at' => now(),
            'assigned_at' => $this->delivery->assigned_at ?? now(),
        ]);
        $this->setOrderStatus($this->delivery, OrderStatus::InDelivery->value, 'Prise en charge par le livreur.');

        $this->loadDelivery();
        $this->dispatch('toast', type: 'success', message: 'Livraison commencee.');
    }

    public function openCodModal(): void
    {
        if (! $this->delivery) {
            return;
        }

        $this->amountReceived = (int) ($this->delivery->amount_expected ?? $this->delivery->order?->total_xof ?? 0);
        $this->showCodModal = true;
    }

    public function completeCod(): void
    {
        $this->validate([
            'amountReceived' => ['required', 'integer', 'min:0'],
        ], [
            'amountReceived.required' => 'Le montant recu est obligatoire.',
            'amountReceived.integer' => 'Le montant recu doit etre un nombre entier.',
            'amountReceived.min' => 'Le montant recu doit etre positif.',
        ]);

        if (! $this->delivery) {
            return;
        }

        if (! in_array($this->delivery->status, [DeliveryStatus::InDelivery->value], true)) {
            $this->dispatch('toast', type: 'error', message: 'Cette livraison ne peut pas etre marquee livree.');

            return;
        }

        $expected = (int) ($this->delivery->amount_expected ?? $this->delivery->order?->total_xof ?? 0);
        $received = (int) $this->amountReceived;

        DB::transaction(function () use ($expected, $received): void {
            $this->delivery?->update([
                'status' => DeliveryStatus::Delivered->value,
                'delivered_at' => now(),
                'amount_expected' => $expected,
                'amount_received' => $received,
                'cod_received_at' => now(),
                'failed_reason' => null,
            ]);

            if ($this->delivery) {
                $this->setOrderStatus($this->delivery, OrderStatus::Delivered->value, 'Livraison finalisee par le livreur.');
            }
            $this->delivery?->order?->update(['payment_method' => 'cod']);

            if ($this->delivery?->order) {
                Payment::query()->updateOrCreate(
                    ['order_id' => $this->delivery->order->id],
                    [
                        'method' => 'cod',
                        'amount_xof' => $received,
                        'status' => 'success',
                        'reference' => 'COD-'.$this->delivery->order->id.'-'.now()->format('YmdHis'),
                        'response_data' => [
                            'source' => 'courier_livewire_detail_complete',
                            'amount_expected' => $expected,
                            'amount_received' => $received,
                        ],
                    ]
                );
            }
        });

        $this->showCodModal = false;
        $this->loadDelivery();
        $this->dispatch(
            'toast',
            type: $received === $expected ? 'success' : 'info',
            message: $received === $expected
                ? 'Paiement recu et livraison terminee.'
                : 'Livraison terminee avec ecart de montant enregistre.'
        );
    }

    public function failDelivery(): void
    {
        $this->validate([
            'failedReason' => ['required', 'in:client absent,adresse incorrecte,refus client'],
        ], [
            'failedReason.required' => 'La raison de l echec est obligatoire.',
            'failedReason.in' => 'La raison de l echec est invalide.',
        ]);

        if (! $this->delivery) {
            return;
        }

        if ($this->delivery->status === 'delivered') {
            $this->dispatch('toast', type: 'error', message: 'Une livraison deja terminee ne peut pas etre en echec.');

            return;
        }

        $this->delivery->update([
            'status' => DeliveryStatus::Failed->value,
            'failed_reason' => $this->failedReason,
        ]);
        $this->setOrderStatus($this->delivery, OrderStatus::Failed->value, 'Livraison en echec: '.$this->failedReason);

        $this->showFailModal = false;
        $this->loadDelivery();
        $this->dispatch('toast', type: 'success', message: 'Livraison marquee en echec.');
    }

    protected function loadDelivery(): void
    {
        $courier = Courier::query()->where('user_id', auth()->id())->firstOrFail();

        $this->delivery = Delivery::query()
            ->with(['order.items.product', 'order.user', 'order.payments', 'zone'])
            ->where('courier_id', $courier->id)
            ->findOrFail($this->deliveryId);

        $this->delivery->amount_expected = (int) ($this->delivery->amount_expected ?? $this->delivery->order?->total_xof ?? 0);
    }

    public function render()
    {
        return view('livewire.courier.delivery-detail');
    }

    protected function setOrderStatus(Delivery $delivery, string $toStatus, ?string $note = null): void
    {
        $order = $delivery->order;
        if (! $order || $order->status === $toStatus) {
            return;
        }

        $fromStatus = $order->status;
        $order->update(['status' => $toStatus]);

        OrderStatusLog::query()->create([
            'order_id' => $order->id,
            'from_status' => $fromStatus,
            'to_status' => $toStatus,
            'changed_by' => auth()->id(),
            'note' => $note,
        ]);
    }
}
