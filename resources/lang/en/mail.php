<?php

/*
 * Levelek: __('commerce-users::mail.*') — angol változat.
 */
return [
    'greeting' => 'Dear :name,',
    'button_fallback' => 'If the button doesn’t work, copy this address into your browser:',

    'activation' => [
        'subject' => 'Activate your account – :shop',
        'intro' => 'Thank you for registering with the :shop online shop.',
        'instruction' => 'To activate your account, click the button below.',
        'button' => 'Activate account',
        'expiry' => '{1} The link is valid for :count hour. If you didn’t register, you can safely ignore this email — the account cannot be used without activation.|[2,*] The link is valid for :count hours. If you didn’t register, you can safely ignore this email — the account cannot be used without activation.',
    ],

    'password_reset' => [
        'subject' => 'Set a new password – :shop',
        'intro' => 'You requested a new password for your :shop account.',
        'instruction' => 'Click the button below to set your new password.',
        'button' => 'Set new password',
        'expiry' => '{1} The link is valid for :count minute. If you didn’t request this, you can safely ignore this email — your password will stay the same.|[2,*] The link is valid for :count minutes. If you didn’t request this, you can safely ignore this email — your password will stay the same.',
    ],
];
