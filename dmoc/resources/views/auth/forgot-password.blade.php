@extends('layouts.client')
@section('title', 'Mot de passe oublie')

@section('content')
<div class="mx-auto max-w-lg rounded-2xl border border-[#5a2080]/70 bg-[#1a0035]/90 p-6 md:p-8 shadow-2xl shadow-purple-900/30">
    <h1 class="mb-2 text-2xl font-black">Mot de passe oublie</h1>
    <p class="mb-6 text-sm text-[#c4b5d6]">Entre ton email pour recevoir un lien de reinitialisation.</p>

    @if (session('status'))
        <div class="mb-4 rounded border border-emerald-500/40 bg-emerald-500/10 px-3 py-2 text-sm text-emerald-300">
            {{ session('status') }}
        </div>
    @endif

    @if($errors->any())
        <div class="mb-4 rounded border border-red-500/40 bg-red-500/10 px-3 py-2 text-sm text-red-300">
            {{ $errors->first() }}
        </div>
    @endif

    <form method="POST" action="{{ route('password.email') }}" class="space-y-4">
        @csrf
        <div>
            <label class="mb-1 block text-sm">Email</label>
            <input
                type="email"
                name="email"
                value="{{ old('email') }}"
                required
                class="w-full rounded border border-[#5a2080] bg-[#120024] px-3 py-2 text-white"
            />
        </div>
        <button type="submit" class="w-full rounded-xl bg-[#d304f4] px-4 py-2 font-semibold hover:bg-[#b003cc] transition-colors">
            Envoyer le lien
        </button>
    </form>
</div>
@endsection
