<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Support\TotpService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Validation\Rules\Password as PasswordRule;
use Illuminate\View\View;

class WebAuthController extends Controller
{
    public function __construct(private readonly TotpService $totp)
    {
    }

    public function showAuthForm(): View
    {
        return view('client.auth');
    }

    public function showForgotPasswordForm(): View
    {
        return view('auth.forgot-password');
    }

    public function register(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email'],
            'phone' => ['nullable', 'string', 'max:30'],
            'password' => ['required', 'confirmed', PasswordRule::min(8)],
        ]);

        $user = User::query()->create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'phone' => $validated['phone'] ?? null,
            'password' => Hash::make($validated['password']),
            'role' => 'client',
        ]);

        Auth::login($user);
        $request->session()->regenerate();

        return redirect()->to($this->redirectPathByRole($user->role));
    }

    public function login(Request $request): RedirectResponse
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required', 'string'],
        ]);

        $remember = $request->boolean('remember');

        if (! Auth::attempt($credentials, $remember)) {
            return back()
                ->withErrors(['email' => 'Identifiants invalides.'])
                ->onlyInput('email');
        }

        $user = $request->user();

        if ($user && $user->two_factor_secret && $user->two_factor_confirmed_at) {
            Auth::logout();
            $request->session()->put('2fa:user:id', $user->id);
            $request->session()->put('2fa:remember', $remember);

            return redirect()->route('auth.2fa.challenge');
        }

        $request->session()->regenerate();

        return redirect()->to($this->redirectPathByRole((string) $user?->role));
    }

    public function logout(Request $request): RedirectResponse
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('client.auth');
    }

    public function showTwoFactorChallenge(): View|RedirectResponse
    {
        if (! session()->has('2fa:user:id')) {
            return redirect()->route('client.auth');
        }

        return view('auth.two-factor-challenge');
    }

    public function verifyTwoFactorChallenge(Request $request): RedirectResponse
    {
        $request->validate([
            'code' => ['required', 'digits:6'],
        ]);

        $userId = (int) $request->session()->get('2fa:user:id');
        $remember = (bool) $request->session()->get('2fa:remember', false);
        $user = User::query()->find($userId);

        if (! $user || ! $user->two_factor_secret || ! $this->totp->verify($user->two_factor_secret, (string) $request->input('code'))) {
            return back()->withErrors(['code' => 'Code TOTP invalide.'])->onlyInput('code');
        }

        $request->session()->forget(['2fa:user:id', '2fa:remember']);
        Auth::login($user, $remember);
        $request->session()->regenerate();

        return redirect()->to($this->redirectPathByRole((string) $user->role));
    }

    public function showTwoFactorSetup(Request $request): View
    {
        $user = $request->user();
        $pendingSecret = (string) $request->session()->get('2fa:pending_secret', '');

        if ($pendingSecret === '') {
            $pendingSecret = $this->totp->generateSecret();
            $request->session()->put('2fa:pending_secret', $pendingSecret);
        }

        $otpUri = $this->totp->provisioningUri($user->email, $pendingSecret);

        return view('client.two-factor-setup', [
            'pendingSecret' => $pendingSecret,
            'otpUri' => $otpUri,
            'isEnabled' => (bool) ($user->two_factor_secret && $user->two_factor_confirmed_at),
        ]);
    }

    public function enableTwoFactor(Request $request): RedirectResponse
    {
        $request->validate([
            'code' => ['required', 'digits:6'],
        ]);

        $secret = (string) $request->session()->get('2fa:pending_secret', '');

        if ($secret === '' || ! $this->totp->verify($secret, (string) $request->input('code'))) {
            return back()->withErrors(['code' => 'Code invalide. Verifie ton application Authenticator.']);
        }

        $user = $request->user();
        $user->forceFill([
            'two_factor_secret' => $secret,
            'two_factor_confirmed_at' => now(),
        ])->save();

        $request->session()->forget('2fa:pending_secret');

        return redirect()->route('auth.2fa.setup')->with('status', '2FA activee avec succes.');
    }

    public function disableTwoFactor(Request $request): RedirectResponse
    {
        $request->user()->forceFill([
            'two_factor_secret' => null,
            'two_factor_confirmed_at' => null,
        ])->save();

        $request->session()->forget('2fa:pending_secret');

        return redirect()->route('auth.2fa.setup')->with('status', '2FA desactivee.');
    }

    public function sendResetLink(Request $request): RedirectResponse
    {
        $request->validate([
            'email' => ['required', 'email'],
        ]);

        $status = Password::sendResetLink($request->only('email'));

        if ($status === Password::RESET_LINK_SENT) {
            return back()->with('status', __($status));
        }

        return back()->withErrors(['email' => __($status)]);
    }

    private function redirectPathByRole(string $role): string
    {
        return match ($role) {
            User::ROLE_COURIER => route('courier.list'),
            User::ROLE_ADMIN, User::ROLE_SUPERADMIN => route('admin.orders'),
            default => route('client.dashboard'),
        };
    }
}
