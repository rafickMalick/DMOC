<?php

namespace App\Enums;

enum OrderStatus: string
{
    case Pending = 'pending';
    case Confirmed = 'confirmed';
    case Assigned = 'assigned';
    case InDelivery = 'in_delivery';
    case Delivered = 'delivered';
    case Failed = 'failed';
    case Cancelled = 'cancelled';

    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }

    public function label(): string
    {
        return match ($this) {
            self::Pending => 'En attente',
            self::Confirmed => 'Confirmee',
            self::Assigned => 'Assignee',
            self::InDelivery => 'En cours de livraison',
            self::Delivered => 'Livree',
            self::Failed => 'Echouee',
            self::Cancelled => 'Annulee',
        };
    }

    public function badgeColor(): string
    {
        return match ($this) {
            self::Pending => 'amber',
            self::Confirmed => 'blue',
            self::Assigned => 'indigo',
            self::InDelivery => 'yellow',
            self::Delivered => 'green',
            self::Failed, self::Cancelled => 'red',
        };
    }

    public function transitions(): array
    {
        return match ($this) {
            self::Pending => [self::Confirmed, self::Cancelled],
            self::Confirmed => [self::Assigned, self::Cancelled],
            self::Assigned => [self::InDelivery, self::Cancelled],
            self::InDelivery => [self::Delivered, self::Failed],
            self::Delivered, self::Failed, self::Cancelled => [],
        };
    }
}
