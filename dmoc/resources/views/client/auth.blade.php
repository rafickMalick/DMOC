@extends('layouts.client')
@section('title','Auth')
@section('content')
<div class="mx-auto mb-6 max-w-2xl text-center">
  <h1 class="text-4xl font-black tracking-tight mb-2">Bienvenue sur DMOC</h1>
  <p class="text-[#c4b5d6]">Connecte-toi pour acceder a ton espace et suivre tes commandes.</p>
</div>

<div class='mx-auto max-w-2xl rounded-2xl border border-[#5a2080]/70 bg-[#1a0035]/90 p-6 md:p-8 shadow-2xl shadow-purple-900/30'>
  @if($errors->any())
    <div class="mb-4 rounded border border-red-500/40 bg-red-500/10 px-3 py-2 text-sm text-red-300">
      {{ $errors->first() }}
    </div>
  @endif

  <h2 class="text-2xl font-semibold mb-4">Connexion</h2>
  <form method="POST" action="{{ route('auth.login') }}" class="grid gap-3 md:grid-cols-2">
    @csrf
    <div class="md:col-span-2">
      <label class="mb-1 block text-sm">Email</label>
      <input
        type="email"
        name="email"
        value="{{ old('email') }}"
        required
        class="w-full rounded border border-[#5a2080] bg-[#120024] px-3 py-2 text-white"
      />
    </div>
    <div class="md:col-span-2">
      <label class="mb-1 block text-sm">Mot de passe</label>
      <input
        type="password"
        name="password"
        required
        class="w-full rounded border border-[#5a2080] bg-[#120024] px-3 py-2 text-white"
      />
    </div>
    <label class="flex items-center gap-2 text-sm text-[#c4b5d6] md:col-span-2">
      <input type="checkbox" name="remember" value="1" />
      Se souvenir de moi
    </label>
    <button type="submit" class="w-full rounded-xl bg-[#d304f4] px-4 py-2 font-semibold hover:bg-[#b003cc] transition-colors md:col-span-2">Se connecter</button>
  </form>

  <hr class="my-5 border-[#5a2080]" />

  <h3 class="text-xl font-semibold mb-3">Inscription (client)</h3>
  <form method="POST" action="{{ route('auth.register') }}" class="grid gap-3 md:grid-cols-2">
    @csrf
    <input type="text" name="name" placeholder="Nom complet" required class="w-full rounded border border-[#5a2080] bg-[#120024] px-3 py-2 text-white md:col-span-2" />
    <input type="email" name="email" placeholder="Email" required class="w-full rounded border border-[#5a2080] bg-[#120024] px-3 py-2 text-white" />
    <input type="text" name="phone" placeholder="Telephone (optionnel)" class="w-full rounded border border-[#5a2080] bg-[#120024] px-3 py-2 text-white" />
    <input type="password" name="password" placeholder="Mot de passe" required class="w-full rounded border border-[#5a2080] bg-[#120024] px-3 py-2 text-white" />
    <input type="password" name="password_confirmation" placeholder="Confirmer mot de passe" required class="w-full rounded border border-[#5a2080] bg-[#120024] px-3 py-2 text-white" />
    <button type="submit" class="w-full rounded-xl border border-[#d304f4] px-4 py-2 font-semibold text-[#d304f4] hover:bg-[#d304f4] hover:text-white transition-colors md:col-span-2">
      Creer mon compte
    </button>
  </form>
</div>
@endsection