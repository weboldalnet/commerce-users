@extends('commerce-users::site.layouts.auth')
@section('title', 'Bejelentkezés')
@section('cu_title', 'Bejelentkezés')
@section('cu_lead', 'Lépj be a fiókodba a rendeléseid és a mentett címeid eléréséhez.')

@section('cu_content')
    <form method="POST" action="{{ route('commerce-users.login.submit') }}" novalidate>
        @csrf
        <div class="form-group">
            <label for="cu_email">E-mail-cím <span class="cu-req">*</span></label>
            <input type="email" name="email" id="cu_email" value="{{ $email }}" autocomplete="email"
                   class="form-control @error('email') is-invalid @enderror" required autofocus>
            @error('email') <div class="invalid-feedback">{{ $message }}</div> @enderror
        </div>

        @include('commerce-users::site.partials.password-field', [
            'name' => 'password', 'id' => 'cu_password', 'label' => 'Jelszó', 'autocomplete' => 'current-password',
        ])

        <div class="d-flex flex-wrap align-items-center justify-content-between mb-3">
            <div class="custom-control custom-checkbox mb-2">
                <input type="checkbox" class="custom-control-input" id="cu_remember" name="remember" value="1" @checked(old('remember'))>
                <label class="custom-control-label font-weight-normal" for="cu_remember">Maradjak bejelentkezve</label>
            </div>
            <a href="{{ route('commerce-users.password.request') }}" class="cu-link mb-2">Elfelejtett jelszó?</a>
        </div>

        <button type="submit" class="btn btn-primary btn-lg cu-btn-block">Bejelentkezés</button>
    </form>

    @if(session('unverified_email'))
        <div class="cu-divider"></div>
        <p class="mb-2">Nem kaptad meg az aktiváló levelet?</p>
        <form method="POST" action="{{ route('commerce-users.verification.resend') }}">
            @csrf
            <input type="hidden" name="email" value="{{ session('unverified_email') }}">
            <button type="submit" class="btn btn-outline-primary cu-btn-block">Aktiváló levél újraküldése</button>
        </form>
    @endif
@endsection

@section('cu_after')
    <p class="cu-auth-alt">Még nincs fiókod? <a href="{{ route('commerce-users.register') }}">Regisztrálj</a></p>
@endsection
