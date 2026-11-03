<?php

namespace Weboldalnet\CommerceUsers\Http\Controllers\Site;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;
use Weboldalnet\CommerceUsers\CommerceUsers;
use Weboldalnet\CommerceUsers\Models\CommerceUser;
use Weboldalnet\CommerceUsers\Services\ActivationService;

class RegisterController extends Controller
{
    public function showRegister()
    {
        return view('commerce-users::site.auth.register', [
            'privacyUrl' => CommerceUsers::link('privacy'),
            'termsUrl' => CommerceUsers::link('terms'),
            'minPassword' => self::minPasswordLength(),
        ]);
    }

    public function register(Request $request)
    {
        // Csapda-mező: ember nem látja, a bot kitölti. Ilyenkor sikert mutatunk, de nem hozunk létre fiókot.
        if ($request->filled('website')) {
            return redirect()->to(CommerceUsers::route('commerce-users.register.done'));
        }

        $key = 'commerce-users-register|' . $request->ip();
        if (RateLimiter::tooManyAttempts($key, 10)) {
            throw ValidationException::withMessages(['email' => __('commerce-users::auth.register.throttled')]);
        }

        $request->merge(['email' => mb_strtolower(trim((string) $request->input('email')))]);

        $data = $request->validate([
            'last_name' => 'required|string|max:100',
            'first_name' => 'required|string|max:100',
            'email' => ['required', 'email', 'max:255', Rule::unique(CommerceUser::class, 'email')],
            'password' => 'required|string|confirmed|min:' . self::minPasswordLength() . '|max:255',
            'accept_privacy' => 'accepted',
        ], [
            'last_name.required' => __('commerce-users::common.validation.last_name_required'),
            'first_name.required' => __('commerce-users::common.validation.first_name_required'),
            'email.required' => __('commerce-users::common.validation.email_required'),
            'email.email' => __('commerce-users::common.validation.email_invalid'),
            // a register nézet ezzel az üzenettel ismeri fel a foglalt címet (belépés / új jelszó linkek)
            'email.unique' => __('commerce-users::auth.register.email_unique'),
            'password.required' => __('commerce-users::common.validation.password_required'),
            'password.confirmed' => __('commerce-users::common.validation.password_confirmed'),
            'password.min' => __('commerce-users::common.validation.password_min'),
            'accept_privacy' => __('commerce-users::auth.register.accept_privacy_required'),
        ]);

        RateLimiter::hit($key, 3600);

        $user = CommerceUser::create([
            'last_name' => trim($data['last_name']),
            'first_name' => trim($data['first_name']),
            'email' => $data['email'],
            'password' => $data['password'],
            'is_active' => true,
        ]);

        ActivationService::send($user);

        return redirect()->to(CommerceUsers::route('commerce-users.register.done'))->with('registered_email', $user->email);
    }

    public function done()
    {
        return view('commerce-users::site.auth.register-done', [
            'email' => session('registered_email'),
            'expireHours' => ActivationService::expireHours(),
        ]);
    }

    public function verify(Request $request, int $id, string $hash)
    {
        if (!$request->hasValidSignature()) {
            return view('commerce-users::site.auth.activation-expired');
        }

        $user = CommerceUser::find($id);

        if (!$user || !hash_equals(ActivationService::hash($user), $hash)) {
            return view('commerce-users::site.auth.activation-expired');
        }

        if ($user->isVerified()) {
            return redirect()->to(CommerceUsers::route('commerce-users.login'))->with('verified_email', $user->email)
                ->with('info', __('commerce-users::auth.activation.already_active'));
        }

        ActivationService::activate($user);

        return redirect()->to(CommerceUsers::route('commerce-users.login'))->with('verified_email', $user->email)
            ->with('success', __('commerce-users::auth.activation.success'));
    }

    /** Aktiváló levél újraküldése. A válasz mindig ugyanaz: nem árulja el, van-e ilyen fiók. */
    public function resend(Request $request)
    {
        $data = $request->validate(['email' => 'required|email|max:255'], [
            'email.required' => __('commerce-users::common.validation.email_required'),
            'email.email' => __('commerce-users::common.validation.email_invalid'),
        ]);

        $email = mb_strtolower(trim($data['email']));
        $key = 'commerce-users-resend|' . $email . '|' . $request->ip();

        if (!RateLimiter::tooManyAttempts($key, 3)) {
            RateLimiter::hit($key, 600);

            $user = CommerceUser::where('email', $email)->first();
            if ($user && $user->is_active && !$user->isVerified()) {
                ActivationService::send($user);
            }
        }

        return back()->with('success', __('commerce-users::auth.activation.resent'));
    }

    public static function minPasswordLength(): int
    {
        return max(6, (int) config('commerce-users.password_min_length', 8));
    }
}
