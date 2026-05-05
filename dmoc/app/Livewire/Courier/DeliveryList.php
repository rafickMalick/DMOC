<?php

namespace App\Livewire\Courier;

use App\Enums\DeliveryStatus;
use App\Enums\OrderStatus;
use App\Models\Courier;
use App\Models\Delivery;
use App\Models\OrderStatusLog;
use App\Models\Payment;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class DeliveryList extends Component
{
    public string $statusFilter = 'all';

    public string $dateFilter = '';

    public ?Courier $courier = null;

    public ?int $selectedDeliveryId = null;

    public ?int $amountReceived = null;

    public bool $showCodModal = false;

    public bool $showFailModal = false;

    public string $failedReason = 'client absent';

    public Collection $deliveries;

    public function mount(): void
    {
        $this->deliveries = collect();
        $this->courier = Courier::query()->where('user_id', auth()->id())->first();
        $this->loadDeliveries();
    }

    public function updatedStatusFilter(): void
    {
        $this->loadDeliveries();
    }

    public function updatedDateFilter(): void
    {
        $this->loadDeliveries();
    }

    public function start(int $deliveryId): void
    {
        $delivery = $this->findDelivery($deliveryId);
        if (! $delivery) {
            return;
        }

        if (! in_array($delivery->status, [DeliveryStatus::Pending->value, DeliveryStatus::Assigned->value], true)) {
            $this->dispatch('toast', type: 'error', message: 'Cette livraison ne peut pas etre demarree.');

            return;
        }

        $delivery->update([
            'status' => DeliveryStatus::InDelivery->value,
            'started_at' => now(),
            'assigned_at' => $delivery->assigned_at ?? now(),
        ]);

        $this->setOrderStatus($delivery, OrderStatus::InDelivery->value, 'Prise en charge par le livreur.');
        $this->loadDeliveries();
        $this->dispatch('toast', type: 'success', message: 'Livraison commencee.');
    }

    public function openCodModal(int $deliveryId): void
    {
        $delivery = $this->findDelivery($deliveryId);
        if (! $delivery) {
            return;
        }

        $expected = (int) ($delivery->amount_expected ?? $delivery->order?->total_xof ?? 0);
        $this->selectedDeliveryId = $delivery->id;
        $this->amountReceived = $expected;
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

        $delivery = $this->findDelivery((int) $this->selectedDeliveryId);
        if (! $delivery) {
            return;
        }

        if (! in_array($delivery->status, [DeliveryStatus::InDelivery->value], true)) {
            $this->dispatch('toast', type: 'error', message: 'Cette livraison ne peut pas etre marquee livree.');

            return;
        }

        $expected = (int) ($delivery->amount_expected ?? $delivery->order?->total_xof ?? 0);
        $received = (int) $this->amountReceived;

        DB::transaction(function () use ($delivery, $expected, $received): void {
            $delivery->update([
                'status' => DeliveryStatus::Delivered->value,
                'delivered_at' => now(),
                'amount_expected' => $expected,
                'amount_received' => $received,
                'cod_received_at' => now(),
                'failed_reason' => null,
            ]);

            $this->setOrderStatus($delivery, OrderStatus::Delivered->value, 'Livraison finalisee par le livreur.');
            $delivery->order?->update(['payment_method' => 'cod']);

            if ($delivery->order) {
                Payment::query()->updateOrCreate(
                    ['order_id' => $delivery->order->id],
                    [
                        'method' => 'cod',
                        'amount_xof' => $received,
                        'status' => 'success',
                        'reference' => 'COD-'.$delivery->order->id.'-'.now()->format('YmdHis'),
                        'response_data' => [
                            'source' => 'courier_livewire_complete',
                            'amount_expected' => $expected,
                            'amount_received' => $received,
                        ],
                    ]
                );
            }
        });

        $this->showCodModal = false;
        $this->selectedDeliveryId = null;
        $this->loadDeliveries();

        if ($received !== $expected) {
            $this->dispatch('toast', type: 'info', message: 'Livraison terminee avec ecart de montant enregistre.');

            return;
        }

        $this->dispatch('toast', type: 'success', message: 'Livraison terminee et paiement COD confirme.');
    }

    public function openFailModal(int $deliveryId): void
    {
        $delivery = $this->findDelivery($deliveryId);
        if (! $delivery) {
            return;
        }

        $this->selectedDeliveryId = $delivery->id;
        $this->failedReason = 'client absent';
        $this->showFailModal = true;
    }

    public function failDelivery(): void
    {
        $this->validate([
            'failedReason' => ['required', 'in:client absent,adresse incorrecte,refus client'],
        ], [
            'failedReason.required' => 'La raison de l echec est obligatoire.',
            'failedReason.in' => 'La raison de l echec est invalide.',
        ]);

        $delivery = $this->findDelivery((int) $this->selectedDeliveryId);
        if (! $delivery) {
            return;
        }

        if ($delivery->status === 'delivered') {
            $this->dispatch('toast', type: 'error', message: 'Une livraison deja terminee ne peut pas etre en echec.');

            return;
        }

        $delivery->update([
            'status' => DeliveryStatus::Failed->value,
            'failed_reason' => $this->failedReason,
        ]);

        $this->setOrderStatus($delivery, OrderStatus::Failed->value, 'Livraison en echec: '.$this->failedReason);
        $this->showFailModal = false;
        $this->selectedDeliveryId = null;
        $this->loadDeliveries();
        $this->dispatch('toast', type: 'success', message: 'Livraison marquee en echec.');
    }

    public function getStatsProperty(): array
    {
        if (! $this->courier) {
            return ['today' => 0, 'delivered' => 0, 'failed' => 0, 'cash' => 0];
        }

        $base = Delivery::query()
            ->where('courier_id', $this->courier->id)
            ->whereDate('updated_at', today());

        return [
            'today' => (clone $base)->count(),
            'delivered' => (clone $base)->where('status', 'delivered')->count(),
            'failed' => (clone $base)->where('status', 'failed')->count(),
            'cash' => (int) (clone $base)->where('status', 'delivered')->sum('amount_received'),
        ];
    }

    protected function loadDeliveries(): void
    {
        if (! $this->courier) {
            $this->deliveries = collect();

            return;
        }

        $query = Delivery::query()
            ->with(['order.user', 'zone'])
            ->where('courier_id', $this->courier->id)
            ->when($this->statusFilter !== 'all', function (Builder $q): void {
                $q->where('status', $this->statusFilter);
            })
            ->when($this->dateFilter !== '', fn (Builder $q) => $q->whereDate('created_at', $this->dateFilter))
            ->latest();

        $this->deliveries = $query->get()->map(function (Delivery $delivery) {
            $delivery->amount_expected = (int) ($delivery->amount_expected ?? $delivery->order?->total_xof ?? 0);

            return $delivery;
        });
    }

    protected function findDelivery(int $deliveryId): ?Delivery
    {
        if (! $this->courier) {
            $this->dispatch('toast', type: 'error', message: 'Profil livreur introuvable.');

            return null;
        }

        return Delivery::query()
            ->with(['order.payments', 'order.user', 'zone'])
            ->where('courier_id', $this->courier->id)
            ->find($deliveryId);
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

    public function render()
    {
        return view('livewire.courier.delivery-list');
    }
}
