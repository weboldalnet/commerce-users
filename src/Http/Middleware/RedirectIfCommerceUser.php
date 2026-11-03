<?php

namespace Weboldalnet\CommerceUsers\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Weboldalnet\CommerceUsers\CommerceUsers;

/** A belépő, regisztrációs és elfelejtett jelszó oldal bejelentkezve a fiókoldalra visz. */
class RedirectIfCommerceUser
{
    public function handle(Request $request, Closure $next)
    {
        if (Auth::guard(CommerceUsers::GUARD)->check()) {
            return redirect()->to(CommerceUsers::route('commerce-users.account'));
        }

        return $next($request);
    }
}
