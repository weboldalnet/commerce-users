<?php

namespace Weboldalnet\CommerceUsers;

use Illuminate\Routing\Router;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;
use Weboldalnet\CommerceUsers\Http\Middleware\AuthenticateCommerceUser;
use Weboldalnet\CommerceUsers\Http\Middleware\EnsureCommerceUsersEnabled;
use Weboldalnet\CommerceUsers\Http\Middleware\RedirectIfCommerceUser;
use Weboldalnet\CommerceUsers\Models\CommerceUser;
use Weboldalnet\CommerceUsers\Support\PackageHelper;
use Weboldalnet\CommerceUsers\Console\ExtendViewsCommerceUsersCommand;
use Weboldalnet\CommerceUsers\Console\InstallCommerceUsersCommand;

class CommerceUsersServiceProvider extends ServiceProvider
{
    public function boot()
    {
        $this->registerAuth();

        /** @var Router $router */
        $router = $this->app['router'];
        $router->aliasMiddleware('commerce-users.enabled', EnsureCommerceUsersEnabled::class);
        $router->aliasMiddleware('commerce-users.auth', AuthenticateCommerceUser::class);
        $router->aliasMiddleware('commerce-users.guest', RedirectIfCommerceUser::class);

        // route-ok és nézetek (site, levelek, admin: commerce-users::…; a projekt
        // resources/views/vendor/commerce-users mappája felülírja őket)
        $this->loadRoutesFrom(__DIR__.'/../routes/web.php');
        $this->loadViewsFrom(__DIR__.'/../resources/views', PackageHelper::PACKAGE_PREFIX);
        $this->loadViewsFrom(__DIR__.'/../settings/views', PackageHelper::PACKAGE_PREFIX);

        // A modul saját tábláit a csomag hozza; publish nem kell hozzájuk.
        $this->loadMigrationsFrom(__DIR__.'/../database/migrations');

        // a fejléc fiók-gombja és a fiókoldalak a bejelentkezett vásárlót kapják meg
        View::composer('commerce-users::*', function ($view) {
            if (!$view->offsetExists('commerceUser')) {
                $view->with('commerceUser', CommerceUsers::user());
            }
        });

        $publishList = [];
        foreach (PackageHelper::PACKAGE_LIST as $name => $publish) {
            $this->publishes([
                $publish['source'] => base_path($publish['destination']),
            ], PackageHelper::PACKAGE_PREFIX . '-' . $name);

            $publishList[$publish['source']] = base_path($publish['destination']);
        }

        $this->publishes($publishList, PackageHelper::PACKAGE_PREFIX . '-all');

        $this->publishes([
            PackageHelper::VIEWS_SOURCE => resource_path('views/vendor/' . PackageHelper::PACKAGE_PREFIX),
        ], PackageHelper::PACKAGE_PREFIX . '-views');
    }

    public function register()
    {
        $this->mergeConfigFrom(__DIR__.'/../config/commerce-users.php', 'commerce-users');

        $this->commands([
            InstallCommerceUsersCommand::class,
            ExtendViewsCommerceUsersCommand::class,
        ]);
    }

    /**
     * Saját guard, provider és jelszó-broker a vásárlóknak.
     *
     * Futásidejű config()->set, NEM mergeConfigFrom: cache-elt confignál (élesben) a merge
     * kimarad, ez viszont minden kérésnél lefut. Ha a projekt saját maga felvette ugyanezeket
     * a kulcsokat, azokat nem írjuk felül.
     */
    protected function registerAuth(): void
    {
        $config = $this->app['config'];

        if (!$config->get('auth.guards.' . CommerceUsers::GUARD)) {
            $config->set('auth.guards.' . CommerceUsers::GUARD, [
                'driver' => 'session',
                'provider' => CommerceUsers::PROVIDER,
            ]);
        }

        if (!$config->get('auth.providers.' . CommerceUsers::PROVIDER)) {
            $config->set('auth.providers.' . CommerceUsers::PROVIDER, [
                'driver' => 'eloquent',
                'model' => CommerceUser::class,
            ]);
        }

        if (!$config->get('auth.passwords.' . CommerceUsers::BROKER)) {
            $config->set('auth.passwords.' . CommerceUsers::BROKER, [
                'provider' => CommerceUsers::PROVIDER,
                'table' => 'public.commerce_user_password_resets',
                // a tokentábla is a közös (alapnyelvi) adatbázisban: a fiók nyelvtől független
                'connection' => \Weboldalnet\CommerceCore\Support\CommerceConnection::name(),
                'expire' => (int) $config->get('commerce-users.password_reset_expire_minutes', 60) ?: 60,
                'throttle' => 60,
            ]);
        }
    }
}
