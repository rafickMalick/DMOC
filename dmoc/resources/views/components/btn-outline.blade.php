<button {{ $attributes->merge(['type' => 'button', 'class' => 'inline-flex items-center justify-center rounded-full border-2 border-[#9a5a2c] bg-transparent px-6 py-3 font-bold text-white transition-all duration-300 hover:border-[#f97316] hover:bg-[#f97316]/10 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-[#f97316] focus:ring-offset-[#23150b]']) }}>
    {{ $slot }}
</button>
