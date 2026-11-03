<?php

/*
 * Belépés, regisztráció, aktiválás, elfelejtett és új jelszó: __('commerce-users::auth.*')
 * Nézetek: site/auth/*; controllerek: Site/AuthController, RegisterController, PasswordResetController;
 * middleware: AuthenticateCommerceUser.
 */
return [
    'login' => [
        'lead' => 'Lépj be a fiókodba a rendeléseid és a mentett címeid eléréséhez.',
        'remember' => 'Maradjak bejelentkezve',
        'forgot_link' => 'Elfelejtett jelszó?',
        'no_activation_mail' => 'Nem kaptad meg az aktiváló levelet?',
        'no_account' => 'Még nincs fiókod?',
        'register_link' => 'Regisztrálj',
        'password_required' => 'Add meg a jelszavad.',
        'throttled' => 'Túl sok sikertelen próbálkozás. Próbáld újra :count másodperc múlva.',
        'failed' => 'Hibás e-mail-cím vagy jelszó.',
        'disabled' => 'Ez a fiók le van tiltva. Kérdés esetén vedd fel velünk a kapcsolatot.',
        'unverified' => 'A fiókod még nincs aktiválva. Kattints az aktiváló levélben lévő linkre.',
        'success' => 'Sikeresen bejelentkeztél.',
        'logged_out' => 'Kijelentkeztél.',
        // AuthenticateCommerceUser: vendég egy fiókoldalon
        'required' => 'Ehhez az oldalhoz jelentkezz be a fiókodba.',
        'required_json' => 'Bejelentkezés szükséges.',
    ],

    'register' => [
        'title' => 'Regisztráció',
        'lead' => 'Fiókkal gyorsabb a vásárlás: a címeid elmentheted, a rendeléseid egy helyen látod.',
        'email_hint' => 'Erre a címre küldjük az aktiváló levelet. Később nem módosítható.',
        'new_password_link' => 'Új jelszó',
        'honeypot' => 'Weboldal',
        // :link = a privacy_policy szöveg (linkkel, ha van tájékoztató-cím)
        'accept_privacy' => 'Elolvastam és elfogadom az :link.',
        'privacy_policy' => 'adatkezelési tájékoztatót',
        'submit' => 'Regisztráció',
        'has_account' => 'Van már fiókod?',
        'login_link' => 'Jelentkezz be',
        'throttled' => 'Túl sok regisztráció erről a hálózatról. Próbáld újra később.',
        'email_unique' => 'Ezzel az e-mail-címmel már van fiók. Jelentkezz be, vagy kérj új jelszót.',
        'accept_privacy_required' => 'A regisztrációhoz el kell fogadnod az adatkezelési tájékoztatót.',
    ],

    'register_done' => [
        'title' => 'Aktiváld a fiókod',
        'heading' => 'Már csak egy lépés',
        'sent_to' => 'Elküldtük az aktiváló levelet a(z) :email címre.',
        'sent' => 'Elküldtük az aktiváló levelet a megadott címre.',
        'click_link' => 'Kattints a benne lévő linkre, és utána bejelentkezhetsz.',
        'expiry' => 'A link :count óráig érvényes. Ha néhány percen belül nem érkezik meg, nézd meg a levélszemét (spam) mappát is, vagy kérj újat.',
        'continue_to_login' => 'Tovább a bejelentkezéshez',
    ],

    'activation' => [
        'expired_title' => 'Lejárt aktiváló link',
        'expired_heading' => 'A link érvénytelen vagy lejárt',
        'expired_lead' => 'Az aktiváló link csak korlátozott ideig érvényes. Add meg az e-mail-címed, és küldünk egy újat.',
        'request_new' => 'Új aktiváló levél kérése',
        'already_active' => 'A fiókod már aktív, bejelentkezhetsz.',
        'success' => 'Sikeres aktiválás! Most már bejelentkezhetsz.',
        'resent' => 'Ha ezzel a címmel van aktiválásra váró fiók, újra elküldtük az aktiváló levelet.',
    ],

    'forgot' => [
        'title' => 'Elfelejtett jelszó',
        'lead' => 'Add meg a fiókodhoz tartozó e-mail-címet, és küldünk egy linket, amellyel új jelszót állíthatsz be.',
        'submit' => 'Link küldése',
        'throttled' => 'Nemrég küldtünk már levelet erre a címre. Kérlek, várj egy percet.',
        'sent' => 'Ha ezzel a címmel van fiók, elküldtük a jelszó-visszaállító levelet. Nézd meg a beérkező leveleid (és a levélszemét mappát is).',
    ],

    'reset' => [
        'title' => 'Új jelszó beállítása',
        'invalid' => 'A jelszó-visszaállító link érvénytelen vagy lejárt. Kérj újat az „Elfelejtett jelszó” oldalon.',
        'success' => 'Az új jelszavad beállítottuk. Most már bejelentkezhetsz vele.',
    ],
];
