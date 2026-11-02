<?php

namespace Weboldalnet\CommerceUsers\Http\Controllers\Site;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Hash;
use Weboldalnet\CommerceUsers\CommerceUsers;
use Weboldalnet\CommerceUsers\Models\CommerceUserAddress;

class AccountController extends Controller
{
    /** Profil: az adatok és a módosító űrlap egy oldalon. */
    public function show()
    {
        $user = CommerceUsers::user();

        return view('commerce-users::site.account.profile', [
            'user' => $user,
            'addressesEnabled' => CommerceUsers::feature('addresses'),
            'defaultShipping' => $user->defaultAddress(CommerceUserAddress::TYPE_SHIPPING),
            'defaultBilling' => $user->defaultAddress(CommerceUserAddress::TYPE_BILLING),
        ]);
    }

    /** Az e-mail-cím szándékosan NEM módosítható: az a belépési azonosító és a rendelések kapcsolata. */
    public function update(Request $request)
    {
        $data = $request->validate([
            'last_name' => 'required|string|max:100',
            'first_name' => 'required|string|max:100',
            'phone' => ['nullable', 'string', 'max:30', 'regex:/^[0-9+()\/\-\s]*$/'],
        ], [
            'last_name.required' => 'Add meg a vezetékneved.',
            'first_name.required' => 'Add meg a keresztneved.',
            'phone.regex' => 'A telefonszámban csak számok, szóköz és a + ( ) / - jelek szerepelhetnek.',
        ]);

        CommerceUsers::user()->fill([
            'last_name' => trim($data['last_name']),
            'first_name' => trim($data['first_name']),
            'phone' => $data['phone'] ? trim($data['phone']) : null,
        ])->save();

        return redirect()->route('commerce-users.account')->with('success', 'Az adataidat elmentettük.');
    }

    public function editPassword()
    {
        return view('commerce-users::site.account.password', [
            'user' => CommerceUsers::user(),
            'minPassword' => RegisterController::minPasswordLength(),
        ]);
    }

    public function updatePassword(Request $request)
    {
        $user = CommerceUsers::user();

        $request->validate([
            'current_password' => 'required|string',
            'password' => 'required|string|confirmed|min:' . RegisterController::minPasswordLength() . '|max:255|different:current_password',
        ], [
            'current_password.required' => 'Add meg a jelenlegi jelszavad.',
            'password.required' => 'Adj meg egy új jelszót.',
            'password.confirmed' => 'A két új jelszó nem egyezik.',
            'password.min' => 'Az új jelszó legalább :min karakter legyen.',
            'password.different' => 'Az új jelszó nem egyezhet a jelenlegivel.',
        ]);

        if (!Hash::check($request->input('current_password'), $user->password)) {
            return back()->withErrors(['current_password' => 'A jelenlegi jelszó nem megfelelő.']);
        }

        $user->password = $request->input('password');
        $user->save();
        $request->session()->regenerate();

        return redirect()->route('commerce-users.password.edit')->with('success', 'Az új jelszavad elmentettük.');
    }
}
