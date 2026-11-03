<?php

namespace Weboldalnet\CommerceUsers\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Weboldalnet\CommerceUsers\CommerceUsers;

/**
 * A fiókoldal megfelelője a többi nyelven — a core nyelvválasztójának és hreflang-sorainak.
 *
 * A core LocaleUrlService csak a saját oldalait ismeri; csomag-oldalon a nyelvválasztó a másik nyelv főoldalára vinne.
 * Amíg a core-ban nincs erre bővítési pont, a modul a core kérésen belüli gyorsítótárába (LocaleUrlService::REQUEST_KEY)
 * előre beírja a címeket. Ha a core ezt a kulcsot megváltoztatja, a működés csendben a régire esik vissza.
 */
class SetLocaleAlternates
{
    /** A core LocaleUrlService::REQUEST_KEY értéke */
    private const CORE_REQUEST_KEY = 'wn_locale_alternates';

    /** Aláírt vagy tokenes címek: ezeknek nincs nyelvi párja a nyelvválasztóban */
    private const SKIP = ['commerce-users.verify', 'commerce-users.password.reset'];

    public function handle(Request $request, Closure $next)
    {
        $locales = CommerceUsers::locales();
        $route = $request->route();
        $name = $route ? $route->getName() : null;

        if (count($locales) > 1 && $name && $request->isMethod('GET') && !$request->attributes->has(self::CORE_REQUEST_KEY)) {
            $base = self::baseName($name, $locales);
            if (!in_array($base, self::SKIP, true)) {
                $alternates = [];
                foreach ($locales as $locale) {
                    $target = CommerceUsers::routeName($base, $locale);
                    if (!Route::has($target)) {
                        continue;
                    }
                    try {
                        $alternates[$locale] = route($target, $route->parameters(), false);
                    } catch (\Throwable $e) {
                        // hiányzó paraméter: ennek a nyelvnek nincs párja — a nyelvválasztó a nyelv főoldalára visz
                    }
                }
                if ($alternates) {
                    $request->attributes->set(self::CORE_REQUEST_KEY, $alternates);
                }
            }
        }

        return $next($request);
    }

    /** commerce-users.en.login → commerce-users.login */
    private static function baseName(string $name, array $locales): string
    {
        $parts = explode('.', $name);
        if (count($parts) > 2 && in_array($parts[1], $locales, true)) {
            array_splice($parts, 1, 1);
        }

        return implode('.', $parts);
    }
}
