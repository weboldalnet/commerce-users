<?php

namespace Weboldalnet\CommerceUsers\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Weboldalnet\CommerceUsers\Mail\PasswordResetMail;

/**
 * Vásárlói fiók.
 *
 * @property int $id
 * @property string $last_name
 * @property string $first_name
 * @property string $email
 * @property string|null $phone
 * @property \Illuminate\Support\Carbon|null $email_verified_at
 * @property bool $is_active
 * @property \Illuminate\Support\Carbon|null $last_login_at
 */
class CommerceUser extends Authenticatable
{
    protected $table = 'public.commerce_users';

    protected $fillable = ['last_name', 'first_name', 'email', 'phone', 'password', 'is_active'];

    protected $hidden = ['password', 'remember_token'];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'last_login_at' => 'datetime',
        'is_active' => 'boolean',
        'password' => 'hashed',
    ];

    public const STATUS_ACTIVE = 'active';
    public const STATUS_UNVERIFIED = 'unverified';
    public const STATUS_DISABLED = 'disabled';

    public const STATUSES = [
        self::STATUS_ACTIVE => 'Aktív',
        self::STATUS_UNVERIFIED => 'Aktiválásra vár',
        self::STATUS_DISABLED => 'Letiltva',
    ];

    /** Az e-mail-cím mindig kisbetűsen, szóközök nélkül kerül tárolásra (egyediség, belépés). */
    public function setEmailAttribute($value): void
    {
        $this->attributes['email'] = mb_strtolower(trim((string) $value));
    }

    public function addresses()
    {
        return $this->hasMany(CommerceUserAddress::class, 'commerce_user_id');
    }

    public function shippingAddresses()
    {
        return $this->addresses()->where('type', CommerceUserAddress::TYPE_SHIPPING)->orderByDesc('is_default')->orderBy('id');
    }

    public function billingAddresses()
    {
        return $this->addresses()->where('type', CommerceUserAddress::TYPE_BILLING)->orderByDesc('is_default')->orderBy('id');
    }

    public function defaultAddress(string $type): ?CommerceUserAddress
    {
        return $this->addresses()->where('type', $type)->orderByDesc('is_default')->orderBy('id')->first();
    }

    /** „Vezetéknév Keresztnév” — magyar sorrend. */
    public function getFullNameAttribute(): string
    {
        return trim($this->last_name . ' ' . $this->first_name);
    }

    public function isVerified(): bool
    {
        return $this->email_verified_at !== null;
    }

    public function status(): string
    {
        if (!$this->is_active) {
            return self::STATUS_DISABLED;
        }

        return $this->isVerified() ? self::STATUS_ACTIVE : self::STATUS_UNVERIFIED;
    }

    public function scopeSearch($query, ?string $search)
    {
        $search = trim((string) $search);
        if ($search === '') {
            return $query;
        }

        return $query->where(function ($q) use ($search) {
            $q->where('email', 'ILIKE', '%' . $search . '%')
                ->orWhere('last_name', 'ILIKE', '%' . $search . '%')
                ->orWhere('first_name', 'ILIKE', '%' . $search . '%')
                ->orWhereRaw("(last_name || ' ' || first_name) ILIKE ?", ['%' . $search . '%'])
                ->orWhere('phone', 'ILIKE', '%' . $search . '%');
        });
    }

    public function scopeStatus($query, ?string $status)
    {
        return match ($status) {
            self::STATUS_ACTIVE => $query->where('is_active', true)->whereNotNull('email_verified_at'),
            self::STATUS_UNVERIFIED => $query->where('is_active', true)->whereNull('email_verified_at'),
            self::STATUS_DISABLED => $query->where('is_active', false),
            default => $query,
        };
    }

    /**
     * A jelszó-broker ezt hívja: saját, a bolt arculatára illeszkedő levelet küldünk a Laravel
     * angol nyelvű alapértesítése helyett.
     */
    public function sendPasswordResetNotification($token): void
    {
        try {
            Mail::to($this->email)->send(new PasswordResetMail($this, $token));
        } catch (\Throwable $e) {
            Log::error('commerce-users: jelszó-visszaállító levél hiba: ' . $e->getMessage(), ['user_id' => $this->id]);
        }
    }
}
