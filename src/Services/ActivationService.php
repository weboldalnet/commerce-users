<?php

namespace Weboldalnet\CommerceUsers\Services;

use Weboldalnet\CommerceUsers\CommerceUsers;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\URL;
use Weboldalnet\CommerceUsers\Mail\ActivationMail;
use Weboldalnet\CommerceUsers\Models\CommerceUser;

/**
 * Regisztráció utáni e-mail-cím megerősítés: aláírt, lejáró link (id + az e-mail-cím hash-e),
 * így a linkből sem a felhasználó, sem a cím nem hamisítható, és egy régi link a
 * cím változása után sem működne.
 */
class ActivationService
{
    public static function expireHours(): int
    {
        return (int) config('commerce-users.activation_expire_hours', 48) ?: 48;
    }

    public static function url(CommerceUser $user): string
    {
        return URL::temporarySignedRoute(CommerceUsers::routeName('commerce-users.verify'), now()->addHours(self::expireHours()), [
            'id' => $user->id,
            'hash' => self::hash($user),
        ]);
    }

    public static function hash(CommerceUser $user): string
    {
        return sha1($user->email);
    }

    /** Elküldi az aktiváló levelet; hibánál naplóz, és false-t ad (a regisztráció ettől még megmarad). */
    public static function send(CommerceUser $user): bool
    {
        if (!$user->email) {
            return false;
        }

        try {
            Mail::to($user->email)->send(new ActivationMail($user, self::url($user)));

            return true;
        } catch (\Throwable $e) {
            Log::error('commerce-users: aktiváló levél hiba: ' . $e->getMessage(), ['user_id' => $user->id]);

            return false;
        }
    }

    public static function activate(CommerceUser $user): void
    {
        if (!$user->isVerified()) {
            $user->forceFill(['email_verified_at' => now()])->save();
        }
    }
}
