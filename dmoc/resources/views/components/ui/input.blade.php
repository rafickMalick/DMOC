@props([
    'type' => 'text',
])

<input
    type="{{ $type }}"
    {{ $attributes->merge([
        'class' => 'w-full rounded-xl border border-dmoc-border bg-dmoc-input px-3 py-2 text-sm text-white placeholder:text-slate-400 focus:border-dmoc-accent focus:outline-none focus:ring-1 focus:ring-dmoc-accent',
    ]) }}
/>
@props([
    'type' => 'text',
])

<input
    type="{{ $type }}"
    {{ $attributes->merge([
        'class' => 'w-full rounded-xl border border-dmoc-border bg-dmoc-input px-3 py-2 text-sm text-white placeholder:text-slate-400 focus:border-dmoc-accent focus:outline-none focus:ring-1 focus:ring-dmoc-accent',
    ]) }}
/>
