@extends('commerce-users::site.layouts.auth')
@section('title', 'Aktiváld a fiókod')
@section('cu_icon', 'fa-envelope-circle-check')
@section('cu_title', 'Már csak egy lépés')
@section('cu_lead')
    @if($email)
        Elküldtük az aktiváló levelet a(z) <strong>{{ $email }}</strong> címre.
    @else
        Elküldtük az aktiváló levelet a megadott címre.
    @endif
    Kattints a benne lévő linkre, és utána bejelentkezhetsz.
@endsection

@section('cu_content')
    <p class="cu-hint text-center mb-0">
        A link {{ $expireHours }} óráig érvényes. Ha néhány percen belül nem érkezik meg, nézd meg a levélszemét
        (spam) mappát is, vagy kérj újat.
    </p>

    <div class="cu-divider"></div>

    <form method="POST" action="{{ route('commerce-users.verification.resend') }}" novalidate>
        @csrf
        <div class="form-group">
            <label for="cu_email">E-mail-cím</label>
            <input type="email" name="email" id="cu_email" value="{{ old('email', $email) }}" autocomplete="email"
                   class="form-control @error('email') is-invalid @enderror" required>
            @error('email') <div class="invalid-feedback">{{ $message }}</div> @enderror
        </div>
        <button type="submit" class="btn btn-outline-primary cu-btn-block">Aktiváló levél újraküldése</button>
    </form>
@endsection

@section('cu_after')
    <p class="cu-auth-alt"><a href="{{ route('commerce-users.login') }}">Tovább a bejelentkezéshez</a></p>
@endsection
