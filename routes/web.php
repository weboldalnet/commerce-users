<?php

use Illuminate\Support\Facades\Route;
use Weboldalnet\CommerceUsers\CommerceUsers;
use Weboldalnet\CommerceUsers\Http\Controllers\Admin\UserController;
use Weboldalnet\CommerceUsers\Http\Controllers\Site\AccountController;
use Weboldalnet\CommerceUsers\Http\Controllers\Site\AddressController;
use Weboldalnet\CommerceUsers\Http\Controllers\Site\AuthController;
use Weboldalnet\CommerceUsers\Http\Controllers\Site\PasswordResetController;
use Weboldalnet\CommerceUsers\Http\Controllers\Site\RegisterController;
use Weboldalnet\CommerceUsers\Http\Middleware\SetLocaleAlternates;

/*
|--------------------------------------------------------------------------
| Webshop felhasználó modul útvonalai
|--------------------------------------------------------------------------
|
| Site: /fiok/... (a config `route_prefix`-e). Kikapcsolt modulnál mind 404
| (commerce-users.enabled middleware). A rendeléseket és az újrarendelést a
| webshop-ai-default adja a saját útvonalain, ugyanebben a fiókmenüben.
|
| Többnyelvű site-on nyelvenként (CommerceUsers::locales()): az alapnyelv a megszokott /fiok/… címeken és
| commerce-users.* neveken, a többi nyelv /{nyelv}/account/… címen (config url_words), commerce-users.{nyelv}.*
| néven. A linkek a CommerceUsers::route()-tal készülnek, így a vásárló a kérés nyelvén marad (a nyelvet a core az
| URL-ből állítja).
|
| FIGYELEM: a platformon 'admin_share' a middleware alias, nem 'admin'.
|
*/

foreach (CommerceUsers::locales() as $cuLocale) {
    $cuIsDefault = $cuLocale === CommerceUsers::defaultLocale();
    $cuWord = fn (string $key) => CommerceUsers::word($key, $cuLocale);

    Route::domain(getSiteDomain())
        // a nyelvválasztó és a hreflang a fiókoldal nyelvi párjára mutasson (csak többnyelvű site-on dolgozik)
        ->middleware(['web', 'site_share', 'commerce-users.enabled', SetLocaleAlternates::class])
        ->prefix(($cuIsDefault ? '' : $cuLocale . '/') . $cuWord('prefix'))
        ->name('commerce-users.' . ($cuIsDefault ? '' : $cuLocale . '.'))
        ->group(function () use ($cuWord) {
            Route::middleware('commerce-users.guest')->group(function () use ($cuWord) {
                Route::get('/' . $cuWord('login'), [AuthController::class, 'showLogin'])->name('login');
                Route::post('/' . $cuWord('login'), [AuthController::class, 'login'])->name('login.submit');

                Route::get('/' . $cuWord('register'), [RegisterController::class, 'showRegister'])->name('register');
                Route::post('/' . $cuWord('register'), [RegisterController::class, 'register'])->name('register.submit');
                Route::get('/' . $cuWord('register') . '/' . $cuWord('done'), [RegisterController::class, 'done'])->name('register.done');

                Route::get('/' . $cuWord('forgot'), [PasswordResetController::class, 'showForgot'])->name('password.request');
                Route::post('/' . $cuWord('forgot'), [PasswordResetController::class, 'sendLink'])->name('password.email');
                Route::get('/' . $cuWord('reset') . '/{token}', [PasswordResetController::class, 'showReset'])->name('password.reset');
                Route::post('/' . $cuWord('reset'), [PasswordResetController::class, 'reset'])->name('password.update');
            });

            // az aktiváló link bejelentkezve is működjön (pl. másik fiókból kattintva)
            Route::get('/' . $cuWord('verify') . '/{id}/{hash}', [RegisterController::class, 'verify'])
                ->whereNumber('id')->name('verify');
            Route::post('/' . $cuWord('resend'), [RegisterController::class, 'resend'])->name('verification.resend');

            Route::post('/' . $cuWord('logout'), [AuthController::class, 'logout'])->name('logout');

            Route::middleware('commerce-users.auth')->group(function () use ($cuWord) {
                Route::get('/', [AccountController::class, 'show'])->name('account');
                Route::put('/', [AccountController::class, 'update'])->name('account.update');

                Route::get('/' . $cuWord('password'), [AccountController::class, 'editPassword'])->name('password.edit');
                Route::put('/' . $cuWord('password'), [AccountController::class, 'updatePassword'])->name('password.change');

                Route::middleware('commerce-users.enabled:addresses')->prefix($cuWord('addresses'))->name('addresses.')->group(function () use ($cuWord) {
                    Route::get('/', [AddressController::class, 'index'])->name('index');
                    Route::get('/' . $cuWord('new') . '/{type}', [AddressController::class, 'create'])->whereIn('type', ['shipping', 'billing'])->name('create');
                    Route::post('/' . $cuWord('new') . '/{type}', [AddressController::class, 'store'])->whereIn('type', ['shipping', 'billing'])->name('store');
                    Route::get('/{address}', [AddressController::class, 'edit'])->whereNumber('address')->name('edit');
                    Route::put('/{address}', [AddressController::class, 'update'])->whereNumber('address')->name('update');
                    Route::post('/{address}/' . $cuWord('default'), [AddressController::class, 'makeDefault'])->whereNumber('address')->name('default');
                    Route::delete('/{address}', [AddressController::class, 'destroy'])->whereNumber('address')->name('destroy');
                });
            });
        });
}

Route::domain(getAdminDomain())
    ->middleware(['web', 'admin_share', 'auth:admin'])
    ->prefix('webshop/users')
    ->name('admin.webshop.users.')
    ->group(function () {
        Route::get('/', [UserController::class, 'index'])->name('index');
        Route::get('/{user}', [UserController::class, 'show'])->whereNumber('user')->name('show');
        Route::put('/{user}/email', [UserController::class, 'updateEmail'])->whereNumber('user')->name('email');
        Route::post('/{user}/toggle-active', [UserController::class, 'toggleActive'])->whereNumber('user')->name('toggle-active');
        Route::post('/{user}/verify', [UserController::class, 'verify'])->whereNumber('user')->name('verify');
        Route::post('/{user}/resend-activation', [UserController::class, 'resendActivation'])->whereNumber('user')->name('resend-activation');
        Route::delete('/{user}', [UserController::class, 'destroy'])->whereNumber('user')->name('destroy');
    });
