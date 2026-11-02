<?php

namespace Weboldalnet\CommerceUsers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use Weboldalnet\CommerceUsers\Models\CommerceUser;

/**
 * A modul belépési pontja a befogadó rendszer (pl. webshop-ai-default) felé.
 *
 * - Funkciókapcsolók: a befogadó a resolveFeaturesUsing()-gal adja át, honnan olvassa őket
 *   (webshop: Webshop beállítások). Befogadó nélkül a config `enabled` értéke számít.
 * - Fiókmenü: a befogadó saját menüpontot tehet a fiókoldal navigációjába
 *   (webshop: „Rendeléseim”), a modulnak ehhez nem kell ismernie a rendeléseket.
 * - Linkek: az adatvédelmi tájékoztató / ÁSZF címét a befogadó adja meg (resolveLinksUsing).
 */
class CommerceUsers
{
    /** A vásárlói fiók guardja, providere és jelszó-brokere — a `web` és az `admin` guardtól független. */
    public const GUARD = 'commerce_user';
    public const PROVIDER = 'commerce_users';
    public const BROKER = 'commerce_users';

    /** Funkciók és az alapértékük, ha a befogadó nem ad meg resolvert. */
    public const FEATURES = [
        'enabled' => false,
        'addresses' => true,
    ];

    /** @var callable|null */
    protected static $featureResolver = null;

    /** @var callable|null */
    protected static $linkResolver = null;

    /** @var array<string, array> */
    protected static array $menuItems = [];

    public static function resolveFeaturesUsing(callable $resolver): void
    {
        static::$featureResolver = $resolver;
    }

    public static function resolveLinksUsing(callable $resolver): void
    {
        static::$linkResolver = $resolver;
    }

    /** Be van-e kapcsolva a modul (regisztráció, bejelentkezés, fiókoldal). */
    public static function enabled(): bool
    {
        return static::feature('enabled');
    }

    public static function feature(string $name): bool
    {
        if ($name !== 'enabled' && !static::feature('enabled')) {
            return false;
        }

        if (static::$featureResolver) {
            $value = call_user_func(static::$featureResolver, $name);
            if ($value !== null) {
                return (bool) $value;
            }
        }

        if ($name === 'enabled') {
            return (bool) config('commerce-users.enabled', false);
        }

        return (bool) (static::FEATURES[$name] ?? false);
    }

    /** A befogadó által megadott link (pl. 'privacy', 'terms'), vagy null. */
    public static function link(string $name): ?string
    {
        if (!static::$linkResolver) {
            return null;
        }

        $url = call_user_func(static::$linkResolver, $name);

        return $url ?: null;
    }

    /** A bejelentkezett vásárló (vagy null). Kikapcsolt modulnál mindig null. */
    public static function user(): ?CommerceUser
    {
        if (!static::enabled()) {
            return null;
        }

        $user = Auth::guard(static::GUARD)->user();

        return $user instanceof CommerceUser ? $user : null;
    }

    /**
     * Menüpont a fiókoldal navigációjába.
     *
     * @param  string         $key      egyedi kulcs (ugyanazzal felülírható)
     * @param  string         $label    felirat
     * @param  string         $icon     Font Awesome osztály, pl. 'fa-box'
     * @param  string         $route    route-név
     * @param  int            $order    sorrend (a beépítettek: profil 10, címek 30, jelszó 40)
     * @param  callable|null  $visible  láthatóság (pl. a webshop kapcsolója)
     * @param  array          $activeRoutes  ezekre a route-nevekre (* helyettesítővel) is aktív
     */
    public static function addAccountMenuItem(string $key, string $label, string $icon, string $route, int $order = 50, ?callable $visible = null, array $activeRoutes = []): void
    {
        static::$menuItems[$key] = compact('key', 'label', 'icon', 'route', 'order', 'visible', 'activeRoutes');
    }

    /** A fiókmenü látható pontjai sorrendben (a beépítettekkel együtt). */
    public static function accountMenu(): array
    {
        $items = array_merge([
            'profile' => ['key' => 'profile', 'label' => 'Profilom', 'icon' => 'fa-user', 'route' => 'commerce-users.account',
                'order' => 10, 'visible' => null, 'activeRoutes' => ['commerce-users.account']],
            'addresses' => ['key' => 'addresses', 'label' => 'Címeim', 'icon' => 'fa-location-dot', 'route' => 'commerce-users.addresses.index',
                'order' => 30, 'visible' => fn () => static::feature('addresses'), 'activeRoutes' => ['commerce-users.addresses.*']],
            'password' => ['key' => 'password', 'label' => 'Jelszócsere', 'icon' => 'fa-key', 'route' => 'commerce-users.password.edit',
                'order' => 40, 'visible' => null, 'activeRoutes' => ['commerce-users.password.edit']],
        ], static::$menuItems);

        $items = array_filter($items, function ($item) {
            return Route::has($item['route']) && (!$item['visible'] || call_user_func($item['visible']));
        });

        uasort($items, fn ($a, $b) => $a['order'] <=> $b['order']);

        return array_values($items);
    }
}
