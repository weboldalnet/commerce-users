<?php

/*
 * Fiókoldalak: __('commerce-users::account.*') — angol változat.
 */
return [
    // Fiókmenü (oldalsáv és a fejléc lenyílója)
    'menu' => [
        'label' => 'Account menu',
        'signed_in_as' => 'Signed in as:',
        'hello' => 'Hi, :name!',
        'profile' => 'My profile',
        'addresses' => 'My addresses',
        'password' => 'Change password',
    ],

    'profile' => [
        'title' => 'My profile',
        'details' => 'My details',
        'email_hint' => 'Your email address is your sign-in ID; if you would like to change it, please contact us.',
        'phone_hint' => 'We use this to fill in the phone number at checkout.',
        'save' => 'Save',
        'account' => 'Account',
        'registered_at' => 'Registered',
        'last_login_at' => 'Last sign-in',
        'shipping_address' => 'Shipping address',
        'billing_address' => 'Billing address',
        'not_saved' => 'Not saved yet',
        'manage_addresses' => 'Manage my addresses',
        'saved' => 'Your details have been saved.',
    ],

    'password' => [
        'title' => 'Change password',
        'current' => 'Current password',
        'current_required' => 'Please enter your current password.',
        'confirmed' => 'The two new passwords do not match.',
        'min' => 'The new password must be at least :min characters long.',
        'different' => 'The new password must be different from your current one.',
        'current_wrong' => 'The current password is incorrect.',
        'saved' => 'Your new password has been saved.',
    ],

    // Címlista (site/account/addresses/index) és az AddressController visszajelzései
    'addresses' => [
        'title' => 'My addresses',
        'shipping_title' => 'Shipping addresses',
        'billing_title' => 'Billing addresses',
        'new_shipping' => 'New shipping address',
        'new_billing' => 'New billing address',
        'empty_shipping' => 'You have no saved shipping addresses yet. At checkout, your default address is filled in automatically.',
        'empty_billing' => 'You have no saved billing addresses yet.',
        'shipping_label' => 'Shipping address',
        'billing_label' => 'Billing address',
        'default_badge' => 'Default',
        'tax_number_line' => 'Tax number: :number',
        'phone_line' => 'Tel.: :phone',
        'edit' => 'Edit',
        'make_default' => 'Make default',
        'delete' => 'Delete',
        'delete_confirm' => 'Are you sure you want to delete this address?',
        'limit_reached' => 'You have reached the maximum number of saved addresses. Delete an old one before adding a new one.',
        'saved' => 'The address has been saved.',
        'deleted' => 'The address has been deleted.',
        'made_default_shipping' => 'This is now your default shipping address.',
        'made_default_billing' => 'This is now your default billing address.',
    ],

    // Címűrlap (site/account/addresses/form) és a validációs üzenetei
    'address_form' => [
        'edit_shipping' => 'Edit shipping address',
        'edit_billing' => 'Edit billing address',
        'billing_name' => 'Billing name',
        'billing_name_hint' => 'Your own name as a private individual, or the company name for a business.',
        'recipient_name' => 'Recipient’s name',
        'label' => 'Label',
        'label_placeholder_shipping' => 'e.g. Home, Office',
        'label_placeholder_billing' => 'e.g. Company',
        'label_hint' => 'Optional — it just helps you tell your addresses apart.',
        'company' => 'Company name',
        'tax_number' => 'Tax number',
        'tax_number_hint' => 'For company invoices.',
        'zip' => 'Postcode',
        'city' => 'Town/City',
        'address' => 'Street and house number',
        'address_hint' => 'You can add the floor and door number here too.',
        'phone_hint' => 'The courier will use this number to reach the recipient.',
        'first_shipping' => 'This will be your default shipping address.',
        'first_billing' => 'This will be your default billing address.',
        'set_default' => 'Make this my default address',
        'save' => 'Save address',
        'cancel' => 'Cancel',
        'billing_name_required' => 'Please enter the billing name.',
        'recipient_name_required' => 'Please enter the recipient’s name.',
        'zip_required' => 'Please enter the postcode.',
        'city_required' => 'Please enter the town or city.',
        'address_required' => 'Please enter the street and house number.',
    ],
];
