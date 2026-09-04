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
    ];

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
