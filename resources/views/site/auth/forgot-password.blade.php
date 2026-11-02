@extends('commerce-users::site.layouts.auth')
@section('title', 'Elfelejtett jelszó')
@section('cu_title', 'Elfelejtett jelszó')
@section('cu_lead', 'Add meg a fiókodhoz tartozó e-mail-címet, és küldünk egy linket, amellyel új jelszót állíthatsz be.')

@section('cu_content')
    <form method="POST" action="{{ route('commerce-users.password.email') }}" novalidate>
        @csrf
        <div class="form-group">
            <label for="cu_email">E-mail-cím <span class="cu-req">*</span></label>
            <input type="email" name="email" id="cu_email" value="{{ old('email') }}" autocomplete="email"
                   class="form-control @error('email') is-invalid @enderror" required autofocus>
            @error('email') <div class="invalid-feedback">{{ $message }}</div> @enderror
        </div>
        <button type="submit" class="btn btn-primary btn-lg cu-btn-block">Link küldése</button>
    </form>
@endsection

@section('cu_after')
    <p class="cu-auth-alt"><a href="{{ route('commerce-users.login') }}">Vissza a bejelentkezéshez</a></p>
@endsection
