<?php

namespace Weboldalnet\CommerceUsers;

use Illuminate\Support\ServiceProvider;
use Weboldalnet\CommerceUsers\Support\PackageHelper;
use Weboldalnet\CommerceUsers\Console\ExtendViewsCommerceUsersCommand;
use Weboldalnet\CommerceUsers\Console\InstallCommerceUsersCommand;

class CommerceUsersServiceProvider extends ServiceProvider
{
    public function boot()
    {
        // route-ok és admin nézetek
        $this->loadRoutesFrom(__DIR__.'/../routes/web.php');
        $this->loadViewsFrom(__DIR__.'/../settings/views', PackageHelper::PACKAGE_PREFIX);

        // migrációk – a modul saját tábláinak elkészítésekor kell aktiválni
        //$this->loadMigrationsFrom(__DIR__.'/../database/migrations');

        $publishList = [];
        foreach (PackageHelper::PACKAGE_LIST as $name => $publish) {
            $this->publishes([
                $publish['source'] => base_path($publish['destination']),
            ], PackageHelper::PACKAGE_PREFIX . '-' . $name);

            $publishList[$publish['source']] = base_path($publish['destination']);
        }

        $this->publishes($publishList, PackageHelper::PACKAGE_PREFIX . '-all');
    }

    public function register()
    {
        $this->mergeConfigFrom(__DIR__.'/../config/commerce-users.php', 'commerce-users');

        $this->commands([
            InstallCommerceUsersCommand::class,
            ExtendViewsCommerceUsersCommand::class,
        ]);
    }
}
