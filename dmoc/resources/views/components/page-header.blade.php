@props(['title' => 'Titre', 'breadcrumb' => 'DMC / Page'])
<div class="relative mb-10 overflow-hidden rounded-3xl border border-[#ffedd5]/70 bg-[#f97316] p-8 md:p-12 shadow-2xl flex flex-col md:flex-row md:items-center justify-between gap-4">
    <!-- Glow -->
    <div class="absolute top-0 right-0 w-64 h-64 bg-[#fff7ed]/30 blur-[80px] rounded-full pointer-events-none"></div>
    <div class="relative z-10">
        <p class="text-xs font-semibold tracking-widest text-[#fff7ed] uppercase mb-2">{{ $breadcrumb }}</p>
        <h1 class="text-4xl md:text-5xl font-black text-white tracking-tight">{{ $title }}</h1>
    </div>
    <div class="relative z-10">
        {{ $slot }}
    </div>
</div>
