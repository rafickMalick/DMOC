<div {{ $attributes->merge(['class' => 'relative overflow-hidden rounded-2xl border border-[#ffedd5]/70 bg-[#fb923c] p-6 shadow-xl shadow-[rgba(194,65,12,0.25)] transition-all duration-300 hover:border-white hover:shadow-[0_0_20px_rgba(249,115,22,0.2)] group backdrop-blur-md']) }}>
    {{ $slot }}
</div>
