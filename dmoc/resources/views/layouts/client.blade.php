<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>DMOC - @yield('title', 'Client')</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles
    <style>[x-cloak]{display:none!important}</style>
</head>

<body x-data="{ drawerOpen: false, userMenuOpen: false, darkMode: localStorage.getItem('dmoc-theme') !== 'light', toasts: [] }"
    x-init="$watch('darkMode', value => { document.documentElement.classList.toggle('dark', value); localStorage.setItem('dmoc-theme', value ? 'dark' : 'light'); }); document.documentElement.classList.toggle('dark', darkMode); window.addEventListener('dmoc-toast', (event) => { toasts.push({ id: Date.now() + Math.random(), ...event.detail }); setTimeout(() => toasts.shift(), 3000); });"
    @click="userMenuOpen = false"
    class="bg-[#120024] text-white font-sans antialiased overflow-x-hidden selection:bg-[#d304f4] selection:text-white">

    <!-- Top Banner -->
    <div
        class="w-full bg-[#0a0015] text-[#c4b5d6] text-xs font-semibold py-2.5 px-4 sticky top-0 z-[60] border-b border-[#1a0035] overflow-hidden whitespace-nowrap">
        <div class="flex items-center justify-center gap-8 w-full max-w-[90rem] mx-auto">
            <div class="flex items-center gap-2">
                <span class="text-orange-500 text-sm">🔥</span>
                <span>offre Spéciale<span class="text-[#2dd4bf] font-bold">-5%</span></span>
            </div>
            <div class="hidden md:flex items-center gap-2">
                <span class="w-1.5 h-1.5 rounded-full bg-[#5a2080]/50"></span>
                <a href="{{ route('client.catalog') }}"
                    class="hover:text-white underline decoration-[#5a2080] underline-offset-4 transition-colors">Faite
                    votre premier achat</a>
            </div>
            <div class="hidden lg:flex items-center gap-2">
                <span class="w-1.5 h-1.5 rounded-full bg-[#5a2080]/50"></span>
                <span class="text-yellow-500 text-sm">⚡</span>
                <span>Accès illimité aux Boutique</span>
            </div>
        </div>
    </div>

    <nav id="navbar"
        class="sticky top-10 z-50 w-full bg-[#0a0015] border-b border-transparent shadow-[0_10px_30px_-10px_rgba(0,0,0,0.5)] transition-all">
        <div class="mx-auto flex max-w-[90rem] items-center justify-between px-6 py-4 md:py-5 w-full">

            <!-- Logo -->
            <a href="{{ route('client.home') }}" class="flex items-center gap-2 group w-48">
                <span
                    class="text-[32px] font-black tracking-tighter text-white group-hover:opacity-80 transition-opacity">dmoc<span
                        class="text-transparent bg-clip-text bg-gradient-to-r from-[#d304f4] to-cyan-400">.</span></span>
            </a>

            <!-- Desktop Navigation -->
            <div class="hidden md:flex items-center justify-center flex-1 gap-10 font-bold text-sm tracking-wide">
                <a href="{{ route('client.home') }}"
                    class="text-white hover:text-[#d304f4] transition-colors relative">Accueil</a>
                <a href="{{ route('client.catalog') }}"
                    class="text-[#c4b5d6] hover:text-white transition-colors relative group">
                    Offre Spéciale
                    <span
                        class="absolute -top-3.5 -right-5 bg-[#0ea5e9] text-white text-[9px] font-black px-1.5 py-0.5 rounded-[4px] shadow-[0_0_10px_rgba(14,165,233,0.5)]">New</span>
                </a>
                <a href="{{ route('client.catalog') }}"
                    class="text-[#c4b5d6] hover:text-white transition-colors">shop</a>
                <a href="{{ route('client.wishlist') }}"
                    class="text-[#c4b5d6] hover:text-white transition-colors relative group">
                    Pour vous
                    <span
                        class="absolute -top-3.5 -right-4 bg-[#ef4444] text-white text-[9px] font-black px-1.5 py-0.5 rounded-[4px] shadow-[0_0_10px_rgba(239,68,68,0.5)]">Hot</span>
                </a>
                <a href="{{ route('client.orders') }}"
                    class="text-[#c4b5d6] hover:text-white transition-colors">Support</a>
            </div>

            <!-- Actions (Cart/Profile) -->
            <div class="hidden md:flex items-center justify-end gap-6 w-48">
                <div class="flex items-center gap-4 relative">
                    <button @click.stop="userMenuOpen = !userMenuOpen" class="text-[#c4b5d6] hover:text-white transition-colors">
                        <svg class="w-[22px] h-[22px]" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                            stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                        </svg>
                    </button>

                    <div x-cloak x-show="userMenuOpen" @click.stop
                        class="absolute top-10 right-0 z-50 mt-2 w-48 origin-top-right rounded-xl border border-[#2a0550] bg-[#0a0015] p-2 shadow-2xl">
                        <a href="{{ route('client.profile') }}"
                            class="block rounded-lg px-4 py-2.5 text-sm hover:bg-[#1a0035] hover:text-[#d304f4] transition-colors text-white">Mon
                            profil</a>
                        <a href="{{ route('client.dashboard') }}"
                            class="block rounded-lg px-4 py-2.5 text-sm hover:bg-[#1a0035] hover:text-[#d304f4] transition-colors text-white">Dashboard</a>
                        <div class="h-px bg-[#2a0550] my-1"></div>
                        <form method="POST" action="{{ route('auth.logout') }}">
                            @csrf
                            <button
                                class="block w-full rounded-lg px-4 py-2.5 text-left text-sm text-red-500 hover:bg-red-500/10 transition-colors">Deconnexion</button>
                        </form>
                    </div>
                </div>

                <a href="{{ route('client.cart') }}"
                    class="flex items-center gap-2 bg-[#120024] border border-[#2a0550] text-white text-sm font-bold px-4 py-2 rounded-lg hover:border-[#d304f4]/50 hover:bg-[#1a0035] hover:shadow-[0_0_20px_rgba(211,4,244,0.3)] transition-all duration-300">
                    <svg class="w-4 h-4 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z" />
                    </svg>
                    Panier
                    @php
                        $cartCount = auth()->check()
                            ? (int) (\App\Models\Cart::query()
                                ->where('user_id', auth()->id())
                                ->first()?->items()
                                ->sum('quantity') ?? 0)
                            : 0;
                    @endphp
                    @if($cartCount > 0)
                        <span class="rounded-full bg-[#d304f4] px-2 py-0.5 text-xs">{{ $cartCount }}</span>
                    @endif
                </a>
            </div>

            <!-- Mobile Menu Toggle -->
            <button @click.stop="drawerOpen = true" class="md:hidden text-[#c4b5d6] hover:text-white p-2">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24"
                    stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                </svg>
            </button>
        </div>
    </nav>

    <!-- Mobile Sidebar -->
    <aside :class="drawerOpen ? 'translate-x-0' : 'translate-x-full'"
        class="fixed right-0 top-0 z-[100] flex h-full w-80 flex-col border-l border-[#5a2080] bg-[#1a0035] shadow-2xl transition-transform duration-300 ease-in-out md:hidden">
        <div class="p-6 border-b border-[#5a2080]/50 flex items-center justify-between">
            <span class="text-xl font-black">Menu</span>
            <button @click="drawerOpen = false" class="text-[#c4b5d6] hover:text-white p-2 -mr-2">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24"
                    stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </div>
        <div class="p-4 flex-1 flex flex-col gap-2 overflow-y-auto">
            <a href="{{ route('client.home') }}"
                class="rounded-xl p-3 text-lg font-medium hover:bg-[#3f047b] hover:text-[#d304f4] transition-colors">Accueil</a>
            <a href="{{ route('client.catalog') }}"
                class="rounded-xl p-3 text-lg font-medium hover:bg-[#3f047b] hover:text-[#d304f4] transition-colors">Catalogue</a>
            <a href="{{ route('client.orders') }}"
                class="rounded-xl p-3 text-lg font-medium hover:bg-[#3f047b] hover:text-[#d304f4] transition-colors">Mes
                commandes</a>
            <a href="{{ route('client.wishlist') }}"
                class="rounded-xl p-3 text-lg font-medium hover:bg-[#3f047b] hover:text-[#d304f4] transition-colors">Wishlist</a>
        </div>
        <div class="p-6 border-t border-[#5a2080]/50 bg-[#120024]">
            <a href="{{ route('client.cart') }}"
                class="flex items-center justify-center gap-2 rounded-xl bg-[#3f047b] p-3 hover:bg-[#d304f4] transition-colors w-full font-bold">
                Voir mon panier (3)
            </a>
        </div>
    </aside>

    <button @click="darkMode = !darkMode"
        class="fixed bottom-24 right-4 z-40 rounded-full border border-[#5a2080] bg-[#120024] p-3 text-xs shadow-lg md:bottom-6">
        <span x-text="darkMode ? 'Mode clair' : 'Mode sombre'"></span>
    </button>

    <!-- Main content -->
    <main class="mx-auto max-w-[90rem] px-4 md:px-8 pt-8 pb-24">@yield('content')</main>

    <!-- Footer -->
    <footer class="bg-[#0f001f] border-t border-[#3f047b]/40 relative overflow-hidden">
        <!-- Footer glow -->
        <div class="absolute top-0 right-0 w-96 h-96 bg-[#d304f4]/5 rounded-full blur-[100px] pointer-events-none">
        </div>
        <div class="mx-auto max-w-[90rem] grid gap-10 px-6 py-16 md:grid-cols-4 relative z-10">
            <div class="md:col-span-1">
                <h3 class="text-2xl font-black mb-4 group inline-block">dmoc<span class="text-[#d304f4]">.</span></h3>
                <p class="text-[#c4b5d6] text-sm leading-relaxed mb-6">Craft a beautiful and high-converting store with
                    our premium platform. Optimized for eCommerce speed.</p>
                <div class="flex gap-4">
                    <!-- Social placeholder -->
                    <a href="#"
                        class="w-10 h-10 rounded-full bg-[#1a0035] border border-[#5a2080] flex items-center justify-center hover:bg-[#d304f4] hover:border-[#d304f4] transition-all">FB</a>
                    <a href="#"
                        class="w-10 h-10 rounded-full bg-[#1a0035] border border-[#5a2080] flex items-center justify-center hover:bg-[#d304f4] hover:border-[#d304f4] transition-all">TW</a>
                    <a href="#"
                        class="w-10 h-10 rounded-full bg-[#1a0035] border border-[#5a2080] flex items-center justify-center hover:bg-[#d304f4] hover:border-[#d304f4] transition-all">IG</a>
                </div>
            </div>
            <div>
                <h3 class="font-bold text-lg mb-6">Shopping</h3>
                <div class="flex flex-col gap-3">
                    <a class="text-[#c4b5d6] hover:text-[#d304f4] hover:translate-x-1 transition-all"
                        href="{{ route('client.home') }}">Accueil</a>
                    <a class="text-[#c4b5d6] hover:text-[#d304f4] hover:translate-x-1 transition-all"
                        href="{{ route('client.catalog') }}">Catalogue Produits</a>
                    <a class="text-[#c4b5d6] hover:text-[#d304f4] hover:translate-x-1 transition-all" href="#">Nouvelle
                        Collection</a>
                </div>
            </div>
            <div>
                <h3 class="font-bold text-lg mb-6">Assistance</h3>
                <div class="flex flex-col gap-3">
                    <a class="text-[#c4b5d6] hover:text-[#d304f4] hover:translate-x-1 transition-all" href="#">Suivi
                        Commande</a>
                    <a class="text-[#c4b5d6] hover:text-[#d304f4] hover:translate-x-1 transition-all" href="#">Retours &
                        Échanges</a>
                    <a class="text-[#c4b5d6] hover:text-[#d304f4] hover:translate-x-1 transition-all" href="#">FAQ</a>
                </div>
            </div>
            <div>
                <h3 class="font-bold text-lg mb-6">Contact & Info</h3>
                <p class="text-[#c4b5d6] text-sm mb-2">support@dmoc.shop</p>
                <p class="text-[#c4b5d6] text-sm">+221 77 123 45 67</p>
            </div>
        </div>
        <div class="border-t border-[#5a2080]/30 py-6 text-center">
            <p class="text-[#c4b5d6] text-sm">© 2026 DMOC. Tous droits réservés.</p>
        </div>
    </footer>

    <nav class="fixed inset-x-0 bottom-0 z-50 border-t border-[#2a0550] bg-[#0a0015] md:hidden">
        <div class="mx-auto grid max-w-md grid-cols-4 text-xs">
            <a href="{{ route('client.home') }}" class="px-3 py-3 text-center">Accueil</a>
            <a href="{{ route('client.catalog') }}" class="px-3 py-3 text-center">Catalogue</a>
            <a href="{{ route('client.cart') }}" class="px-3 py-3 text-center">Panier</a>
            <a href="{{ route('client.profile') }}" class="px-3 py-3 text-center">Profil</a>
        </div>
    </nav>

    <div class="fixed right-4 top-24 z-[120] w-80 space-y-2">
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
            Livewire.on('toast', ({ type, message }) => {
                window.showToast(type, message);
            });
        });
    </script>
    @livewireScripts
</body>

</html>