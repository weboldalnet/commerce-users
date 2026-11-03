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
            'last_name.required' => __('commerce-users::common.validation.last_name_required'),
            'first_name.required' => __('commerce-users::common.validation.first_name_required'),
            'phone.regex' => __('commerce-users::common.validation.phone_format'),
        ]);

        CommerceUsers::user()->fill([
            'last_name' => trim($data['last_name']),
            'first_name' => trim($data['first_name']),
            'phone' => $data['phone'] ? trim($data['phone']) : null,
        ])->save();

        return redirect()->to(CommerceUsers::route('commerce-users.account'))->with('success', __('commerce-users::account.profile.saved'));
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
            'current_password.required' => __('commerce-users::account.password.current_required'),
            'password.required' => __('commerce-users::common.validation.new_password_required'),
            'password.confirmed' => __('commerce-users::account.password.confirmed'),
            'password.min' => __('commerce-users::account.password.min'),
            'password.different' => __('commerce-users::account.password.different'),
        ]);

        if (!Hash::check($request->input('current_password'), $user->password)) {
            return back()->withErrors(['current_password' => __('commerce-users::account.password.current_wrong')]);
        }

        $user->password = $request->input('password');
        $user->save();
        $request->session()->regenerate();

        return redirect()->to(CommerceUsers::route('commerce-users.password.edit'))->with('success', __('commerce-users::account.password.saved'));
    }
}
