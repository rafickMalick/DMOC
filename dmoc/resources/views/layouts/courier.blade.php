<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>DMOC - @yield('title', 'Livreur')</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        primary: '#3f047b',
                        accent: '#d304f4',
                        surface: '#1a0035',
                        input: '#2a0550',
                        border: '#5a2080',
                    }
                }
            }
        }
    </script>
</head>

<body class="min-h-screen bg-[#120024] text-white font-sans antialiased">
    <div class="fixed inset-0 bg-[radial-gradient(circle_at_top_left,rgba(56,189,248,0.10),transparent_40%)] pointer-events-none"></div>

    <nav class="sticky top-0 z-40 bg-[#0d001c]/90 backdrop-blur-md border-b border-[#5a2080]/60">
        <div class="mx-auto max-w-7xl flex flex-wrap items-center justify-between gap-3 px-4 py-4">
            <div>
                <a href="{{ route('courier.list') }}" class="text-2xl font-black tracking-tight">DMOC</a>
                <p class="text-xs text-[#c4b5d6]">Courier Workspace</p>
            </div>

            <div class="flex gap-2">
                <a href="{{ route('courier.list') }}" class="rounded-xl border border-[#5a2080] px-4 py-2 text-sm hover:border-[#d304f4] hover:text-[#d304f4] transition-colors">Mes livraisons</a>
                <a href="{{ route('client.auth') }}" class="rounded-xl border border-[#5a2080] px-4 py-2 text-sm hover:border-red-400 hover:text-red-300 transition-colors">Deconnexion</a>
            </div>

            <x-badge type="success">En service</x-badge>
        </div>
    </nav>

    <main class="mx-auto max-w-7xl px-4 py-8 relative z-10">
        @yield('content')
    </main>
</body>

</html>
