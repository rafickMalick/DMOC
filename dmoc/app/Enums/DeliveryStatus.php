<?php

namespace App\Enums;

enum DeliveryStatus: string
{
    case Pending = 'pending';
    case Assigned = 'assigned';
    case InDelivery = 'in_delivery';
    case Delivered = 'delivered';
    case Failed = 'failed';

    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }

    public function label(): string
    {
        return match ($this) {
            self::Pending => 'En attente',
            self::Assigned => 'Assignee',
            self::InDelivery => 'En cours',
            self::Delivered => 'Terminee',
            self::Failed => 'Echouee',
        };
    }

    public function badgeColor(): string
    {
        return match ($this) {
            self::Pending => 'amber',
            self::Assigned => 'indigo',
            self::InDelivery => 'yellow',
            self::Delivered => 'green',
            self::Failed => 'red',
        };
    }

    public function transitions(): array
    {
        return match ($this) {
            self::Pending => [self::Assigned],
            self::Assigned => [self::InDelivery, self::Failed],
            self::InDelivery => [self::Delivered, self::Failed],
            self::Delivered, self::Failed => [],
        };
    }
}
