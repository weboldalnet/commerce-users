<?php

namespace Weboldalnet\CommerceUsers\Http\Controllers\Site;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Str;
use Weboldalnet\CommerceUsers\CommerceUsers;
use Weboldalnet\CommerceUsers\Models\CommerceUser;
use Weboldalnet\CommerceUsers\Services\ActivationService;

class PasswordResetController extends Controller
{
    public function showForgot()
    {
        return view('commerce-users::site.auth.forgot-password');
    }

    /** A válasz nem árulja el, van-e fiók a címhez (csak a túl gyakori kérést jelzi). */
    public function sendLink(Request $request)
    {
        $data = $request->validate(['email' => 'required|email|max:255'], [
            'email.required' => __('commerce-users::common.validation.email_required'),
            'email.email' => __('commerce-users::common.validation.email_invalid'),
        ]);

        $email = mb_strtolower(trim($data['email']));
        $user = CommerceUser::where('email', $email)->first();

        // letiltott fiók nem kap levelet — a válasz ettől még ugyanaz
        if ($user && $user->is_active) {
            $status = Password::broker(CommerceUsers::BROKER)->sendResetLink(['email' => $email]);

            if ($status === Password::RESET_THROTTLED) {
                return back()->withInput()->withErrors(['email' => __('commerce-users::auth.forgot.throttled')]);
            }
        }

        return back()->with('success', __('commerce-users::auth.forgot.sent'));
    }

    public function showReset(Request $request, string $token)
    {
        return view('commerce-users::site.auth.reset-password', [
            'token' => $token,
            'email' => old('email', $request->query('email')),
            'minPassword' => RegisterController::minPasswordLength(),
        ]);
    }

    public function reset(Request $request)
    {
        $request->merge(['email' => mb_strtolower(trim((string) $request->input('email')))]);

        $request->validate([
            'token' => 'required|string',
            'email' => 'required|email|max:255',
            'password' => 'required|string|confirmed|min:' . RegisterController::minPasswordLength() . '|max:255',
        ], [
            'password.required' => __('commerce-users::common.validation.new_password_required'),
            'password.confirmed' => __('commerce-users::common.validation.password_confirmed'),
            'password.min' => __('commerce-users::common.validation.password_min'),
        ]);

        $status = Password::broker(CommerceUsers::BROKER)->reset(
            $request->only('email', 'password', 'password_confirmation', 'token'),
            function (CommerceUser $user, string $password) {
                $user->password = $password;
                $user->setRememberToken(Str::random(60));
                $user->save();
                // a levélben kapott link is igazolja a cím birtoklását
                ActivationService::activate($user);
            }
        );

        if ($status !== Password::PASSWORD_RESET) {
            return back()->withInput($request->only('email'))
                ->withErrors(['email' => __('commerce-users::auth.reset.invalid')]);
        }

        return redirect()->to(CommerceUsers::route('commerce-users.login'))->with('verified_email', $request->input('email'))
            ->with('success', __('commerce-users::auth.reset.success'));
    }
}
