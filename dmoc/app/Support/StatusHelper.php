<?php

namespace App\Support;

use App\Enums\DeliveryStatus;
use App\Enums\OrderStatus;

class StatusHelper
{
    public static function orderLabel(string $status): string
    {
        return OrderStatus::tryFrom($status)?->label() ?? $status;
    }

    public static function orderBadgeClasses(string $status): string
    {
        $color = OrderStatus::tryFrom($status)?->badgeColor() ?? 'slate';

        return self::badgeClasses($color);
    }

    public static function deliveryLabel(string $status): string
    {
        return DeliveryStatus::tryFrom($status)?->label() ?? $status;
    }

    public static function deliveryBadgeClasses(string $status): string
    {
        $color = DeliveryStatus::tryFrom($status)?->badgeColor() ?? 'slate';

        return self::badgeClasses($color);
    }

    public static function allowedOrderTransitions(string $status): array
    {
        return array_map(
            static fn (OrderStatus $next) => $next->value,
            OrderStatus::tryFrom($status)?->transitions() ?? []
        );
    }

    private static function badgeClasses(string $color): string
    {
        return match ($color) {
            'amber' => 'border-amber-500/50 bg-amber-500/10 text-amber-300',
            'blue' => 'border-blue-500/50 bg-blue-500/10 text-blue-300',
            'indigo' => 'border-indigo-500/50 bg-indigo-500/10 text-indigo-300',
            'yellow' => 'border-yellow-500/50 bg-yellow-500/10 text-yellow-300',
            'green' => 'border-emerald-500/50 bg-emerald-500/10 text-emerald-300',
            'red' => 'border-red-500/50 bg-red-500/10 text-red-300',
            default => 'border-slate-500/50 bg-slate-500/10 text-slate-300',
        };
    }
}
