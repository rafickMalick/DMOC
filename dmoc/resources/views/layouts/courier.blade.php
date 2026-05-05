<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>DMOC - @yield('title', 'Livreur')</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles
    <style>[x-cloak]{display:none!important}</style>
</head>

<body x-data="{ toasts: [] }"
    x-init="window.addEventListener('dmoc-toast', (event) => { toasts.push({ id: Date.now() + Math.random(), ...event.detail }); setTimeout(() => toasts.shift(), 2800); });"
    class="min-h-screen bg-[#120024] text-white font-sans antialiased">
    <div class="fixed inset-0 bg-[radial-gradient(circle_at_top_left,rgba(56,189,248,0.10),transparent_40%)] pointer-events-none"></div>

    <nav class="sticky top-0 z-40 bg-[#0d001c]/90 backdrop-blur-md border-b border-[#5a2080]/60">
        <div class="mx-auto max-w-7xl flex flex-wrap items-center justify-between gap-3 px-4 py-4">
            <div>
                <a href="{{ route('courier.list') }}" class="text-2xl font-black tracking-tight">DMOC</a>
                <p class="text-xs text-[#c4b5d6]">Courier Workspace</p>
            </div>

            <div class="flex gap-2">
                <a href="{{ route('courier.list') }}" class="rounded-xl border border-[#5a2080] px-4 py-2 text-sm hover:border-[#d304f4] hover:text-[#d304f4] transition-colors">Mes livraisons</a>
                <form method="POST" action="{{ route('auth.logout') }}">
                    @csrf
                    <button class="rounded-xl border border-[#5a2080] px-4 py-2 text-sm hover:border-red-400 hover:text-red-300 transition-colors">Deconnexion</button>
                </form>
            </div>

            <x-badge type="success">En service</x-badge>
        </div>
    </nav>

    <main class="mx-auto max-w-7xl px-4 py-8 relative z-10">
        @yield('content')
    </main>

    <div class="fixed right-4 top-20 z-[120] w-72 space-y-2">
        <template x-for="toast in toasts" :key="toast.id">
            <div class="rounded-lg border px-4 py-3 text-sm shadow-xl"
                :class="{
                    'border-emerald-500/40 bg-emerald-500/20 text-emerald-100': toast.type === 'success',
                    'border-red-500/40 bg-red-500/20 text-red-100': toast.type === 'error',
                    'border-cyan-500/40 bg-cyan-500/20 text-cyan-100': toast.type === 'info'
                }">
                <span x-text="toast.message"></span>
            </div>
        </template>
    </div>

    <script>
        document.addEventListener('livewire:init', () => {
            Livewire.on('toast', ({ type, message }) => window.showToast(type, message));
        });
    </script>
    @livewireScripts
</body>

</html>
