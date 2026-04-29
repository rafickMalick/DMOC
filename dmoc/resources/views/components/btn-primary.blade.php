<button {{ $attributes->merge(['type' => 'button', 'class' => 'inline-flex items-center justify-center rounded-full bg-gradient-to-r from-[#d304f4] to-[#3f047b] px-6 py-3 font-bold text-white transition-all duration-300 hover:scale-[1.02] hover:shadow-[0_0_20px_rgba(211,4,244,0.4)] focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-[#d304f4] focus:ring-offset-[#1a0035] shadow-lg']) }}>
    {{ $slot }}
</button>
