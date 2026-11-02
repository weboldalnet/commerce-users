<?php
/**
 * Webshop felhasználó modul konfiguráció.
 *
 * FIGYELEM: webshop projektben a modul BE/KI kapcsolója és a funkciók (címkezelés,
 * kötelező bejelentkezés a vásárláshoz …) NEM itt vannak, hanem a Webshop beállítások
 * „Vásárlói fiókok” fülén — a webshop-ai-default a CommerceUsers::resolveFeaturesUsing()
 * hívással adja át őket, így a bolt tulajdonosa .env-módosítás nélkül kapcsolhatja.
 * Az itteni `enabled` csak webshop NÉLKÜLI projektben számít.
 *
 * A biztonsági alapértékek (jelszóhossz, token-élettartam …) a kódban is ott vannak
 * tartalékként: cache-elt confignál a csomag mergeConfigFrom-ja kimarad, és egy
 * config('commerce-users.…') hívás ilyenkor null-t adna.
 */
return [
    'enabled' => env('COMMERCE_USERS_ENABLED', false),

    // A site oldali fiók-útvonalak előtagja: /fiok/belepes, /fiok/regisztracio …
    'route_prefix' => 'fiok',

    // Az aktiváló link érvényessége órában
    'activation_expire_hours' => 48,

    // Az elfelejtett jelszó link érvényessége percben
    'password_reset_expire_minutes' => 60,

    // A jelszó minimális hossza
    'password_min_length' => 8,

    // Felhasználónként legfeljebb ennyi szállítási és ennyi számlázási cím menthető
    'max_addresses_per_type' => 20,
];
