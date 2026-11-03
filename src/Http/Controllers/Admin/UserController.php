<?php

namespace Weboldalnet\CommerceUsers\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Weboldalnet\CommerceUsers\CommerceUsers;
use Weboldalnet\CommerceUsers\Models\CommerceUser;
use Weboldalnet\CommerceUsers\Services\ActivationService;
use Weboldalnet\CommerceCore\Support\CommerceConnection;

/** Vásárlói fiókok az adminban: lista, adatlap, e-mail-cím módosítása, aktiválás, letiltás, törlés. */
class UserController extends Controller
{
    public function index(Request $request)
    {
        $users = CommerceUser::query()
            ->search($request->input('search'))
            ->status($request->input('status'))
            ->withCount('addresses')
            ->orderByDesc('id')
            ->paginate(25)
            ->withQueryString();

        return view('commerce-users::admin.users.index', [
            'users' => $users,
            'statuses' => CommerceUser::STATUSES,
            'total' => CommerceUser::count(),
        ]);
    }

    public function show(CommerceUser $user)
    {
        // NEM `user`: a core admin-layoutja a bejelentkezett admint várja ezen a néven
        return view('commerce-users::admin.users.show', [
            'customer' => $user,
            'shippingAddresses' => $user->shippingAddresses()->get(),
            'billingAddresses' => $user->billingAddresses()->get(),
        ]);
    }

    /**
     * Az e-mail-cím (a belépési azonosító) módosítása — a vásárló maga nem módosíthatja.
     *
     * Az új címet is igazolni kell: a fiók aktiválatlan lesz, és az új címre aktiváló levél megy;
     * addig a vásárló nem tud belépni (a bejelentkezett munkamenetét is kilépteti az auth
     * middleware). A régi címre kiküldött aktiváló link magától érvénytelen (a cím hash-ét
     * tartalmazza), a jelszó-visszaállító linkek törlődnek, az emlékező token megújul.
     */
    public function updateEmail(Request $request, CommerceUser $user)
    {
        $request->merge(['email' => mb_strtolower(trim((string) $request->input('email')))]);
        $data = $request->validate([
            'email' => ['required', 'email', 'max:255', Rule::unique(CommerceUser::class, 'email')->ignore($user->id)],
        ], [
            'email.required' => 'Add meg az új e-mail-címet.',
            'email.email' => 'Ez nem érvényes e-mail-cím.',
            'email.max' => 'Az e-mail-cím legfeljebb 255 karakter lehet.',
            'email.unique' => 'Ezzel az e-mail-címmel már van fiók.',
        ]);

        if ($data['email'] === $user->email) {
            return back()->with('info', 'Az e-mail-cím nem változott.');
        }

        $oldEmail = $user->email;

        CommerceConnection::db()->transaction(function () use ($user, $data) {
            // a régi címre kért jelszó-visszaállító linkek (a repository a jelenlegi címmel töröl)
            Password::broker(CommerceUsers::BROKER)->getRepository()->delete($user);

            $user->forceFill([
                'email' => $data['email'],
                'email_verified_at' => null,
                'remember_token' => Str::random(60),
            ])->save();
        });

        return ActivationService::send($user)
            ? back()->with('success', 'Az e-mail-cím módosult (' . $oldEmail . ' → ' . $user->email . '). Az új címre aktiváló levelet küldtünk; a vásárló a link megnyitásáig nem tud belépni.')
            : back()->with('error', 'Az e-mail-cím módosult (' . $user->email . '), de az aktiváló levél küldése nem sikerült — a részletek a naplóban. Újraküldés a Műveletek között.');
    }

    public function toggleActive(CommerceUser $user)
    {
        $user->is_active = !$user->is_active;
        $user->save();

        return back()->with('success', $user->is_active ? 'A fiókot újra engedélyeztük.' : 'A fiókot letiltottuk: a vásárló nem tud belépni.');
    }

    /** Kézi aktiválás, ha a vásárló nem kapta meg a levelet (pl. telefonon jelezte). */
    public function verify(CommerceUser $user)
    {
        ActivationService::activate($user);

        return back()->with('success', 'A fiókot aktiváltuk, a vásárló bejelentkezhet.');
    }

    public function resendActivation(CommerceUser $user)
    {
        if ($user->isVerified()) {
            return back()->with('error', 'Ez a fiók már aktív.');
        }

        return ActivationService::send($user)
            ? back()->with('success', 'Az aktiváló levelet újra elküldtük: ' . $user->email)
            : back()->with('error', 'A levél küldése nem sikerült, a részletek a naplóban.');
    }

    /**
     * A fiók és a címei törlődnek; a leadott rendelések megmaradnak (a webshop azokat
     * a fiók nélkül, a megrendelő adataival tárolja).
     */
    public function destroy(CommerceUser $user)
    {
        $email = $user->email;
        $user->addresses()->delete();
        $user->delete();

        return redirect()->route('admin.webshop.users.index')->with('success', 'A fiókot töröltük: ' . $email);
    }
}
