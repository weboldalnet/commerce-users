<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Webshop felhasználó modul útvonalai
|--------------------------------------------------------------------------
|
| Csomagváz: a regisztráció / bejelentkezés / fiókoldal útvonalai a modul
| fejlesztésekor kerülnek ide, a testvércsomagok mintájára.
|
| FIGYELEM: a platformon 'admin_share' a middleware alias, nem 'admin'.
|
| Route::domain(getSiteDomain())
|     ->middleware(['web', 'site_share'])
|     ->group(function () {
|         ...
|     });
|
| Route::domain(getAdminDomain())
|     ->middleware(['web', 'admin_share', 'auth:admin'])
|     ->prefix('webshop/users')
|     ->name('admin.webshop.users.')
|     ->group(function () {
|         ...
|     });
|
*/
