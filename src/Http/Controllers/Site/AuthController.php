<?php

namespace Weboldalnet\CommerceUsers\Http\Controllers\Site;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Validation\ValidationException;
use Weboldalnet\CommerceUsers\CommerceUsers;
use Weboldalnet\CommerceUsers\Models\CommerceUser;

class AuthController extends Controller
{
    /** Sikertelen belépési kísérletek e-mail + IP-cím párosonként, percenként */
    private const MAX_ATTEMPTS = 5;

    public function showLogin(Request $request)
    {
        // a pénztár „belépés, majd vissza” útja: a visszatérési címet itt jegyezzük meg
        if ($request->filled('redirect') && $this->isSafeRedirect($request->input('redirect'))) {
            $request->session()->put('url.intended', $request->input('redirect'));
        }

        return view('commerce-users::site.auth.login', [
            'email' => old('email', $request->query('email', session('verified_email'))),
        ]);
    }

    public function login(Request $request)
    {
        $data = $request->validate([
            'email' => 'required|email|max:255',
            'password' => 'required|string|max:255',
        ], [
            'email.required' => __('commerce-users::common.validation.email_required'),
            'email.email' => __('commerce-users::common.validation.email_invalid'),
            'password.required' => __('commerce-users::auth.login.password_required'),
        ]);

        $email = mb_strtolower(trim($data['email']));
        $key = 'commerce-users-login|' . $email . '|' . $request->ip();

        if (RateLimiter::tooManyAttempts($key, self::MAX_ATTEMPTS)) {
            throw ValidationException::withMessages([
                'email' => trans_choice('commerce-users::auth.login.throttled', RateLimiter::availableIn($key)),
            ]);
        }

        $user = CommerceUser::where('email', $email)->first();

        if (!$user || !Hash::check($data['password'], $user->password)) {
            RateLimiter::hit($key, 60);
            throw ValidationException::withMessages(['email' => __('commerce-users::auth.login.failed')]);
        }

        if (!$user->is_active) {
            throw ValidationException::withMessages(['email' => __('commerce-users::auth.login.disabled')]);
        }

        if (!$user->isVerified()) {
            return back()->withInput($request->only('email'))
                ->with('unverified_email', $user->email)
                ->withErrors(['email' => __('commerce-users::auth.login.unverified')]);
        }

        RateLimiter::clear($key);

        Auth::guard(CommerceUsers::GUARD)->login($user, $request->boolean('remember'));
        $request->session()->regenerate();
        $user->forceFill(['last_login_at' => now()])->save();

        return redirect()->intended(CommerceUsers::route('commerce-users.account'))->with('success', __('commerce-users::auth.login.success'));
    }

    public function logout(Request $request)
    {
        Auth::guard(CommerceUsers::GUARD)->logout();

        // A teljes munkamenet törlődik (kosár, megjegyzett rendelések): közös gépen se maradjon
        // ott semmi az előző vásárlótól.
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/')->with('success', __('commerce-users::auth.login.logged_out'));
    }

    /** Csak a saját oldalon belüli, relatív cím fogadható el (nyitott átirányítás ellen). */
    private function isSafeRedirect(?string $url): bool
    {
        return is_string($url) && str_starts_with($url, '/') && !str_starts_with($url, '//') && !str_contains($url, '\\');
    }
}
