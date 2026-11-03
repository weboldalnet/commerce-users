<?php

use Illuminate\Support\Facades\Route;
use Weboldalnet\CommerceUsers\Http\Controllers\Admin\UserController;
use Weboldalnet\CommerceUsers\Http\Controllers\Site\AccountController;
use Weboldalnet\CommerceUsers\Http\Controllers\Site\AddressController;
use Weboldalnet\CommerceUsers\Http\Controllers\Site\AuthController;
use Weboldalnet\CommerceUsers\Http\Controllers\Site\PasswordResetController;
use Weboldalnet\CommerceUsers\Http\Controllers\Site\RegisterController;

/*
|--------------------------------------------------------------------------
| Webshop felhasználó modul útvonalai
|--------------------------------------------------------------------------
|
| Site: /fiok/... (a config `route_prefix`-e). Kikapcsolt modulnál mind 404
| (commerce-users.enabled middleware). A rendeléseket és az újrarendelést a
| webshop-ai-default adja a saját útvonalain, ugyanebben a fiókmenüben.
|
| FIGYELEM: a platformon 'admin_share' a middleware alias, nem 'admin'.
|
*/

Route::domain(getSiteDomain())
    ->middleware(['web', 'site_share', 'commerce-users.enabled'])
    ->prefix(config('commerce-users.route_prefix', 'fiok') ?: 'fiok')
    ->name('commerce-users.')
    ->group(function () {
        Route::middleware('commerce-users.guest')->group(function () {
            Route::get('/belepes', [AuthController::class, 'showLogin'])->name('login');
            Route::post('/belepes', [AuthController::class, 'login'])->name('login.submit');

            Route::get('/regisztracio', [RegisterController::class, 'showRegister'])->name('register');
            Route::post('/regisztracio', [RegisterController::class, 'register'])->name('register.submit');
            Route::get('/regisztracio/kesz', [RegisterController::class, 'done'])->name('register.done');

            Route::get('/elfelejtett-jelszo', [PasswordResetController::class, 'showForgot'])->name('password.request');
            Route::post('/elfelejtett-jelszo', [PasswordResetController::class, 'sendLink'])->name('password.email');
            Route::get('/uj-jelszo/{token}', [PasswordResetController::class, 'showReset'])->name('password.reset');
            Route::post('/uj-jelszo', [PasswordResetController::class, 'reset'])->name('password.update');
        });

        // az aktiváló link bejelentkezve is működjön (pl. másik fiókból kattintva)
        Route::get('/aktivalas/{id}/{hash}', [RegisterController::class, 'verify'])
            ->whereNumber('id')->name('verify');
        Route::post('/aktivalo-level', [RegisterController::class, 'resend'])->name('verification.resend');

        Route::post('/kilepes', [AuthController::class, 'logout'])->name('logout');

        Route::middleware('commerce-users.auth')->group(function () {
            Route::get('/', [AccountController::class, 'show'])->name('account');
            Route::put('/', [AccountController::class, 'update'])->name('account.update');

            Route::get('/jelszo', [AccountController::class, 'editPassword'])->name('password.edit');
            Route::put('/jelszo', [AccountController::class, 'updatePassword'])->name('password.change');

            Route::middleware('commerce-users.enabled:addresses')->prefix('cimek')->name('addresses.')->group(function () {
                Route::get('/', [AddressController::class, 'index'])->name('index');
                Route::get('/uj/{type}', [AddressController::class, 'create'])->whereIn('type', ['shipping', 'billing'])->name('create');
                Route::post('/uj/{type}', [AddressController::class, 'store'])->whereIn('type', ['shipping', 'billing'])->name('store');
                Route::get('/{address}', [AddressController::class, 'edit'])->whereNumber('address')->name('edit');
                Route::put('/{address}', [AddressController::class, 'update'])->whereNumber('address')->name('update');
                Route::post('/{address}/elsodleges', [AddressController::class, 'makeDefault'])->whereNumber('address')->name('default');
                Route::delete('/{address}', [AddressController::class, 'destroy'])->whereNumber('address')->name('destroy');
            });
        });
    });

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
