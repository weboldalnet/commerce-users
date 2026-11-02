<?php

namespace Weboldalnet\CommerceUsers\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Weboldalnet\CommerceUsers\Models\CommerceUser;
use Weboldalnet\CommerceUsers\Services\ActivationService;

/** Vásárlói fiókok az adminban: lista, adatlap, aktiválás, letiltás, törlés. */
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
