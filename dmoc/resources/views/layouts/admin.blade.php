<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>DMOC - @yield('title', 'Admin')</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="min-h-screen bg-[#120024] text-white font-sans antialiased">
    <div class="fixed inset-0 bg-[radial-gradient(circle_at_top_right,rgba(211,4,244,0.12),transparent_45%)] pointer-events-none"></div>

    <aside class="fixed left-0 top-0 h-screen w-[280px] border-r border-[#5a2080]/60 bg-[#0d001c]/95 backdrop-blur-md p-6 z-40">
        <h1 class="mb-1 text-2xl font-black tracking-tight">DMOC Admin</h1>
        <p class="mb-6 text-xs text-[#c4b5d6]">Back-office operations</p>

        <nav class="space-y-2 text-sm">
            <a href="{{ route('admin.kpi') }}" class="block rounded-xl px-4 py-3 hover:bg-[#5a2080]/70 transition-colors">📊 KPI</a>
            <a href="{{ route('admin.products') }}" class="block rounded-xl px-4 py-3 hover:bg-[#5a2080]/70 transition-colors">📦 Produits</a>
            <a href="{{ route('admin.orders') }}" class="block rounded-xl bg-[#d304f4]/20 border border-[#d304f4]/40 px-4 py-3">🧾 Commandes</a>
            <a href="{{ route('admin.couriers') }}" class="block rounded-xl px-4 py-3 hover:bg-[#5a2080]/70 transition-colors">🚚 Livreurs</a>
            <a href="{{ route('admin.zones') }}" class="block rounded-xl px-4 py-3 hover:bg-[#5a2080]/70 transition-colors">🗺️ Zones</a>
            <a href="{{ route('admin.settings') }}" class="block rounded-xl px-4 py-3 hover:bg-[#5a2080]/70 transition-colors">⚙️ Parametres</a>
        </nav>
    </aside>

    <div class="ml-[280px] min-h-screen relative z-10">
        <header class="flex flex-wrap items-center justify-between gap-3 border-b border-[#5a2080]/50 px-8 py-5 bg-[#120024]/80 backdrop-blur-md sticky top-0 z-30">
            <div>
                <p class="text-xs uppercase tracking-wide text-[#c4b5d6]">DMOC / Admin</p>
                <h2 class="text-xl font-semibold">@yield('title')</h2>
            </div>
            <div class="flex items-center gap-3">
                <span class="rounded-full border border-[#5a2080] px-3 py-1 text-xs">Session admin</span>
                <a href="{{ route('client.auth') }}" class="rounded-xl border border-[#5a2080] px-4 py-2 text-sm hover:border-[#d304f4] hover:text-[#d304f4] transition-colors">Deconnexion</a>
            </div>
        </header>

        <main class="p-8">
            @yield('content')
        </main>
    </div>
</body>

</html>
