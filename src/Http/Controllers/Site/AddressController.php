<?php

namespace Weboldalnet\CommerceUsers\Http\Controllers\Site;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Validation\Rule;
use Weboldalnet\CommerceUsers\CommerceUsers;
use Weboldalnet\CommerceUsers\Models\CommerceUserAddress;
use Weboldalnet\CommerceUsers\Services\AddressService;

/**
 * Szállítási és számlázási címek. Minden cím a bejelentkezett vásárló saját címei közül
 * töltődik be ($user->addresses()->findOrFail), így más címét azonosítóval sem érheti el.
 */
class AddressController extends Controller
{
    public function index()
    {
        $user = CommerceUsers::user();

        return view('commerce-users::site.account.addresses.index', [
            'user' => $user,
            'shippingAddresses' => $user->shippingAddresses()->get(),
            'billingAddresses' => $user->billingAddresses()->get(),
            'maxPerType' => AddressService::maxPerType(),
        ]);
    }

    public function create(string $type)
    {
        abort_unless(isset(CommerceUserAddress::TYPES[$type]), 404);
        $user = CommerceUsers::user();

        if (AddressService::countOfType($user, $type) >= AddressService::maxPerType()) {
            return redirect()->route('commerce-users.addresses.index')
                ->with('error', 'Elérted a menthető címek számát. Törölj egy régit, mielőtt újat adsz hozzá.');
        }

        // új címnél a nevet előre kitöltjük a fiók nevével
        $address = new CommerceUserAddress(['type' => $type, 'name' => $user->full_name,
            'phone' => $type === CommerceUserAddress::TYPE_SHIPPING ? $user->phone : null]);

        return view('commerce-users::site.account.addresses.form', [
            'address' => $address,
            'type' => $type,
            'isFirst' => AddressService::countOfType($user, $type) === 0,
        ]);
    }

    public function store(Request $request, string $type)
    {
        abort_unless(isset(CommerceUserAddress::TYPES[$type]), 404);
        $user = CommerceUsers::user();

        if (AddressService::countOfType($user, $type) >= AddressService::maxPerType()) {
            return redirect()->route('commerce-users.addresses.index')
                ->with('error', 'Elérted a menthető címek számát. Törölj egy régit, mielőtt újat adsz hozzá.');
        }

        AddressService::save($user, $type, $this->validated($request, $type));

        return redirect()->route('commerce-users.addresses.index')->with('success', 'A címet elmentettük.');
    }

    public function edit(int $address)
    {
        $address = CommerceUsers::user()->addresses()->findOrFail($address);

        return view('commerce-users::site.account.addresses.form', [
            'address' => $address,
            'type' => $address->type,
            'isFirst' => false,
        ]);
    }

    public function update(Request $request, int $address)
    {
        $user = CommerceUsers::user();
        $address = $user->addresses()->findOrFail($address);

        AddressService::save($user, $address->type, $this->validated($request, $address->type), $address);

        return redirect()->route('commerce-users.addresses.index')->with('success', 'A címet elmentettük.');
    }

    public function makeDefault(int $address)
    {
        $address = CommerceUsers::user()->addresses()->findOrFail($address);
        AddressService::makeDefault($address);

        return redirect()->route('commerce-users.addresses.index')
            ->with('success', 'Mostantól ez az elsődleges ' . mb_strtolower(CommerceUserAddress::TYPES[$address->type]) . '.');
    }

    public function destroy(int $address)
    {
        $address = CommerceUsers::user()->addresses()->findOrFail($address);
        AddressService::delete($address);

        return redirect()->route('commerce-users.addresses.index')->with('success', 'A címet töröltük.');
    }

    private function validated(Request $request, string $type): array
    {
        $isBilling = $type === CommerceUserAddress::TYPE_BILLING;

        $data = $request->validate([
            'label' => 'nullable|string|max:100',
            'name' => 'required|string|max:255',
            'company' => [Rule::excludeIf(!$isBilling), 'nullable', 'string', 'max:255'],
            'tax_number' => [Rule::excludeIf(!$isBilling), 'nullable', 'string', 'max:30'],
            'phone' => [Rule::excludeIf($isBilling), 'nullable', 'string', 'max:30', 'regex:/^[0-9+()\/\-\s]*$/'],
            'zip' => 'required|string|max:10',
            'city' => 'required|string|max:100',
            'address' => 'required|string|max:255',
            'is_default' => 'nullable|boolean',
        ], [
            'name.required' => $isBilling ? 'Add meg a számlázási nevet.' : 'Add meg a címzett nevét.',
            'zip.required' => 'Add meg az irányítószámot.',
            'city.required' => 'Add meg a települést.',
            'address.required' => 'Add meg az utcát és a házszámot.',
            'phone.regex' => 'A telefonszámban csak számok, szóköz és a + ( ) / - jelek szerepelhetnek.',
        ]);

        return array_map(fn ($v) => is_string($v) ? trim($v) : $v, $data);
    }
}
