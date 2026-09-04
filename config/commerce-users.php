<?php
/**
 * Webshop felhasználó modul konfiguráció.
 *
 * Csomagváz: a modul tényleges beállításai (regisztráció, bejelentkezés,
 * fiókoldal, jelszókezelés) a funkció fejlesztésekor kerülnek ide.
 */
return [
    'enabled' => env('COMMERCE_USERS_ENABLED', false),
];
