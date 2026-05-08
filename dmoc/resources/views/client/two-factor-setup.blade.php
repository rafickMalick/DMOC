@extends('layouts.client')
@section('title', 'Configuration 2FA')

@section('content')
<div class="mx-auto max-w-2xl rounded-2xl border border-[#5a2080]/70 bg-[#1a0035]/90 p-6 md:p-8 shadow-2xl shadow-purple-900/30">
    <h1 class="mb-2 text-2xl font-black">Securite 2FA (TOTP)</h1>
    <p class="mb-5 text-sm text-[#c4b5d6]">Scanne la cle avec Google Authenticator, puis confirme avec un code a 6 chiffres.</p>

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

    <div class="mb-6 rounded-xl border border-[#5a2080]/70 bg-[#120024] p-4">
        <p class="mb-2 text-sm text-[#c4b5d6]">Cle secrete :</p>
        <p class="font-mono text-lg tracking-wide">{{ $pendingSecret }}</p>
        <p class="mt-3 text-sm text-[#c4b5d6]">URI OTP (si besoin manuel):</p>
        <p class="mt-1 break-all text-xs text-[#d304f4]">{{ $otpUri }}</p>
    </div>

    @if (!$isEnabled)
        <form method="POST" action="{{ route('auth.2fa.enable') }}" class="space-y-4">
            @csrf
            <div>
                <label class="mb-1 block text-sm">Code de verification</label>
                <input
                    type="text"
                    name="code"
                    inputmode="numeric"
                    pattern="[0-9]{6}"
                    maxlength="6"
                    required
                    class="w-full rounded border border-[#5a2080] bg-[#120024] px-3 py-2 text-white"
                />
            </div>
            <button type="submit" class="w-full rounded-xl bg-[#d304f4] px-4 py-2 font-semibold hover:bg-[#b003cc] transition-colors">
                Activer la double authentification
            </button>
        </form>
    @else
        <form method="POST" action="{{ route('auth.2fa.disable') }}">
            @csrf
            <button type="submit" class="w-full rounded-xl border border-red-400 px-4 py-2 font-semibold text-red-300 hover:bg-red-500/10 transition-colors">
                Desactiver la double authentification
            </button>
        </form>
    @endif
</div>
@endsection
