@extends('layouts.client')
@section('title', 'Support')

@section('content')
<div class="mx-auto max-w-4xl">
    <h1 class="mb-2 text-3xl font-black">Support DMC</h1>
    <p class="mb-8 text-[#c4b5d6]">Besoin d'aide ? Notre equipe te repond rapidement.</p>

    <div class="grid gap-6 md:grid-cols-2">
        <x-card>
            <h2 class="mb-3 text-xl font-semibold">Contact direct</h2>
            <p class="text-sm text-[#c4b5d6]">Email: support@dmc.shop</p>
            <p class="text-sm text-[#c4b5d6]">Telephone: +221 77 123 45 67</p>
            <p class="mt-3 text-sm text-[#c4b5d6]">Horaires: Lun-Sam, 08:00 - 20:00</p>
        </x-card>

        <x-card>
            <h2 class="mb-3 text-xl font-semibold">Suivi commande</h2>
            <p class="mb-4 text-sm text-[#c4b5d6]">Connecte-toi pour voir l'historique et le statut de tes commandes.</p>
            @auth
                <a href="{{ route('client.orders') }}" class="rounded-xl bg-[#d304f4] px-4 py-2 text-sm font-semibold">Voir mes commandes</a>
            @else
                <a href="{{ route('client.auth') }}" class="rounded-xl bg-[#d304f4] px-4 py-2 text-sm font-semibold">Se connecter</a>
            @endauth
        </x-card>
    </div>

    <x-card class="mt-6">
        <h2 class="mb-3 text-xl font-semibold">Questions frequentes</h2>
        <div class="space-y-3 text-sm">
            <p><span class="font-semibold">Comment suivre ma commande ?</span><br><span class="text-[#c4b5d6]">Depuis ton espace client > Mes commandes.</span></p>
            <p><span class="font-semibold">Quels moyens de paiement ?</span><br><span class="text-[#c4b5d6]">Mobile Money, carte bancaire et paiement a la livraison.</span></p>
            <p><span class="font-semibold">Puis-je retourner un article ?</span><br><span class="text-[#c4b5d6]">Oui, selon les conditions de retour DMC.</span></p>
        </div>
    </x-card>
</div>
@endsection
