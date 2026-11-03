@extends('commerce-users::site.layouts.account')
@section('title', 'Profilom')
@section('cu_title', 'Profilom')

@section('cu_content')
    <div class="cu-card">
        <div class="cu-card-title"><h2>Adataim</h2></div>
        <form method="POST" action="{{ route('commerce-users.account.update') }}" novalidate>
            @csrf @method('PUT')
            <div class="form-row">
                <div class="form-group col-sm-6">
                    <label for="cu_last_name">Vezetéknév <span class="cu-req">*</span></label>
                    <input type="text" name="last_name" id="cu_last_name" value="{{ old('last_name', $user->last_name) }}"
                           autocomplete="family-name" class="form-control @error('last_name') is-invalid @enderror" maxlength="100" required>
                    @error('last_name') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
                <div class="form-group col-sm-6">
                    <label for="cu_first_name">Keresztnév <span class="cu-req">*</span></label>
                    <input type="text" name="first_name" id="cu_first_name" value="{{ old('first_name', $user->first_name) }}"
                           autocomplete="given-name" class="form-control @error('first_name') is-invalid @enderror" maxlength="100" required>
                    @error('first_name') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
            </div>
            <div class="form-row">
                <div class="form-group col-sm-6">
                    <label for="cu_email">E-mail-cím</label>
                    <input type="email" id="cu_email" value="{{ $user->email }}" class="form-control" readonly aria-describedby="cu_email_hint">
                    <span class="cu-hint" id="cu_email_hint">Az e-mail-cím a belépési azonosítód; ha módosítani szeretnéd, írj nekünk.</span>
                </div>
                <div class="form-group col-sm-6">
                    <label for="cu_phone">Telefonszám</label>
                    <input type="tel" name="phone" id="cu_phone" value="{{ old('phone', $user->phone) }}" autocomplete="tel"
                           class="form-control @error('phone') is-invalid @enderror" maxlength="30" placeholder="+36 30 123 4567">
                    @error('phone') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    <span class="cu-hint">A pénztárban ezzel töltjük ki a telefonszám mezőt.</span>
                </div>
            </div>
            <button type="submit" class="btn btn-primary">Mentés</button>
        </form>
    </div>

    <div class="cu-card">
        <div class="cu-card-title"><h2>Fiók</h2></div>
        <dl class="cu-data">
            <dt>Regisztráció</dt>
            <dd>{{ $user->created_at?->format('Y. m. d.') }}</dd>
            <dt>Utolsó belépés</dt>
            <dd>{{ $user->last_login_at ? $user->last_login_at->format('Y. m. d. H:i') : '–' }}</dd>
            @if($addressesEnabled)
                <dt>Szállítási cím</dt>
                <dd>{{ $defaultShipping?->one_line ?? 'Még nincs mentve' }}</dd>
                <dt>Számlázási cím</dt>
                <dd>{{ $defaultBilling?->one_line ?? 'Még nincs mentve' }}</dd>
            @endif
        </dl>
        @if($addressesEnabled)
            <a href="{{ route('commerce-users.addresses.index') }}" class="btn btn-outline-primary mt-3">Címeim kezelése</a>
        @endif
    </div>
@endsection
