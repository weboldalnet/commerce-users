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
            return redirect()->route('commerce-users.register.done');
        }

        $key = 'commerce-users-register|' . $request->ip();
        if (RateLimiter::tooManyAttempts($key, 10)) {
            throw ValidationException::withMessages(['email' => 'Túl sok regisztráció erről a hálózatról. Próbáld újra később.']);
        }

        $request->merge(['email' => mb_strtolower(trim((string) $request->input('email')))]);

        $data = $request->validate([
            'last_name' => 'required|string|max:100',
            'first_name' => 'required|string|max:100',
            'email' => ['required', 'email', 'max:255', Rule::unique(CommerceUser::class, 'email')],
            'password' => 'required|string|confirmed|min:' . self::minPasswordLength() . '|max:255',
            'accept_privacy' => 'accepted',
        ], [
            'last_name.required' => 'Add meg a vezetékneved.',
            'first_name.required' => 'Add meg a keresztneved.',
            'email.required' => 'Add meg az e-mail-címed.',
            'email.email' => 'Ez nem érvényes e-mail-cím.',
            'email.unique' => 'Ezzel az e-mail-címmel már van fiók. Jelentkezz be, vagy kérj új jelszót.',
            'password.required' => 'Adj meg egy jelszót.',
            'password.confirmed' => 'A két jelszó nem egyezik.',
            'password.min' => 'A jelszó legalább :min karakter legyen.',
            'accept_privacy' => 'A regisztrációhoz el kell fogadnod az adatkezelési tájékoztatót.',
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

        return redirect()->route('commerce-users.register.done')->with('registered_email', $user->email);
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
            return redirect()->route('commerce-users.login')->with('verified_email', $user->email)
                ->with('info', 'A fiókod már aktív, bejelentkezhetsz.');
        }

        ActivationService::activate($user);

        return redirect()->route('commerce-users.login')->with('verified_email', $user->email)
            ->with('success', 'Sikeres aktiválás! Most már bejelentkezhetsz.');
    }

    /** Aktiváló levél újraküldése. A válasz mindig ugyanaz: nem árulja el, van-e ilyen fiók. */
    public function resend(Request $request)
    {
        $data = $request->validate(['email' => 'required|email|max:255'], [
            'email.required' => 'Add meg az e-mail-címed.',
            'email.email' => 'Ez nem érvényes e-mail-cím.',
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

        return back()->with('success', 'Ha ezzel a címmel van aktiválásra váró fiók, újra elküldtük az aktiváló levelet.');
    }

    public static function minPasswordLength(): int
    {
        return max(6, (int) config('commerce-users.password_min_length', 8));
    }
}
