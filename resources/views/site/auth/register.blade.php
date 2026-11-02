@extends('commerce-users::site.layouts.auth')
@section('title', 'Regisztráció')
@section('cu_title', 'Regisztráció')
@section('cu_lead', 'Fiókkal gyorsabb a vásárlás: a címeid elmentheted, a rendeléseid egy helyen látod.')
@section('cu_card_class', 'is-wide')

@section('cu_content')
    <form method="POST" action="{{ route('commerce-users.register.submit') }}" novalidate>
        @csrf
        <div class="form-row">
            <div class="form-group col-sm-6">
                <label for="cu_last_name">Vezetéknév <span class="cu-req">*</span></label>
                <input type="text" name="last_name" id="cu_last_name" value="{{ old('last_name') }}" autocomplete="family-name"
                       class="form-control @error('last_name') is-invalid @enderror" maxlength="100" required>
                @error('last_name') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>
            <div class="form-group col-sm-6">
                <label for="cu_first_name">Keresztnév <span class="cu-req">*</span></label>
                <input type="text" name="first_name" id="cu_first_name" value="{{ old('first_name') }}" autocomplete="given-name"
                       class="form-control @error('first_name') is-invalid @enderror" maxlength="100" required>
                @error('first_name') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>
        </div>

        <div class="form-group">
            <label for="cu_email">E-mail-cím <span class="cu-req">*</span></label>
            <input type="email" name="email" id="cu_email" value="{{ old('email') }}" autocomplete="email"
                   class="form-control @error('email') is-invalid @enderror" maxlength="255" required>
            @error('email')
                <div class="invalid-feedback">
                    {{ $message }}
                    @if(str_contains($message, 'már van fiók'))
                        <a href="{{ route('commerce-users.login', ['email' => old('email')]) }}">Bejelentkezés</a> ·
                        <a href="{{ route('commerce-users.password.request') }}">Új jelszó</a>
                    @endif
                </div>
            @enderror
            <span class="cu-hint">Erre a címre küldjük az aktiváló levelet. Később nem módosítható.</span>
        </div>

        <div class="form-row">
            <div class="col-sm-6">
                @include('commerce-users::site.partials.password-field', [
                    'name' => 'password', 'id' => 'cu_password', 'label' => 'Jelszó', 'autocomplete' => 'new-password',
                    'hint' => 'Legalább ' . $minPassword . ' karakter.',
                ])
            </div>
            <div class="col-sm-6">
                @include('commerce-users::site.partials.password-field', [
                    'name' => 'password_confirmation', 'id' => 'cu_password_confirmation', 'label' => 'Jelszó még egyszer',
                    'autocomplete' => 'new-password',
                ])
            </div>
        </div>

        {{-- csapda-mező: ember nem látja és nem tölti ki --}}
        <div class="cu-hp" aria-hidden="true">
            <label for="cu_website">Weboldal</label>
            <input type="text" name="website" id="cu_website" tabindex="-1" autocomplete="off">
        </div>

        <div class="form-group">
            <div class="custom-control custom-checkbox">
                <input type="checkbox" class="custom-control-input @error('accept_privacy') is-invalid @enderror" id="cu_accept_privacy"
                       name="accept_privacy" value="1" @checked(old('accept_privacy')) required>
                <label class="custom-control-label font-weight-normal" for="cu_accept_privacy">
                    Elolvastam és elfogadom az
                    @if($privacyUrl)
                        <a href="{{ $privacyUrl }}" target="_blank" rel="noopener" class="cu-link">adatkezelési tájékoztatót</a>.
                    @else
                        adatkezelési tájékoztatót.
                    @endif
                    <span class="cu-req">*</span>
                </label>
                @error('accept_privacy') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
            </div>
        </div>

        <button type="submit" class="btn btn-primary btn-lg cu-btn-block">Regisztráció</button>
    </form>
@endsection

@section('cu_after')
    <p class="cu-auth-alt">Van már fiókod? <a href="{{ route('commerce-users.login') }}">Jelentkezz be</a></p>
@endsection
