@extends('commerce-users::site.layouts.auth')
@section('title', 'Lejárt aktiváló link')
@section('cu_icon', 'fa-hourglass-end')
@section('cu_title', 'A link érvénytelen vagy lejárt')
@section('cu_lead', 'Az aktiváló link csak korlátozott ideig érvényes. Add meg az e-mail-címed, és küldünk egy újat.')

@section('cu_content')
    <form method="POST" action="{{ route('commerce-users.verification.resend') }}" novalidate>
        @csrf
        <div class="form-group">
            <label for="cu_email">E-mail-cím <span class="cu-req">*</span></label>
            <input type="email" name="email" id="cu_email" value="{{ old('email') }}" autocomplete="email"
                   class="form-control @error('email') is-invalid @enderror" required>
            @error('email') <div class="invalid-feedback">{{ $message }}</div> @enderror
        </div>
        <button type="submit" class="btn btn-primary cu-btn-block">Új aktiváló levél kérése</button>
    </form>
@endsection

@section('cu_after')
    <p class="cu-auth-alt"><a href="{{ route('commerce-users.login') }}">Vissza a bejelentkezéshez</a></p>
@endsection
