<?php

namespace Weboldalnet\CommerceUsers\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Weboldalnet\CommerceUsers\CommerceUsers;

/** Kikapcsolt modulnál a fiók-útvonalak nem léteznek (404), mintha nem is lennének. */
class EnsureCommerceUsersEnabled
{
    public function handle(Request $request, Closure $next, ?string $feature = null)
    {
        if (!CommerceUsers::enabled() || ($feature && !CommerceUsers::feature($feature))) {
            abort(404);
        }

        return $next($request);
    }
}
