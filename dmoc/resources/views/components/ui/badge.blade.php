@props([
    'variant' => 'default',
])

@php
    $styles = match ($variant) {
        'success' => 'bg-emerald-500/20 text-emerald-300 border-emerald-500/30',
        'warning' => 'bg-amber-500/20 text-amber-300 border-amber-500/30',
        'danger' => 'bg-red-500/20 text-red-300 border-red-500/30',
        default => 'bg-dmoc-primary/25 text-dmoc-accent border-dmoc-border',
    };
@endphp

<span {{ $attributes->merge(['class' => 'inline-flex items-center rounded-full border px-2.5 py-1 text-xs font-semibold '.$styles]) }}>
    {{ $slot }}
</span>
