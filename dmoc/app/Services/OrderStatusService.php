<?php

namespace App\Services;

use App\Models\Order;
use Illuminate\Validation\ValidationException;

class OrderStatusService
{
    public function transition(
        Order $order,
        string $nextStatus,
        ?int $changedBy = null,
        string $source = 'system',
        ?string $note = null
    ): Order {
        if (! $order->canTransitionTo($nextStatus)) {
            throw ValidationException::withMessages([
                'status' => "Transition de statut invalide: {$order->status} -> {$nextStatus}",
            ]);
        }

        $previous = $order->status;
        if ($previous !== $nextStatus) {
            $order->update(['status' => $nextStatus]);
        }

        $order->statusHistory()->create([
            'from_status' => $previous,
            'to_status' => $nextStatus,
            'source' => $source,
            'changed_by' => $changedBy,
            'note' => $note,
        ]);

        return $order->fresh();
    }
}
