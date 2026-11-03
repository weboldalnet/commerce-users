<?php

/*
 * Levelek: __('commerce-users::mail.*') — mail/activation, mail/password-reset és a tárgyuk
 * (Mail/ActivationMail, Mail/PasswordResetMail). A levél a küldő kérés nyelvén megy ki.
 */
return [
    'greeting' => 'Kedves :name!',
    'button_fallback' => 'Ha a gomb nem működik, másold be ezt a címet a böngésződbe:',

    'activation' => [
        'subject' => 'Fiókod aktiválása – :shop',
        'intro' => 'Köszönjük, hogy regisztráltál a(z) :shop webáruházban.',
        'instruction' => 'A fiókod aktiválásához kattints az alábbi gombra.',
        'button' => 'Fiók aktiválása',
        'expiry' => 'A link :count óráig érvényes. Ha nem te regisztráltál, nyugodtan hagyd figyelmen kívül ezt a levelet — aktiválás nélkül a fiók nem használható.',
    ],

    'password_reset' => [
        'subject' => 'Új jelszó beállítása – :shop',
        'intro' => 'Új jelszó beállítását kérted a(z) :shop fiókodhoz.',
        'instruction' => 'Az alábbi gombra kattintva adhatod meg az új jelszavad.',
        'button' => 'Új jelszó beállítása',
        'expiry' => 'A link :count percig érvényes. Ha nem te kérted, nyugodtan hagyd figyelmen kívül ezt a levelet — a jelszavad addig nem változik.',
    ],
];
