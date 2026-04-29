@extends('layouts.client')
@section('title', 'Accueil')
@section('content')

    {{-- SECTION HERO --}}
    <section class='relative min-h-[90vh] flex items-center justify-center overflow-hidden pt-24 pb-16 bg-[#0a0015] -mt-10'>

        <!-- Top Horizontal Light Beam -->
        <div
            class="absolute top-0 left-1/2 -translate-x-1/2 w-4/5 max-w-5xl h-[1px] bg-gradient-to-r from-transparent via-white to-transparent opacity-80 shadow-[0_20px_50px_15px_rgba(255,255,255,0.1),_0_10px_30px_10px_rgba(45,212,191,0.2)]">
        </div>
        <div
            class="absolute top-0 left-1/2 -translate-x-1/2 w-1/3 h-32 bg-[radial-gradient(ellipse_at_top,#2dd4bf_0%,transparent_70%)] opacity-20 pointer-events-none">
        </div>

        <div class="mx-auto max-w-[90rem] px-6 w-full relative z-10 grid lg:grid-cols-2 gap-16 items-center">
            <!-- Text Content -->
            <div class="space-y-6 md:space-y-8 text-center lg:text-left pt-10">
                <h2
                    class="inline-block font-semibold tracking-wide text-transparent bg-clip-text bg-gradient-to-r from-[#d304f4] to-[#2dd4bf] text-lg md:text-2xl">
                    One-stop DMOC Theme
                </h2>
                <h1 class='text-5xl md:text-7xl lg:text-[5rem] leading-[1.05] font-black tracking-tight text-white mb-4'>
                    Prime Quality<br />Premium Design
                </h1>
                <p class='text-lg md:text-xl text-[#c4b5d6] max-w-xl mx-auto lg:mx-0 leading-relaxed font-medium'>
                    Créez une boutique e-commerce magnifique et à fort taux de conversion avec le thème DMOC. Optimisé pour
                    la vitesse et l'expérience utilisateur.
                </p>
                <div class='flex flex-col sm:flex-row gap-4 justify-center lg:justify-start pt-6'>
                    <a href="{{ route('client.catalog') }}"
                        class="group relative inline-flex items-center justify-center px-8 py-3.5 text-sm font-bold text-white transition-all duration-300 bg-[#120024] border border-[#2a0550] rounded-xl hover:border-[#d304f4]/50 hover:shadow-[0_0_30px_rgba(211,4,244,0.4)]">
                        <span class="mr-2 text-yellow-400">✦</span> Explorer Boutique
                    </a>
                    <a href="{{ route('client.tracking') }}"
                        class="group inline-flex items-center justify-center px-8 py-3.5 text-sm font-bold text-[#c4b5d6] transition-all duration-300 rounded-xl hover:text-white hover:bg-[#1a0035] border border-transparent hover:border-[#2a0550]">
                        <svg class="w-5 h-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" />
                        </svg> Ouvrir Gratuitement
                    </a>
                </div>
            </div>

            <!-- Mockup Interface -->
            <div class="relative w-full perspective-[2000px] mt-10 lg:mt-0">
                <div
                    class="relative w-full rounded-[1.5rem] bg-[#141416]/90 border border-[#2a2a2b] shadow-2xl p-4 md:p-5 backdrop-blur-3xl overflow-hidden">
                    <!-- Browser Header Elements -->
                    <div class="absolute top-4 left-4 flex gap-1.5 z-20">
                        <div class="w-2.5 h-2.5 rounded-full bg-[#3f3f40] border border-[#525255]"></div>
                        <div class="w-2.5 h-2.5 rounded-full bg-[#3f3f40] border border-[#525255]"></div>
                        <div class="w-2.5 h-2.5 rounded-full bg-[#3f3f40] border border-[#525255]"></div>
                    </div>

                    <div class="absolute top-3 right-6 flex items-center justify-center gap-1.5 z-20">
                        <div
                            class="w-6 h-6 rounded-md bg-[#2dd4bf] flex items-center justify-center shadow-[0_0_15px_rgba(45,212,191,0.5)]">
                            <svg class="w-3 h-3 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                                stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                            </svg>
                        </div>
                        <div class="w-4 h-6 rounded-md bg-white flex items-center justify-center"><svg
                                class="w-2.5 h-2.5 text-[#141416]" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                                stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M12 18h.01M8 21h8a2 2 0 002-2V5a2 2 0 00-2-2H8a2 2 0 00-2 2v14a2 2 0 002 2z" />
                            </svg></div>
                    </div>

                    <!-- Mockup Content -->
                    <div
                        class="flex h-[450px] w-full rounded-xl bg-[#0f0f11] border border-[#2a2a2b] overflow-hidden mt-8 relative">
                        <!-- Sidebar -->
                        <div
                            class="w-1/3 max-w-[200px] border-r border-[#2a2a2b] bg-[#141416] p-4 flex flex-col gap-8 hidden sm:flex">
                            <div class="flex items-center gap-2 mb-2">
                                <span class="text-2xl font-bold text-white tracking-tighter">dmoc<span
                                        class="text-transparent bg-clip-text bg-gradient-to-r from-[#d304f4] to-cyan-400">.</span></span>
                            </div>
                            <div class="space-y-4">
                                <!-- Nav item -->
                                <div class="flex items-center justify-between p-2 rounded-lg bg-[#2a2a2b] cursor-pointer">
                                    <div class="flex items-center gap-3">
                                        <div class="w-6 h-6 rounded-md bg-[#2dd4bf] flex items-center justify-center"><svg
                                                class="w-4 h-4 text-[#141416]" fill="none" viewBox="0 0 24 24"
                                                stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                                            </svg></div>
                                        <span class="text-xs font-semibold text-white">Home Page</span>
                                    </div>
                                    <svg class="w-3 h-3 text-[#c4b5d6]" fill="none" viewBox="0 0 24 24"
                                        stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M9 5l7 7-7 7" />
                                    </svg>
                                </div>
                                <!-- Inactive items -->
                                <div
                                    class="flex items-center justify-between p-2 hover:bg-[#2a2a2b]/50 rounded-lg cursor-pointer transition-colors">
                                    <div class="flex items-center gap-3">
                                        <div
                                            class="w-6 h-6 rounded-md border border-[#2a2a2b] flex items-center justify-center text-[#c4b5d6]">
                                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" />
                                            </svg>
                                        </div>
                                        <span class="text-xs font-medium text-[#c4b5d6]">Product Page</span>
                                    </div>
                                    <svg class="w-3 h-3 text-[#c4b5d6]" fill="none" viewBox="0 0 24 24"
                                        stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M9 5l7 7-7 7" />
                                    </svg>
                                </div>
                                <div
                                    class="flex items-center justify-between p-2 hover:bg-[#2a2a2b]/50 rounded-lg cursor-pointer transition-colors">
                                    <div class="flex items-center gap-3">
                                        <div
                                            class="w-6 h-6 rounded-md border border-[#2a2a2b] flex items-center justify-center text-[#c4b5d6]">
                                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z" />
                                            </svg>
                                        </div>
                                        <span class="text-xs font-medium text-[#c4b5d6]">Collection Page</span>
                                    </div>
                                    <svg class="w-3 h-3 text-[#c4b5d6]" fill="none" viewBox="0 0 24 24"
                                        stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M9 5l7 7-7 7" />
                                    </svg>
                                </div>
                                <div
                                    class="flex items-center justify-between p-2 hover:bg-[#2a2a2b]/50 rounded-lg cursor-pointer transition-colors">
                                    <div class="flex items-center gap-3">
                                        <div
                                            class="w-6 h-6 rounded-md border border-[#2a2a2b] flex items-center justify-center text-[#c4b5d6]">
                                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9.5a2.5 2.5 0 00-2.5-2.5H15M9 11l3 3L22 4" />
                                            </svg>
                                        </div>
                                        <span class="text-xs font-medium text-[#c4b5d6]">Blog</span>
                                    </div>
                                    <svg class="w-3 h-3 text-[#c4b5d6]" fill="none" viewBox="0 0 24 24"
                                        stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M9 5l7 7-7 7" />
                                    </svg>
                                </div>
                            </div>
                        </div>

                        <!-- Main App Area -->
                        <div class="flex-1 bg-white relative flex flex-col">
                            <!-- Top header of mockup -->
                            <div
                                class="h-10 border-b border-gray-100 flex items-center justify-between px-6 bg-white w-full">
                                <div class="font-bold text-gray-800 text-lg">dmoc.</div>
                                <!-- Mock nav links -->
                                <div class="hidden md:flex gap-4 text-[9px] text-gray-500 font-medium tracking-wide">
                                    <span class="text-black font-bold border-b border-black">Demo</span>
                                    <span>Shop</span>
                                    <span class="relative">Product<span
                                            class="absolute -top-1 -right-4 text-[6px] bg-[#2dd4bf] text-white px-1 rounded-sm">Hot</span></span>
                                    <span>Sale</span>
                                    <span>Pages</span>
                                </div>
                                <!-- Icons -->
                                <div class="flex gap-2">
                                    <svg class="w-3.5 h-3.5 text-gray-600" fill="none" viewBox="0 0 24 24"
                                        stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                                    </svg>
                                    <svg class="w-3.5 h-3.5 text-gray-600" fill="none" viewBox="0 0 24 24"
                                        stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                    </svg>
                                    <div class="relative">
                                        <svg class="w-3.5 h-3.5 text-gray-600" fill="none" viewBox="0 0 24 24"
                                            stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z" />
                                        </svg>
                                        <span
                                            class="absolute -top-1 -right-1 w-2.5 h-2.5 bg-black rounded-full text-white text-[6px] flex items-center justify-center font-bold">0</span>
                                    </div>
                                </div>
                            </div>

                            <!-- Content area -->
                            <div class="flex-1 p-6 relative overflow-hidden bg-gray-50 flex items-center">
                                <!-- Circular decor -->
                                <div
                                    class="absolute right-0 bottom-0 w-64 h-64 bg-pink-100 rounded-full translate-x-1/3 translate-y-1/3 blur-3xl opacity-60">
                                </div>
                                <div
                                    class="absolute left-0 top-10 w-48 h-48 bg-cyan-50 rounded-full -translate-x-1/2 blur-2xl opacity-60">
                                </div>

                                <div class="z-10 -mt-10">
                                    <div class="text-[8px] text-gray-500 font-bold tracking-widest uppercase mb-2">Summer
                                        2026</div>
                                    <div class="text-4xl font-black text-gray-900 leading-none mb-4">New
                                        Arrival<br />Collection</div>
                                    <button
                                        class="px-5 py-2 bg-gray-900 text-white text-[9px] font-bold mt-2 hover:bg-black transition-colors">Explore
                                        now</button>
                                </div>

                                <!-- Model image placeholder -->
                                <div class="absolute bottom-0 right-4 w-1/2 h-[85%]">
                                    <div
                                        class="w-full h-full bg-gradient-to-t from-[#e5e5e5] to-[#f5f5f5] rounded-tl-[100px] rounded-tr-[100px] shadow-lg relative overflow-hidden flex items-end justify-center border-4 border-white">
                                        <!-- Cycling Images -->
                                        <div id="hero-slider" class="relative w-full h-full">
                                            <img src="{{ asset('hero_png/1.png') }}"
                                                class="hero-slide absolute inset-0 w-full h-full object-contain p-4 transition-opacity duration-1000 opacity-100"
                                                alt="Produit 1">
                                            <img src="{{ asset('hero_png/2.png') }}"
                                                class="hero-slide absolute inset-0 w-full h-full object-contain p-4 transition-opacity duration-1000 opacity-0"
                                                alt="Produit 2">
                                            <img src="{{ asset('hero_png/3.png') }}"
                                                class="hero-slide absolute inset-0 w-full h-full object-contain p-4 transition-opacity duration-1000 opacity-0"
                                                alt="Produit 3">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Chat Bubble Absolute -->
                <div
                    class="absolute -bottom-4 right-2 w-12 h-12 bg-[#0ea5e9] rounded-full flex items-center justify-center shadow-[0_0_15px_rgba(14,165,233,0.5)] cursor-pointer hover:bg-[#0284c7] transition-colors z-30">
                    <svg class="w-6 h-6 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z" />
                    </svg>
                </div>
            </div>
        </div>
    </section>

    {{-- SECTION START CATEGORIES --}}
    <div class="mb-12 flex items-center justify-between">
        <h2 class="text-3xl font-black text-white">Explorez nos Catégories</h2>
        <a href="{{ route('client.catalog') }}"
            class="text-[#d304f4] font-medium hover:text-white transition-colors flex items-center gap-1">
            Voir tout <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24"
                stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
            </svg>
        </a>
    </div>

    <section class='grid grid-cols-2 md:grid-cols-3 xl:grid-cols-6 gap-4 mb-20'>
        <a href='{{ route('client.catalog') }}'
            class='group relative overflow-hidden rounded-2xl border border-[#5a2080]/60 bg-[#1a0035] p-6 text-center hover:border-[#d304f4] transition-all duration-300 hover:shadow-[0_0_20px_rgba(211,4,244,0.2)] hover:-translate-y-1'>
            <div
                class="absolute inset-0 bg-gradient-to-br from-[#3f047b]/20 to-transparent opacity-0 group-hover:opacity-100 transition-opacity">
            </div>
            <div class="text-4xl mb-3 transform group-hover:scale-110 transition-transform duration-300">📱</div>
            <span class="font-bold text-white block">Electronique</span>
        </a>
        <a href='{{ route('client.catalog') }}'
            class='group relative overflow-hidden rounded-2xl border border-[#5a2080]/60 bg-[#1a0035] p-6 text-center hover:border-[#d304f4] transition-all duration-300 hover:shadow-[0_0_20px_rgba(211,4,244,0.2)] hover:-translate-y-1'>
            <div
                class="absolute inset-0 bg-gradient-to-br from-[#3f047b]/20 to-transparent opacity-0 group-hover:opacity-100 transition-opacity">
            </div>
            <div class="text-4xl mb-3 transform group-hover:scale-110 transition-transform duration-300">👕</div>
            <span class="font-bold text-white block">Mode</span>
        </a>
        <a href='{{ route('client.catalog') }}'
            class='group relative overflow-hidden rounded-2xl border border-[#5a2080]/60 bg-[#1a0035] p-6 text-center hover:border-[#d304f4] transition-all duration-300 hover:shadow-[0_0_20px_rgba(211,4,244,0.2)] hover:-translate-y-1'>
            <div
                class="absolute inset-0 bg-gradient-to-br from-[#3f047b]/20 to-transparent opacity-0 group-hover:opacity-100 transition-opacity">
            </div>
            <div class="text-4xl mb-3 transform group-hover:scale-110 transition-transform duration-300">🏠</div>
            <span class="font-bold text-white block">Maison</span>
        </a>
        <a href='{{ route('client.catalog') }}'
            class='group relative overflow-hidden rounded-2xl border border-[#5a2080]/60 bg-[#1a0035] p-6 text-center hover:border-[#d304f4] transition-all duration-300 hover:shadow-[0_0_20px_rgba(211,4,244,0.2)] hover:-translate-y-1'>
            <div
                class="absolute inset-0 bg-gradient-to-br from-[#3f047b]/20 to-transparent opacity-0 group-hover:opacity-100 transition-opacity">
            </div>
            <div class="text-4xl mb-3 transform group-hover:scale-110 transition-transform duration-300">⚽</div>
            <span class="font-bold text-white block">Sport</span>
        </a>
        <a href='{{ route('client.catalog') }}'
            class='group relative overflow-hidden rounded-2xl border border-[#5a2080]/60 bg-[#1a0035] p-6 text-center hover:border-[#d304f4] transition-all duration-300 hover:shadow-[0_0_20px_rgba(211,4,244,0.2)] hover:-translate-y-1'>
            <div
                class="absolute inset-0 bg-gradient-to-br from-[#3f047b]/20 to-transparent opacity-0 group-hover:opacity-100 transition-opacity">
            </div>
            <div class="text-4xl mb-3 transform group-hover:scale-110 transition-transform duration-300">💄</div>
            <span class="font-bold text-white block">Beaute</span>
        </a>
        <a href='{{ route('client.catalog') }}'
            class='group relative overflow-hidden rounded-2xl border border-[#5a2080]/60 bg-[#1a0035] p-6 text-center hover:border-[#d304f4] transition-all duration-300 hover:shadow-[0_0_20px_rgba(211,4,244,0.2)] hover:-translate-y-1'>
            <div
                class="absolute inset-0 bg-gradient-to-br from-[#3f047b]/20 to-transparent opacity-0 group-hover:opacity-100 transition-opacity">
            </div>
            <div class="text-4xl mb-3 transform group-hover:scale-110 transition-transform duration-300">📚</div>
            <span class="font-bold text-white block">Livres</span>
        </a>
    </section>

    {{-- SELECTION TENDANCE --}}
    <div class="mb-12 flex items-center justify-between">
        <h2 class="text-3xl font-black text-white">Nouveautés</h2>
        <div class="flex gap-2">
            <button
                class="w-10 h-10 rounded-full border border-[#5a2080] flex items-center justify-center hover:bg-[#3f047b] transition-colors"><svg
                    xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24"
                    stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                </svg></button>
            <button
                class="w-10 h-10 rounded-full border border-[#5a2080] flex items-center justify-center hover:bg-[#3f047b] transition-colors"><svg
                    xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24"
                    stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                </svg></button>
        </div>
    </div>

    <section class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-16">
        <!-- Placeholder cards for trendy products as part of nice layout -->
        @for ($i = 1; $i <= 4; $i++)
            <div
                class="group rounded-2xl bg-[#1a0035] border border-[#5a2080]/50 overflow-hidden hover:border-[#d304f4] transition-all duration-300 hover:shadow-lg hover:shadow-[#d304f4]/10">
                <div class="relative h-72 bg-[#2a0550] flex items-center justify-center overflow-hidden">
                    <div class="text-8xl group-hover:scale-110 transition-transform duration-500">🛍️</div>
                    @if ($i == 1)
                        <span
                            class="absolute top-4 left-4 bg-gradient-to-r from-[#d304f4] to-[#a855f7] text-white text-[10px] font-bold px-3 py-1 rounded-full uppercase tracking-wider">NEW</span>
                    @endif
                    @if ($i == 2)
                        <span
                            class="absolute top-4 left-4 bg-cyan-500 text-white text-[10px] font-bold px-3 py-1 rounded-full uppercase tracking-wider">-30%</span>
                    @endif

                    <!-- Quick actions on hover -->
                    <div
                        class="absolute bottom-4 left-0 right-0 flex justify-center gap-2 translate-y-12 opacity-0 group-hover:translate-y-0 group-hover:opacity-100 transition-all duration-300">
                        <button
                            class="bg-white text-[#1a0035] w-10 h-10 rounded-full flex items-center justify-center hover:bg-[#d304f4] hover:text-white shadow-lg transition-colors">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24"
                                stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z" />
                            </svg>
                        </button>
                        <button
                            class="bg-white text-[#1a0035] w-10 h-10 rounded-full flex items-center justify-center hover:bg-red-500 hover:text-white shadow-lg transition-colors">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24"
                                stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" />
                            </svg>
                        </button>
                    </div>
                </div>
                <div class="p-5">
                    <h3 class="font-bold text-lg mb-1 group-hover:text-[#d304f4] transition-colors"><a
                            href="{{ route('client.product') }}">Produit Premium {{ $i }}</a></h3>
                    <p class="text-[#c4b5d6] text-sm mb-3">Catégorie</p>
                    <div class="flex items-center gap-2">
                        @if ($i == 2)
                            <span class="text-[#c4b5d6] line-through text-sm">29,000 FCFA</span>
                        @endif
                        <span class="text-[#d304f4] font-black text-xl">{{ 19000 + $i * 1000 }} FCFA</span>
                    </div>
                </div>
            </div>
        @endfor
    </section>

    {{-- SECTION SERVICE LIVRAISON --}}
    <section
        class="mb-24 relative overflow-hidden rounded-3xl bg-gradient-to-br from-[#1a0035] to-[#0a0015] border border-[#2a0550] p-8 md:p-16">
        <div class="absolute top-0 right-0 w-64 h-64 bg-[#d304f4]/10 blur-[100px] rounded-full pointer-events-none"></div>
        <div class="relative z-10 grid lg:grid-cols-2 gap-12 items-center">
            <div class="space-y-6">
                <h2 class="text-4xl font-black text-white leading-tight">La vitesse est notre <span
                        class="text-[#d304f4]">priorité</span></h2>
                <p class="text-[#c4b5d6] text-lg leading-relaxed">
                    Notre réseau de livreurs experts s'assure que vos colis arrivent à destination en un temps record.
                    Suivez votre commande en temps réel et profitez d'une tranquillité d'esprit totale.
                </p>
                <ul class="space-y-4">
                    <li class="flex items-center gap-3 text-white font-medium">
                        <div class="w-6 h-6 rounded-full bg-[#d304f4]/20 flex items-center justify-center text-[#d304f4]">
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7" />
                            </svg>
                        </div>
                        Livraison express en moins de 24h
                    </li>
                    <li class="flex items-center gap-3 text-white font-medium">
                        <div class="w-6 h-6 rounded-full bg-[#d304f4]/20 flex items-center justify-center text-[#d304f4]">
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7" />
                            </svg>
                        </div>
                        Suivi GPS ultra-précis
                    </li>
                    <li class="flex items-center gap-3 text-white font-medium">
                        <div class="w-6 h-6 rounded-full bg-[#d304f4]/20 flex items-center justify-center text-[#d304f4]">
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7" />
                            </svg>
                        </div>
                        Paiement sécurisé à la livraison
                    </li>
                </ul>
                <div class="pt-4">
                    <a href="{{ route('client.tracking') }}"
                        class="inline-block bg-[#d304f4] hover:bg-[#a803c4] text-white font-bold px-8 py-4 rounded-xl transition-all shadow-lg shadow-[#d304f4]/20">Suivre
                        mon colis</a>
                </div>
            </div>
            <div class="relative flex items-center justify-center">
                <div
                    class="aspect-square w-full max-w-[400px] bg-gradient-to-tr from-[#2a0550] to-transparent rounded-full flex items-center justify-center border border-[#5a2080]/30 shadow-2xl relative">
                    <img src="{{ asset('hero_png/4.png') }}" class="w-3/4 h-3/4 object-contain" alt="Livreur">
                </div>
                <div
                    class="absolute -bottom-6 -left-6 bg-[#0a0015] border border-[#2a0550] p-4 rounded-2xl shadow-xl backdrop-blur-xl animate-pulse">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-full bg-green-500/20 flex items-center justify-center text-green-500">
                            ⚡</div>
                        <div>
                            <div class="text-xs text-[#c4b5d6] uppercase font-bold tracking-widest">Temps moyen</div>
                            <div class="text-white font-black">45 minutes</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- SECTION AVIS & ENTREPRISE --}}
    <section class="mb-24">
        <div class="text-center mb-16 space-y-4">
            <h2 class="text-4xl font-black text-white">Ce que nos clients disent</h2>
            <div class="flex items-center justify-center gap-2">
                <div class="flex text-yellow-500">
                    @for ($i = 0; $i < 5; $i++)
                        <svg class="w-6 h-6 fill-current" viewBox="0 0 20 20">
                            <path
                                d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                        </svg>
                    @endfor
                </div>
                <span class="text-white font-bold text-xl">4.9 / 5</span>
                <span class="text-[#c4b5d6]">(+10k avis vérifiés)</span>
            </div>
        </div>

        <div class="grid md:grid-cols-3 gap-8">
            <!-- Review 1 -->
            <div
                class="bg-[#1a0035] border border-[#2a0550] p-8 rounded-3xl space-y-4 relative group hover:border-[#d304f4] transition-all">
                <div class="text-4xl absolute -top-4 -right-4 bg-[#1a0035] p-2 rounded-full border border-[#2a0550]">💬
                </div>
                <p class="text-[#c4b5d6] italic">"Service incroyable. J'ai commandé mon iPhone le matin, reçu avant le
                    déjeuner. DMOC est devenu mon incontournable."</p>
                <div class="flex items-center gap-4 pt-4">
                    <div class="w-12 h-12 rounded-full bg-[#d304f4] flex items-center justify-center font-bold text-white">
                        MD</div>
                    <div>
                        <div class="text-white font-bold">Moussa Diop</div>
                        <div class="text-[#d304f4] text-xs font-bold uppercase tracking-widest">Client Fidèle</div>
                    </div>
                </div>
            </div>
            <!-- Review 2 -->
            <div
                class="bg-[#1a0035] border border-[#2a0550] p-8 rounded-3xl space-y-4 relative group hover:border-[#d304f4] transition-all">
                <div class="text-4xl absolute -top-4 -right-4 bg-[#1a0035] p-2 rounded-full border border-[#2a0550]">✨</div>
                <p class="text-[#c4b5d6] italic">"La qualité des produits est toujours premium. Le design du site est hyper
                    fluide, on voit que c'est du sérieux."</p>
                <div class="flex items-center gap-4 pt-4">
                    <div class="w-12 h-12 rounded-full bg-[#3f047b] flex items-center justify-center font-bold text-white">
                        FS</div>
                    <div>
                        <div class="text-white font-bold">Fatou Sow</div>
                        <div class="text-[#d304f4] text-xs font-bold uppercase tracking-widest">Acheteuse vérifiée</div>
                    </div>
                </div>
            </div>
            <!-- Review 3 -->
            <div
                class="bg-[#1a0035] border border-[#2a0550] p-8 rounded-3xl space-y-4 relative group hover:border-[#d304f4] transition-all">
                <div class="text-4xl absolute -top-4 -right-4 bg-[#1a0035] p-2 rounded-full border border-[#2a0550]">🚀
                </div>
                <p class="text-[#c4b5d6] italic">"Je recommande DMOC à tous mes amis. Le support client est réactif et les
                    prix sont très compétitifs."</p>
                <div class="flex items-center gap-4 pt-4">
                    <div class="w-12 h-12 rounded-full bg-cyan-500 flex items-center justify-center font-bold text-white">AB
                    </div>
                    <div>
                        <div class="text-white font-bold">Amadou Ba</div>
                        <div class="text-[#d304f4] text-xs font-bold uppercase tracking-widest">Digital Nomad</div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Animation Script -->
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const slides = document.querySelectorAll('.hero-slide');
            let currentSlide = 0;

            if (slides.length > 0) {
                setInterval(() => {
                    // Hide current
                    slides[currentSlide].classList.remove('opacity-100');
                    slides[currentSlide].classList.add('opacity-0');

                    // Next index
                    currentSlide = (currentSlide + 1) % slides.length;

                    // Show next
                    slides[currentSlide].classList.remove('opacity-0');
                    slides[currentSlide].classList.add('opacity-100');
                }, 3000); // 3 seconds interval
            }
        });
    </script>

@endsection