<?php

namespace Weboldalnet\CommerceUsers\Models;

use Illuminate\Database\Eloquent\Model;
use Weboldalnet\CommerceCore\Models\Concerns\UsesCommerceConnection;

/**
 * Vásárló mentett szállítási vagy számlázási címe. Típusonként legfeljebb egy elsődleges.
 *
 * @property int $id
 * @property int $commerce_user_id
 * @property string $type
 * @property string|null $label
 * @property string $name
 * @property string|null $company
 * @property string|null $tax_number
 * @property string|null $phone
 * @property string $country
 * @property string $zip
 * @property string $city
 * @property string $address
 * @property bool $is_default
 */
class CommerceUserAddress extends Model
{
    use UsesCommerceConnection;

    protected $table = 'public.commerce_user_addresses';

    public const TYPE_SHIPPING = 'shipping';
    public const TYPE_BILLING = 'billing';

    public const TYPES = [
        self::TYPE_SHIPPING => 'Szállítási cím',
        self::TYPE_BILLING => 'Számlázási cím',
    ];

    protected $fillable = ['type', 'label', 'name', 'company', 'tax_number', 'phone', 'country', 'zip', 'city', 'address', 'is_default'];

    protected $casts = [
        'commerce_user_id' => 'integer',
        'is_default' => 'boolean',
    ];

    public function user()
    {
        return $this->belongsTo(CommerceUser::class, 'commerce_user_id');
    }

    /** Egysoros cím: „1117 Budapest, Habfény utca 12.” */
    public function getOneLineAttribute(): string
    {
        return trim($this->zip . ' ' . $this->city . ', ' . $this->address, ' ,');
    }

    /** A pénztár mezőneveivel megegyező tömb (name, zip, city, address …) — az előtöltéshez. */
    public function toCheckoutArray(): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'company' => $this->company,
            'tax_number' => $this->tax_number,
            'phone' => $this->phone,
            'zip' => $this->zip,
            'city' => $this->city,
            'address' => $this->address,
            'country' => $this->country,
        ];
    }

    /**
     * Ugyanaz-e a cím (kis- és nagybetűtől, szóközöktől függetlenül) — a pénztárból
     * mentésnél ne kerüljön be kétszer ugyanaz.
     */
    public function matches(array $data): bool
    {
        $norm = fn ($v) => mb_strtolower(preg_replace('/\s+/u', ' ', trim((string) $v)));

        foreach (['name', 'zip', 'city', 'address'] as $field) {
            if ($norm($this->$field) !== $norm($data[$field] ?? '')) {
                return false;
            }
        }

        return true;
    }
}
