<?php

/*
 * Közös vásárlói szövegek és formátumok: __('commerce-users::common.*') — több nézet és controller használja
 * (űrlapmezők, jelszómező, validációs üzenetek, dátumformátum). Minden kulcsnak van angol párja (../en/).
 * A projekt felülírhatja: lang/vendor/commerce-users/{locale}/common.php
 */
return [
    // Dátumformátumok (Carbon::format()), pl. a profil Regisztráció / Utolsó belépés sora
    'date' => 'Y. m. d.',
    'datetime' => 'Y. m. d. H:i',

    'my_account' => 'Fiókom',
    'login' => 'Bejelentkezés',
    'logout' => 'Kijelentkezés',
    'back_to_login' => 'Vissza a bejelentkezéshez',
    'resend_activation' => 'Aktiváló levél újraküldése',

    // Űrlapmezők feliratai
    'fields' => [
        'email' => 'E-mail-cím',
        'password' => 'Jelszó',
        'password_confirmation' => 'Jelszó még egyszer',
        'new_password' => 'Új jelszó',
        'new_password_confirmation' => 'Új jelszó még egyszer',
        'last_name' => 'Vezetéknév',
        'first_name' => 'Keresztnév',
        'phone' => 'Telefonszám',
    ],

    // Jelszómező (site/partials/password-field, password-script)
    'password' => [
        'show' => 'Jelszó megjelenítése',
        'hide' => 'Jelszó elrejtése',
        'min_hint' => 'Legalább :min karakter.',
        'save' => 'Jelszó mentése',
    ],

    // Validációs üzenetek több controllerből (a :min-t a validátor helyettesíti be)
    'validation' => [
        'email_required' => 'Add meg az e-mail-címed.',
        'email_invalid' => 'Ez nem érvényes e-mail-cím.',
        'last_name_required' => 'Add meg a vezetékneved.',
        'first_name_required' => 'Add meg a keresztneved.',
        'phone_format' => 'A telefonszámban csak számok, szóköz és a + ( ) / - jelek szerepelhetnek.',
        'password_required' => 'Adj meg egy jelszót.',
        'new_password_required' => 'Adj meg egy új jelszót.',
        'password_confirmed' => 'A két jelszó nem egyezik.',
        'password_min' => 'A jelszó legalább :min karakter legyen.',
    ],
];
