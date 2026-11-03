<?php

/*
 * Közös vásárlói szövegek és formátumok: __('commerce-users::common.*') — angol változat (a forrás: ../hu/).
 */
return [
    // Dátumformátumok (Carbon::format()), pl. a profil Regisztráció / Utolsó belépés sora
    'date' => 'j M Y',
    'datetime' => 'j M Y H:i',

    'my_account' => 'My account',
    'login' => 'Sign in',
    'logout' => 'Sign out',
    'back_to_login' => 'Back to sign in',
    'resend_activation' => 'Resend activation email',

    // Űrlapmezők feliratai
    'fields' => [
        'email' => 'Email address',
        'password' => 'Password',
        'password_confirmation' => 'Confirm password',
        'new_password' => 'New password',
        'new_password_confirmation' => 'Confirm new password',
        'last_name' => 'Last name',
        'first_name' => 'First name',
        'phone' => 'Phone number',
    ],

    // Jelszómező (site/partials/password-field, password-script)
    'password' => [
        'show' => 'Show password',
        'hide' => 'Hide password',
        'min_hint' => 'At least :min characters.',
        'save' => 'Save password',
    ],

    // Validációs üzenetek több controllerből (a :min-t a validátor helyettesíti be)
    'validation' => [
        'email_required' => 'Please enter your email address.',
        'email_invalid' => 'This is not a valid email address.',
        'last_name_required' => 'Please enter your last name.',
        'first_name_required' => 'Please enter your first name.',
        'phone_format' => 'The phone number may only contain digits, spaces and the characters + ( ) / -.',
        'password_required' => 'Please enter a password.',
        'new_password_required' => 'Please enter a new password.',
        'password_confirmed' => 'The two passwords do not match.',
        'password_min' => 'The password must be at least :min characters long.',
    ],
];
