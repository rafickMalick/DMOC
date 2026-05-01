<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureUserHasRole
{
    private const ROLE_ALIASES = [
        'courier' => 'livreur',
        'delivery' => 'livreur',
        'vendor' => 'vendeur',
        'seller' => 'vendeur',
    ];

    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next, string ...$roles): Response
    {
        $user = $request->user();

        if (! $user) {
            abort(Response::HTTP_UNAUTHORIZED, 'Authentication required.');
        }

        $normalizedRoles = array_map(function (string $role): string {
            $role = strtolower($role);

            return self::ROLE_ALIASES[$role] ?? $role;
        }, $roles);

        $userRole = strtolower((string) $user->role);

        if (! in_array($userRole, $normalizedRoles, true)) {
            abort(Response::HTTP_FORBIDDEN, 'You are not authorized to access this resource.');
        }

        return $next($request);
    }
}
