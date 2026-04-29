<button {{ $attributes->merge(['type' => 'button', 'class' => 'inline-flex items-center justify-center rounded-full border-2 border-[#5a2080] bg-transparent px-6 py-3 font-bold text-white transition-all duration-300 hover:border-[#d304f4] hover:bg-[#d304f4]/10 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-[#d304f4] focus:ring-offset-[#1a0035]']) }}>
    {{ $slot }}
</button>
