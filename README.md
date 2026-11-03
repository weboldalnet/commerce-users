# Webshop felhasználó modul a commerce-core-hoz

Vásárlói fiókok a `weboldalnet/commerce-core` alapú webshopokhoz: regisztráció e-mailes aktiválással,
bejelentkezés, elfelejtett jelszó, profil, jelszócsere, szállítási és számlázási címek, valamint egy
admin vásárlólista. A webshophoz kötött részeket (Rendeléseim, újrarendelés, a pénztár kitöltése, a
kapcsolók) a befogadó adja — a `weboldalnet/webshop-ai-default` 1.1.17-től beépítetten.

## Funkciók

| Rész | Útvonal | Megjegyzés |
| --- | --- | --- |
| Regisztráció | `/fiok/regisztracio` | vezetéknév, keresztnév, e-mail, jelszó + megerősítés, adatkezelési hozzájárulás; csapda-mező és IP-korlát a robotok ellen |
| Aktiválás | `/fiok/aktivalas/{id}/{hash}` | aláírt, 48 óráig érvényes link; aktiválás nélkül nem lehet belépni; újraküldés (`/fiok/aktivalo-level`) |
| Bejelentkezés | `/fiok/belepes` | „Maradjak bejelentkezve”, 5 sikertelen próba / perc / e-mail + IP; visszatér az eredeti oldalra (pl. pénztár) |
| Elfelejtett jelszó | `/fiok/elfelejtett-jelszo` | 60 percig érvényes, egyszer használható link; a válasz nem árulja el, van-e fiók |
| Profil | `/fiok` | név és telefonszám módosítása; az e-mail-címet (belépési azonosító) a vásárló nem, csak az admin módosíthatja |
| Jelszócsere | `/fiok/jelszo` | a jelenlegi jelszóval |
| Címek | `/fiok/cimek` | több szállítási és számlázási cím, típusonként egy elsődleges (az első magától az) |
| Kijelentkezés | `POST /fiok/kilepes` | a munkamenet teljesen törlődik |
| Admin | `/webshop/users` | lista (keresés, állapotszűrő), adatlap, e-mail-cím módosítása (új aktiváló levéllel), kézi aktiválás, aktiváló levél újraküldése, letiltás, törlés |

A levelek (aktiválás, új jelszó) a core levélsablonját (`mail.layouts.mail_layout`) és a bolt arculati
színét (`ContentHelper::THEME['theme_color']`) használják, feladójuk `app.shop_email` / `app.shop_name`.

## Telepítés

```json
"repositories": [
    { "type": "vcs", "url": "https://github.com/weboldalnet/commerce-users" }
]
```

```bash
composer require weboldalnet/commerce-users:^1.1
php artisan migrate
php artisan vendor:publish --tag=commerce-users-assets --force
```

A migráció a saját tábláit hozza (`commerce_users`, `commerce_user_addresses`,
`commerce_user_password_resets`), a Laravel `users` táblájához nem nyúl. A modul saját guardon fut
(`commerce_user`), a `web` és az `admin` guardtól függetlenül; a guardot, a providert és a jelszó-brokert
a service provider futásidőben veszi fel (cache-elt confignál is).

Frissítéskor a stíluslapot is újra ki kell rakni: `vendor:publish --tag=commerce-users-assets --force`.

## Bekapcsolás

- **Webshop projektben** (webshop-ai-default 1.1.17+): Webshop beállítások › Vásárlói fiókok fül. Ott
  kapcsolható a modul, a kötelező belépés a vásárláshoz, a Rendeléseim (+ újrarendelés) és a címkezelés.
- **Webshop nélkül:** `.env` → `COMMERCE_USERS_ENABLED=true`, és a befogadó saját maga köti be a
  fejléc gombját: `@include('commerce-users::site.partials.account-button')`. Admin menüpont:
  `php artisan commerce-users:extend --view=sidebar`.

Kikapcsolt modulnál minden `/fiok/...` útvonal 404, a fejléc gombja nem jelenik meg.

## Bekötés a befogadó rendszerbe

```php
use Weboldalnet\CommerceUsers\CommerceUsers;

// honnan jönnek a kapcsolók ('enabled', 'addresses'); null = a modul alapértéke
CommerceUsers::resolveFeaturesUsing(fn (string $feature) => …);

// az adatkezelési tájékoztató ('privacy') és az ÁSZF ('terms') címe a regisztrációhoz
CommerceUsers::resolveLinksUsing(fn (string $name) => …);

// saját menüpont a fiókmenübe (pl. Rendeléseim); a beépítettek: profil 10, címek 30, jelszó 40
CommerceUsers::addAccountMenuItem('orders', 'Rendeléseim', 'fa-box', 'site.webshop.account.orders', 20,
    fn () => true, ['site.webshop.account.orders*']);

CommerceUsers::user();      // a bejelentkezett vásárló vagy null
CommerceUsers::enabled();   // be van-e kapcsolva
```

A befogadó saját fiókoldalai a modul elrendezését használhatják:
`@extends('commerce-users::site.layouts.account')`, szekciók: `title`, `cu_title`, `cu_content`.
Bejelentkezést kérő útvonalhoz: `->middleware(['commerce-users.enabled', 'commerce-users.auth'])`.

Az admin vásárló-adatlapjára a befogadó saját panelt tehet: az adatlap
`@includeIf('admin.webshop.users.partials.customer-orders', ['commerceUser' => …])`-t húz be.

## Konfiguráció

`config/commerce-users.php` (publikálás: `--tag=commerce-users-config`):

| kulcs | alap | jelentés |
| --- | --- | --- |
| `enabled` | `false` | csak webshop nélkül számít |
| `route_prefix` | `fiok` | a site útvonalak előtagja |
| `activation_expire_hours` | `48` | aktiváló link érvényessége |
| `password_reset_expire_minutes` | `60` | jelszó-visszaállító link érvényessége |
| `password_min_length` | `8` | minimális jelszóhossz |
| `max_addresses_per_type` | `20` | menthető címek típusonként |

A nézetek egyedi átalakításához: `vendor:publish --tag=commerce-users-views`, és csak a ténylegesen
módosított fájl maradjon a projektben (a kirakott nézet a későbbi frissítéseket elfedi).

## Fájlszerkezet

```
src/CommerceUsers.php                    – belépési pont: kapcsolók, fiókmenü, linkek, user()
src/CommerceUsersServiceProvider.php     – guard, middleware-ek, route-ok, nézetek, migráció
src/Models/                              – CommerceUser, CommerceUserAddress
src/Http/Controllers/Site/               – Auth, Register, PasswordReset, Account, Address
src/Http/Controllers/Admin/UserController.php
src/Http/Middleware/                     – enabled, auth, guest
src/Services/                            – ActivationService, AddressService
src/Mail/                                – ActivationMail, PasswordResetMail
resources/views/site|mail|admin          – nézetek (commerce-users::…)
public/packages/commerce-users/          – a fiókoldalak stíluslapja
database/migrations/                     – a modul táblái (PostgreSQL 9.4-kompatibilis, idempotens)
```
