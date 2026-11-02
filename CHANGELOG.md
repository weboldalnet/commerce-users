## [1.1.0] - 2026-11-02
### Vásárlói fiókok: regisztráció, bejelentkezés, profil, címek, admin vásárlólista
1 új migráció (`commerce_users`, `commerce_user_addresses`, `commerce_user_password_resets`) —
frissítés után `php artisan migrate` és `vendor:publish --tag=commerce-users-assets --force`.

- Regisztráció (vezetéknév, keresztnév, e-mail, jelszó + megerősítés, adatkezelési hozzájárulás),
  aktiválás aláírt, 48 óráig érvényes e-mailes linkkel; aktiváló levél újraküldése.
- Bejelentkezés (próbálkozás-korláttal, „Maradjak bejelentkezve”), kijelentkezés, elfelejtett jelszó.
- Profil (név, telefonszám; az e-mail-cím nem módosítható), jelszócsere.
- Szállítási és számlázási címek: több is lehet belőlük, típusonként egy elsődleges.
- Saját guard (`commerce_user`), a `web` és az `admin` guardtól független; futásidőben regisztrálva.
- Befogadói bekötés: `CommerceUsers::resolveFeaturesUsing()`, `resolveLinksUsing()`,
  `addAccountMenuItem()`, a fiókoldal elrendezése (`commerce-users::site.layouts.account`) és a
  `commerce-users.auth` / `commerce-users.enabled` middleware.
- Fejléc fiók-gombja (`commerce-users::site.partials.account-button`): vendégnek belépés, bejelentkezve
  lenyíló a fiókmenüvel.
- Admin: `/webshop/users` — lista keresővel és állapotszűrővel, adatlap a címekkel, kézi aktiválás,
  aktiváló levél újraküldése, letiltás / engedélyezés, törlés.
- Levelek a core levélsablonjában, a bolt színével és feladójával.

## [1.0.0] - 2026-09-04
### Csomagváz a webshop felhasználó modulhoz
- package-template elnevezések lecserélve (`Weboldalnet\CommerceUsers` névtér)
- `CommerceUsersServiceProvider`, `commerce-users:install`, `commerce-users:extend`
- `config/commerce-users.php`, üres `routes/web.php` váz
- composer csomagnév: `weboldalnet/commerce-users`, függőség: `weboldalnet/commerce-core:^1.0`
