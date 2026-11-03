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
     * @param  string         $label    felirat — fordítási kulcs is lehet (pl. 'webshop::account.orders'),
     *                                  megjelenítéskor fordul, így a kérés nyelvén jelenik meg
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
            'profile' => ['key' => 'profile', 'label' => 'commerce-users::account.menu.profile', 'icon' => 'fa-user', 'route' => 'commerce-users.account',
                'order' => 10, 'visible' => null, 'activeRoutes' => ['commerce-users.account']],
            'addresses' => ['key' => 'addresses', 'label' => 'commerce-users::account.menu.addresses', 'icon' => 'fa-location-dot', 'route' => 'commerce-users.addresses.index',
                'order' => 30, 'visible' => fn () => static::feature('addresses'), 'activeRoutes' => ['commerce-users.addresses.*']],
            'password' => ['key' => 'password', 'label' => 'commerce-users::account.menu.password', 'icon' => 'fa-key', 'route' => 'commerce-users.password.edit',
                'order' => 40, 'visible' => null, 'activeRoutes' => ['commerce-users.password.edit']],
        ], static::$menuItems);

        $items = array_filter($items, function ($item) {
            return Route::has($item['route']) && (!$item['visible'] || call_user_func($item['visible']));
        });

        uasort($items, fn ($a, $b) => $a['order'] <=> $b['order']);

        // a felirat itt, a kérés nyelvén fordul (a befogadó a boot-ban, még nyelvváltás előtt regisztrál);
        // sima szöveg változatlanul marad. A cím és az aktív-jelölés a kérés nyelvének útvonalára mutat.
        return array_values(array_map(function ($item) {
            $label = __($item['label']);
            $item['label'] = is_string($label) ? $label : $item['label'];
            $item['url'] = static::route($item['route']);
            $item['activeRoutes'] = static::localizedPatterns($item['activeRoutes'] ?: [$item['route']]);

            return $item;
        }, $items));
    }

    // ------------------------------------------------------------------------------------------ nyelvi címek
    //
    // Többnyelvű site-on a fiók-útvonalak nyelvenként élnek (routes/web.php): az alapnyelv a megszokott
    // commerce-users.* néven és /fiok/… címen, a többi nyelv commerce-users.{nyelv}.* néven, /{nyelv}/account/… címen.
    // A linkek a route() helyett a static::route()-tal készülnek, így a kérés nyelvén maradnak.

    public static function defaultLocale(): string
    {
        return defined('App\Helpers\Lang::DEFAULT_LANG') ? \App\Helpers\Lang::DEFAULT_LANG : (string) config('app.locale', 'hu');
    }

    /** A modul nyelvei (az alapnyelv elöl): a core site-nyelvei, egynyelvű projektben csak az alapnyelv */
    public static function locales(): array
    {
        $locales = config('commerce-users.locales');
        if (!is_array($locales) || !$locales) {
            $multi = class_exists(\App\Helpers\Lang::class) && method_exists(\App\Helpers\Lang::class, 'isMultiLanguage')
                && \App\Helpers\Lang::isMultiLanguage() && method_exists(\App\Helpers\Lang::class, 'siteLocales');
            $locales = $multi ? \App\Helpers\Lang::siteLocales() : [];
        }

        return array_values(array_unique(array_merge([static::defaultLocale()], $locales)));
    }

    /** A címek nyelve: a megadott vagy a kérés nyelve, ha az a modul egyik nyelve; különben az alapnyelv */
    public static function urlLocale(?string $locale = null): string
    {
        $locale = $locale ?: app()->getLocale();

        return in_array($locale, static::locales(), true) ? $locale : static::defaultLocale();
    }

    /** Útszó a config('commerce-users.url_words') térképből; az alapnyelv előtagja a route_prefix */
    public static function word(string $key, string $locale): string
    {
        if ($key === 'prefix' && $locale === static::defaultLocale() && config('commerce-users.route_prefix')) {
            return (string) config('commerce-users.route_prefix');
        }

        $words = (array) config('commerce-users.url_words', []);

        return (string) ($words[$locale][$key] ?? $words['en'][$key] ?? $words[static::defaultLocale()][$key] ?? $key);
    }

    /**
     * Egy útvonal neve a nyelven: a név egy szakasza után beszúrt nyelvkód, ha olyan útvonal létezik
     * (commerce-users.login → commerce-users.en.login; a befogadóé is: site.webshop.account.orders →
     * site.webshop.en.account.orders). Alapnyelven vagy nyelvi változat nélkül az eredeti név.
     */
    public static function routeName(string $name, ?string $locale = null): string
    {
        $locale = static::urlLocale($locale);
        if ($locale === static::defaultLocale()) {
            return $name;
        }

        foreach (static::localizedPatterns([$name], $locale) as $candidate) {
            if ($candidate !== $name && Route::has($candidate)) {
                return $candidate;
            }
        }

        return $name;
    }

    /** A route() nyelvkövető párja */
    public static function route(string $name, $parameters = [], bool $absolute = true, ?string $locale = null): string
    {
        return route(static::routeName($name, $locale), $parameters, $absolute);
    }

    /** A minták nyelvi változatai is (a menü aktív-jelöléséhez): az eredeti + minden lehetséges beszúrási hely */
    public static function localizedPatterns(array $patterns, ?string $locale = null): array
    {
        $locale = static::urlLocale($locale);
        $all = $patterns;
        if ($locale !== static::defaultLocale()) {
            foreach ($patterns as $pattern) {
                $parts = explode('.', $pattern);
                for ($i = 1; $i < count($parts); $i++) {
                    $all[] = implode('.', array_merge(array_slice($parts, 0, $i), [$locale], array_slice($parts, $i)));
                }
            }
        }

        return array_values(array_unique($all));
    }
}
