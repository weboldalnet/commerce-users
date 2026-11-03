<?php

/*
 * Fiókoldalak: __('commerce-users::account.*')
 * Nézetek: site/layouts/account, site/partials/account-button, site/account/** (profil, jelszócsere, címek);
 * controllerek: Site/AccountController, AddressController; fiókmenü: CommerceUsers::accountMenu().
 */
return [
    // Fiókmenü (oldalsáv és a fejléc lenyílója)
    'menu' => [
        'label' => 'Fiókmenü',
        'signed_in_as' => 'Bejelentkezve:',
        'hello' => 'Szia, :name!',
        'profile' => 'Profilom',
        'addresses' => 'Címeim',
        'password' => 'Jelszócsere',
    ],

    'profile' => [
        'title' => 'Profilom',
        'details' => 'Adataim',
        'email_hint' => 'Az e-mail-cím a belépési azonosítód; ha módosítani szeretnéd, írj nekünk.',
        'phone_hint' => 'A pénztárban ezzel töltjük ki a telefonszám mezőt.',
        'save' => 'Mentés',
        'account' => 'Fiók',
        'registered_at' => 'Regisztráció',
        'last_login_at' => 'Utolsó belépés',
        'shipping_address' => 'Szállítási cím',
        'billing_address' => 'Számlázási cím',
        'not_saved' => 'Még nincs mentve',
        'manage_addresses' => 'Címeim kezelése',
        'saved' => 'Az adataidat elmentettük.',
    ],

    'password' => [
        'title' => 'Jelszócsere',
        'current' => 'Jelenlegi jelszó',
        'current_required' => 'Add meg a jelenlegi jelszavad.',
        'confirmed' => 'A két új jelszó nem egyezik.',
        'min' => 'Az új jelszó legalább :min karakter legyen.',
        'different' => 'Az új jelszó nem egyezhet a jelenlegivel.',
        'current_wrong' => 'A jelenlegi jelszó nem megfelelő.',
        'saved' => 'Az új jelszavad elmentettük.',
    ],

    // Címlista (site/account/addresses/index) és az AddressController visszajelzései
    'addresses' => [
        'title' => 'Címeim',
        'shipping_title' => 'Szállítási címek',
        'billing_title' => 'Számlázási címek',
        'new_shipping' => 'Új szállítási cím',
        'new_billing' => 'Új számlázási cím',
        'empty_shipping' => 'Még nincs mentett szállítási címed. A pénztárban az elsődleges cím töltődik ki magától.',
        'empty_billing' => 'Még nincs mentett számlázási címed.',
        'shipping_label' => 'Szállítási cím',
        'billing_label' => 'Számlázási cím',
        'default_badge' => 'Elsődleges',
        'tax_number_line' => 'Adószám: :number',
        'phone_line' => 'Tel.: :phone',
        'edit' => 'Szerkesztés',
        'make_default' => 'Legyen elsődleges',
        'delete' => 'Törlés',
        'delete_confirm' => 'Biztosan törlöd ezt a címet?',
        'limit_reached' => 'Elérted a menthető címek számát. Törölj egy régit, mielőtt újat adsz hozzá.',
        'saved' => 'A címet elmentettük.',
        'deleted' => 'A címet töröltük.',
        'made_default_shipping' => 'Mostantól ez az elsődleges szállítási cím.',
        'made_default_billing' => 'Mostantól ez az elsődleges számlázási cím.',
    ],

    // Címűrlap (site/account/addresses/form) és a validációs üzenetei
    'address_form' => [
        'edit_shipping' => 'Szállítási cím szerkesztése',
        'edit_billing' => 'Számlázási cím szerkesztése',
        'billing_name' => 'Számlázási név',
        'billing_name_hint' => 'Magánszemélyként a neved, cégként a cégnév.',
        'recipient_name' => 'Címzett neve',
        'label' => 'Megnevezés',
        'label_placeholder_shipping' => 'pl. Otthon, Iroda',
        'label_placeholder_billing' => 'pl. Cég',
        'label_hint' => 'Nem kötelező — csak neked segít a címek között.',
        'company' => 'Cégnév',
        'tax_number' => 'Adószám',
        'tax_number_hint' => 'Céges számlához.',
        'zip' => 'Irányítószám',
        'city' => 'Település',
        'address' => 'Utca, házszám',
        'address_hint' => 'Emelet, ajtó is ide jöhet.',
        'phone_hint' => 'A futár ezen keresi a címzettet.',
        'first_shipping' => 'Ez lesz az elsődleges szállítási címed.',
        'first_billing' => 'Ez lesz az elsődleges számlázási címed.',
        'set_default' => 'Legyen ez az elsődleges cím',
        'save' => 'Cím mentése',
        'cancel' => 'Mégse',
        'billing_name_required' => 'Add meg a számlázási nevet.',
        'recipient_name_required' => 'Add meg a címzett nevét.',
        'zip_required' => 'Add meg az irányítószámot.',
        'city_required' => 'Add meg a települést.',
        'address_required' => 'Add meg az utcát és a házszámot.',
    ],
];
