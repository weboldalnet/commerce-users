<?php

/*
 * Belépés, regisztráció, aktiválás, elfelejtett és új jelszó: __('commerce-users::auth.*') — angol változat.
 */
return [
    'login' => [
        'lead' => 'Sign in to your account to see your orders and saved addresses.',
        'remember' => 'Keep me signed in',
        'forgot_link' => 'Forgotten your password?',
        'no_activation_mail' => 'Didn’t receive the activation email?',
        'no_account' => 'Don’t have an account yet?',
        'register_link' => 'Register',
        'password_required' => 'Please enter your password.',
        'throttled' => '{1} Too many failed attempts. Please try again in :count second.|[2,*] Too many failed attempts. Please try again in :count seconds.',
        'failed' => 'Incorrect email address or password.',
        'disabled' => 'This account has been disabled. If you have any questions, please contact us.',
        'unverified' => 'Your account has not been activated yet. Please click the link in the activation email.',
        'success' => 'You have signed in successfully.',
        'logged_out' => 'You have signed out.',
        // AuthenticateCommerceUser: vendég egy fiókoldalon
        'required' => 'Please sign in to your account to view this page.',
        'required_json' => 'Sign-in required.',
    ],

    'register' => [
        'title' => 'Create an account',
        'lead' => 'Shopping is quicker with an account: you can save your addresses and see all your orders in one place.',
        'email_hint' => 'We’ll send the activation email to this address. It cannot be changed later.',
        'new_password_link' => 'Reset password',
        'honeypot' => 'Website',
        // JOGI SZÖVEG — az angol változatot jogásznak ellenőriznie kell
        // :link = a privacy_policy szöveg (linkkel, ha van tájékoztató-cím)
        'accept_privacy' => 'I have read and accept the :link.',
        // JOGI SZÖVEG — az angol változatot jogásznak ellenőriznie kell
        'privacy_policy' => 'privacy notice',
        'submit' => 'Create account',
        'has_account' => 'Already have an account?',
        'login_link' => 'Sign in',
        'throttled' => 'Too many registrations from this network. Please try again later.',
        'email_unique' => 'An account with this email address already exists. Sign in or request a new password.',
        // JOGI SZÖVEG — az angol változatot jogásznak ellenőriznie kell
        'accept_privacy_required' => 'To register, you must accept the privacy notice.',
    ],

    'register_done' => [
        'title' => 'Activate your account',
        'heading' => 'Just one more step',
        'sent_to' => 'We have sent the activation email to :email.',
        'sent' => 'We have sent the activation email to the address you provided.',
        'click_link' => 'Click the link in it, and then you can sign in.',
        'expiry' => '{1} The link is valid for :count hour. If it doesn’t arrive within a few minutes, please check your spam folder too, or request a new one.|[2,*] The link is valid for :count hours. If it doesn’t arrive within a few minutes, please check your spam folder too, or request a new one.',
        'continue_to_login' => 'Continue to sign in',
    ],

    'activation' => [
        'expired_title' => 'Expired activation link',
        'expired_heading' => 'This link is invalid or has expired',
        'expired_lead' => 'The activation link is only valid for a limited time. Enter your email address and we’ll send you a new one.',
        'request_new' => 'Request a new activation email',
        'already_active' => 'Your account is already active, you can sign in.',
        'success' => 'Your account has been activated! You can now sign in.',
        'resent' => 'If there is an account awaiting activation with this address, we have resent the activation email.',
    ],

    'forgot' => [
        'title' => 'Forgotten password',
        'lead' => 'Enter the email address linked to your account and we’ll send you a link to set a new password.',
        'submit' => 'Send link',
        'throttled' => 'We have recently sent an email to this address. Please wait a minute.',
        'sent' => 'If there is an account with this address, we have sent the password reset email. Please check your inbox (and your spam folder too).',
    ],

    'reset' => [
        'title' => 'Set a new password',
        'invalid' => 'This password reset link is invalid or has expired. Request a new one on the “Forgotten password” page.',
        'success' => 'Your new password has been set. You can now sign in with it.',
    ],
];
