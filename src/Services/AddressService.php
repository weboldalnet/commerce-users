<?php

namespace Weboldalnet\CommerceUsers\Services;

use Weboldalnet\CommerceUsers\Models\CommerceUser;
use Weboldalnet\CommerceUsers\Models\CommerceUserAddress;
use Weboldalnet\CommerceCore\Support\CommerceConnection;

/**
 * Címek mentése úgy, hogy típusonként (szállítási / számlázási) mindig PONTOSAN egy
 * elsődleges legyen, amíg van cím: az első cím magától elsődleges, az elsődleges törlésekor
 * a legrégebbi megmaradó veszi át.
 */
class AddressService
{
    public const FIELDS = ['label', 'name', 'company', 'tax_number', 'phone', 'country', 'zip', 'city', 'address'];

    public static function save(CommerceUser $user, string $type, array $data, ?CommerceUserAddress $address = null): CommerceUserAddress
    {
        return CommerceConnection::db()->transaction(function () use ($user, $type, $data, $address) {
            $address = $address ?: new CommerceUserAddress(['type' => $type]);
            $address->fill(array_intersect_key($data, array_flip(self::FIELDS)));
            $address->country = $address->country ?: 'HU';
            $address->commerce_user_id = $user->id;

            $hasOther = $user->addresses()->where('type', $type)
                ->when($address->exists, fn ($q) => $q->where('id', '!=', $address->id))
                ->exists();

            $makeDefault = !empty($data['is_default']) || !$hasOther;
            // egy elsődleges cím nem „veszítheti el” a jelölését szerkesztéssel: csak egy másik átvételével
            $address->is_default = $makeDefault || ($address->exists && $address->getOriginal('is_default'));
            $address->save();

            if ($address->is_default) {
                self::clearOtherDefaults($address);
            }

            return $address;
        });
    }

    public static function makeDefault(CommerceUserAddress $address): void
    {
        CommerceConnection::db()->transaction(function () use ($address) {
            $address->is_default = true;
            $address->save();
            self::clearOtherDefaults($address);
        });
    }

    public static function delete(CommerceUserAddress $address): void
    {
        CommerceConnection::db()->transaction(function () use ($address) {
            $wasDefault = $address->is_default;
            $userId = $address->commerce_user_id;
            $type = $address->type;
            $address->delete();

            if ($wasDefault) {
                $next = CommerceUserAddress::where('commerce_user_id', $userId)->where('type', $type)->orderBy('id')->first();
                if ($next) {
                    $next->is_default = true;
                    $next->save();
                }
            }
        });
    }

    public static function countOfType(CommerceUser $user, string $type): int
    {
        return $user->addresses()->where('type', $type)->count();
    }

    public static function maxPerType(): int
    {
        return (int) config('commerce-users.max_addresses_per_type', 20) ?: 20;
    }

    private static function clearOtherDefaults(CommerceUserAddress $address): void
    {
        CommerceUserAddress::where('commerce_user_id', $address->commerce_user_id)
            ->where('type', $address->type)
            ->where('id', '!=', $address->id)
            ->where('is_default', true)
            ->update(['is_default' => false]);
    }
}
