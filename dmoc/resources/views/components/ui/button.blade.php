@props([
    'variant' => 'primary',
    'type' => 'button',
])

@php
    $base = 'inline-flex items-center justify-center rounded-xl px-4 py-2 text-sm font-semibold transition-colors focus:outline-none focus:ring-2 focus:ring-offset-0';
    $styles = match ($variant) {
        'outline' => 'border border-dmoc-accent text-dmoc-accent hover:bg-dmoc-accent hover:text-white',
        'ghost' => 'text-slate-200 hover:bg-white/5',
        default => 'bg-dmoc-accent text-white hover:brightness-110',
    };
@endphp

<button type="{{ $type }}" {{ $attributes->merge(['class' => $base.' '.$styles]) }}>
    {{ $slot }}
</button>
