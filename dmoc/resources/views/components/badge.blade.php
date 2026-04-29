@props(['type' => 'info'])

@php
    $classes = match ($type) {
        'success' => 'bg-[#22c55e]/20 text-[#22c55e] border-[#22c55e]/40',
        'warning' => 'bg-[#f59e0b]/20 text-[#f59e0b] border-[#f59e0b]/40',
        'danger' => 'bg-[#ef4444]/20 text-[#ef4444] border-[#ef4444]/40',
        default => 'bg-[#d304f4]/20 text-[#d304f4] border-[#d304f4]/40',
    };
@endphp

<span {{ $attributes->merge(['class' => 'inline-flex items-center rounded-full border px-3 py-1 text-xs font-semibold ' . $classes]) }}>
    {{ $slot }}
</span>
