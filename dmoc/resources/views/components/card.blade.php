<div {{ $attributes->merge(['class' => 'relative overflow-hidden rounded-2xl border border-[#5a2080]/50 bg-[#1a0035] p-6 shadow-xl shadow-[rgba(0,0,0,0.5)] transition-all duration-300 hover:border-[#d304f4]/80 hover:shadow-[0_0_20px_rgba(211,4,244,0.15)] group backdrop-blur-md']) }}>
    {{ $slot }}
</div>
