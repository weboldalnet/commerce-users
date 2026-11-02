@extends('commerce-users::site.layouts.auth')
@section('title', 'Új jelszó beállítása')
@section('cu_title', 'Új jelszó beállítása')

@section('cu_content')
    <form method="POST" action="{{ route('commerce-users.password.update') }}" novalidate>
        @csrf
        <input type="hidden" name="token" value="{{ $token }}">
        <div class="form-group">
            <label for="cu_email">E-mail-cím <span class="cu-req">*</span></label>
            <input type="email" name="email" id="cu_email" value="{{ $email }}" autocomplete="email"
                   class="form-control @error('email') is-invalid @enderror" required>
            @error('email') <div class="invalid-feedback">{{ $message }}</div> @enderror
        </div>

        @include('commerce-users::site.partials.password-field', [
            'name' => 'password', 'id' => 'cu_password', 'label' => 'Új jelszó', 'autocomplete' => 'new-password',
            'hint' => 'Legalább ' . $minPassword . ' karakter.',
        ])
        @include('commerce-users::site.partials.password-field', [
            'name' => 'password_confirmation', 'id' => 'cu_password_confirmation', 'label' => 'Új jelszó még egyszer',
            'autocomplete' => 'new-password',
        ])

        <button type="submit" class="btn btn-primary btn-lg cu-btn-block">Jelszó mentése</button>
    </form>
@endsection

@section('cu_after')
    <p class="cu-auth-alt"><a href="{{ route('commerce-users.login') }}">Vissza a bejelentkezéshez</a></p>
@endsection
