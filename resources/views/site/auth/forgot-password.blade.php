@extends('commerce-users::site.layouts.auth')
@section('title', __('commerce-users::auth.forgot.title'))
@section('cu_title', __('commerce-users::auth.forgot.title'))
@section('cu_lead', __('commerce-users::auth.forgot.lead'))

@section('cu_content')
    <form method="POST" action="{{ \Weboldalnet\CommerceUsers\CommerceUsers::route('commerce-users.password.email') }}" novalidate>
        @csrf
        <div class="form-group">
            <label for="cu_email">{{ __('commerce-users::common.fields.email') }} <span class="cu-req">*</span></label>
            <input type="email" name="email" id="cu_email" value="{{ old('email') }}" autocomplete="email"
                   class="form-control @error('email') is-invalid @enderror" required autofocus>
            @error('email') <div class="invalid-feedback">{{ $message }}</div> @enderror
        </div>
        <button type="submit" class="btn btn-primary btn-lg cu-btn-block">{{ __('commerce-users::auth.forgot.submit') }}</button>
    </form>
@endsection

@section('cu_after')
    <p class="cu-auth-alt"><a href="{{ \Weboldalnet\CommerceUsers\CommerceUsers::route('commerce-users.login') }}">{{ __('commerce-users::common.back_to_login') }}</a></p>
@endsection
