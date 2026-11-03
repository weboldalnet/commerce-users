<?php

namespace Weboldalnet\CommerceUsers\Support;

class PackageHelper
{
    const PACKAGE_NAME = 'Webshop felhasználó modul';
    const PACKAGE_PREFIX = 'commerce-users';

    const PACKAGE_LIST = [
        'routes' => [
            'name' => 'routes | routes/web.php',
            'source' => __DIR__.'/../../routes/web.php',
            'destination' => '/routes/commerce-users.php',
        ],
        'settings' => [
            'name' => 'settings | settings/',
            'source' => __DIR__.'/../../settings',
            'destination' => '/settings/commerce-users',
        ],
        'config' => [
            'name' => 'config | config/commerce-users.php',
            'source' => __DIR__.'/../../config/commerce-users.php',
            'destination' => '/config/commerce-users.php',
        ],
        // a fiókoldalak stíluslapja — telepítéskor és frissítéskor ki kell rakni
        'assets' => [
            'name' => 'assets | public/packages/commerce-users',
            'source' => __DIR__.'/../../public/packages/commerce-users',
            'destination' => '/public/packages/commerce-users',
        ],
    ];

    /**
     * A nézetek SZÁNDÉKOSAN nincsenek a fenti listában (és így az `-all` tagben sem): egy
     * kirakott nézet minden későbbi csomagfrissítést elfedne. Egyedi átalakításhoz külön
     * kérhető: `vendor:publish --tag=commerce-users-views`, majd csak a módosított fájl maradjon.
     */
    const VIEWS_SOURCE = __DIR__.'/../../resources/views';

    /**
     * A nyelvi fájlok sincsenek az `-all` tagben (a kirakott szöveg elfedné a csomag javításait).
     * Egyedi szöveghez: `vendor:publish --tag=commerce-users-lang`, és csak a módosított kulcsok maradjanak.
     */
    const LANG_SOURCE = __DIR__.'/../../resources/lang';

    const PACKAGE_VIEW_EXTENDS = [
        'sidebar' => [
            'view_path' => '/resources/views/admin/package-container/admin-p-sidebar.blade.php',
            'include' => "@include('" . self::PACKAGE_PREFIX . "::admin.sidebar')"
        ],
        'package-settings' => [
            'view_path' => '/resources/views/admin/package-settings/package-settings-container.blade.php',
            'include' => "@include('" . self::PACKAGE_PREFIX . "::admin.package-functions')"
        ],
    ];
}
