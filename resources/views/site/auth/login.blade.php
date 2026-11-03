@extends('commerce-users::site.layouts.auth')
@section('title', __('commerce-users::common.login'))
@section('cu_title', __('commerce-users::common.login'))
@section('cu_lead', __('commerce-users::auth.login.lead'))

@section('cu_content')
    <form method="POST" action="{{ \Weboldalnet\CommerceUsers\CommerceUsers::route('commerce-users.login.submit') }}" novalidate>
        @csrf
        <div class="form-group">
            <label for="cu_email">{{ __('commerce-users::common.fields.email') }} <span class="cu-req">*</span></label>
            <input type="email" name="email" id="cu_email" value="{{ $email }}" autocomplete="email"
                   class="form-control @error('email') is-invalid @enderror" required autofocus>
            @error('email') <div class="invalid-feedback">{{ $message }}</div> @enderror
        </div>

        @include('commerce-users::site.partials.password-field', [
            'name' => 'password', 'id' => 'cu_password', 'label' => __('commerce-users::common.fields.password'), 'autocomplete' => 'current-password',
        ])

        <div class="d-flex flex-wrap align-items-center justify-content-between mb-3">
            <div class="custom-control custom-checkbox mb-2">
                <input type="checkbox" class="custom-control-input" id="cu_remember" name="remember" value="1" @checked(old('remember'))>
                <label class="custom-control-label font-weight-normal" for="cu_remember">{{ __('commerce-users::auth.login.remember') }}</label>
            </div>
            <a href="{{ \Weboldalnet\CommerceUsers\CommerceUsers::route('commerce-users.password.request') }}" class="cu-link mb-2">{{ __('commerce-users::auth.login.forgot_link') }}</a>
        </div>

        <button type="submit" class="btn btn-primary btn-lg cu-btn-block">{{ __('commerce-users::common.login') }}</button>
    </form>

    @if(session('unverified_email'))
        <div class="cu-divider"></div>
        <p class="mb-2">{{ __('commerce-users::auth.login.no_activation_mail') }}</p>
        <form method="POST" action="{{ \Weboldalnet\CommerceUsers\CommerceUsers::route('commerce-users.verification.resend') }}">
            @csrf
            <input type="hidden" name="email" value="{{ session('unverified_email') }}">
            <button type="submit" class="btn btn-outline-primary cu-btn-block">{{ __('commerce-users::common.resend_activation') }}</button>
        </form>
    @endif
@endsection

@section('cu_after')
    <p class="cu-auth-alt">{{ __('commerce-users::auth.login.no_account') }} <a href="{{ \Weboldalnet\CommerceUsers\CommerceUsers::route('commerce-users.register') }}">{{ __('commerce-users::auth.login.register_link') }}</a></p>
@endsection
