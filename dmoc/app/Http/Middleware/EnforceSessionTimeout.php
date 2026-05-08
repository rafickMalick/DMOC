<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class EnforceSessionTimeout
{
    public function handle(Request $request, Closure $next): Response
    {
        if (Auth::check()) {
            $timeoutMinutes = (int) env('SESSION_IDLE_TIMEOUT', 60);
            $lastActivity = $request->session()->get('last_activity_at');
            $now = now()->getTimestamp();

            if ($lastActivity && ($now - (int) $lastActivity) > ($timeoutMinutes * 60)) {
                Auth::logout();
                $request->session()->invalidate();
                $request->session()->regenerateToken();

                return redirect()->route('client.auth')
                    ->withErrors(['email' => 'Votre session a expire. Merci de vous reconnecter.']);
            }

            $request->session()->put('last_activity_at', $now);
        }

        return $next($request);
    }
}
