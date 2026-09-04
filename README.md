# Webshop felhasználó modul a commerce-core-hoz

Ez a csomag a webshop felhasználó- és fiókkezelését adja a `weboldalnet/commerce-core` alapú rendszerekhez.

> **Állapot:** csomagváz. A struktúra és az elnevezések készen állnak (composer autoload,
> service provider, publish/extend parancsok, config), a tényleges funkció (modellek,
> migrációk, regisztráció/bejelentkezés, fiókoldal, admin felület, útvonalak) még nincs megírva.

A provider-csomagoktól (SimplePay, FoxPost, GLS, Barion, Számlázz.hu) eltérően ez nem
fizetési/szállítási/számlázási szolgáltató, ezért nem jelentkezik be a `commerce-core`
provider registry-jébe – a saját admin felületét maga hozza magával.

## Telepítés

A projekt `composer.json`-jában:

```json
"repositories": [
    {
        "type": "vcs",
        "url": "https://github.com/weboldalnet/commerce-users"
    }
]
```

```bash
composer require weboldalnet/commerce-users:^1.0
```

A service provider Laravel package auto-discovery-vel regisztrálódik
(`Weboldalnet\CommerceUsers\CommerceUsersServiceProvider`).

## Konfiguráció

Publikálás a projektbe:

```bash
php artisan commerce-users:install --tag=commerce-users-all
php artisan commerce-users:extend --view=all
```

Publikálható tagek:

| tag | tartalom |
| --- | --- |
| `commerce-users-routes` | `routes/web.php` → `routes/commerce-users.php` |
| `commerce-users-settings` | `settings/` → `settings/commerce-users` |
| `commerce-users-config` | `config/commerce-users.php` |
| `commerce-users-all` | mindegyik |

`.env` beállítások:

```env
COMMERCE_USERS_ENABLED=false
```

## Névterek és fájlszerkezet

```
src/CommerceUsersServiceProvider.php             – service provider (publish, route, view betöltés)
src/Console/InstallCommerceUsersCommand.php      – commerce-users:install
src/Console/ExtendViewsCommerceUsersCommand.php  – commerce-users:extend
src/Support/PackageHelper.php                    – publish lista és view kiegészítések
config/commerce-users.php                        – konfiguráció
routes/web.php                                   – útvonalak (egyelőre üres váz)
settings/views/admin/                            – admin sidebar és package-functions blade
```
