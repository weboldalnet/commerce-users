<?php

namespace Weboldalnet\CommerceUsers\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Weboldalnet\CommerceUsers\CommerceUsers;

/**
 * Csak bejelentkezett vásárlónak. Vendéget a belépő oldalra visz, és a belépés után
 * visszahozza ide (intended URL). Ha a fiókot közben letiltották, kilépteti.
 *
 * Saját middleware, nem az `auth:commerce_user`: a projekt `redirectGuestsTo` beállítása
 * az admin belépőre vinne.
 */
class AuthenticateCommerceUser
{
    public function handle(Request $request, Closure $next)
    {
        $guard = Auth::guard(CommerceUsers::GUARD);
        $user = $guard->user();

        if ($user && (!$user->is_active || !$user->isVerified())) {
            $guard->logout();
            $user = null;
        }

        if (!$user) {
            if ($request->expectsJson()) {
                return response()->json(['message' => 'Bejelentkezés szükséges.'], 401);
            }

            return redirect()->guest(route('commerce-users.login'))
                ->with('info', 'Ehhez az oldalhoz jelentkezz be a fiókodba.');
        }

        return $next($request);
    }
}
