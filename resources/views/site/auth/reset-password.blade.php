@extends('commerce-users::site.layouts.auth')
@section('title', __('commerce-users::auth.reset.title'))
@section('cu_title', __('commerce-users::auth.reset.title'))

@section('cu_content')
    <form method="POST" action="{{ route('commerce-users.password.update') }}" novalidate>
        @csrf
        <input type="hidden" name="token" value="{{ $token }}">
        <div class="form-group">
            <label for="cu_email">{{ __('commerce-users::common.fields.email') }} <span class="cu-req">*</span></label>
            <input type="email" name="email" id="cu_email" value="{{ $email }}" autocomplete="email"
                   class="form-control @error('email') is-invalid @enderror" required>
            @error('email') <div class="invalid-feedback">{{ $message }}</div> @enderror
        </div>

        @include('commerce-users::site.partials.password-field', [
            'name' => 'password', 'id' => 'cu_password', 'label' => __('commerce-users::common.fields.new_password'), 'autocomplete' => 'new-password',
            'hint' => __('commerce-users::common.password.min_hint', ['min' => $minPassword]),
        ])
        @include('commerce-users::site.partials.password-field', [
            'name' => 'password_confirmation', 'id' => 'cu_password_confirmation', 'label' => __('commerce-users::common.fields.new_password_confirmation'),
            'autocomplete' => 'new-password',
        ])

        <button type="submit" class="btn btn-primary btn-lg cu-btn-block">{{ __('commerce-users::common.password.save') }}</button>
    </form>
@endsection

@section('cu_after')
    <p class="cu-auth-alt"><a href="{{ route('commerce-users.login') }}">{{ __('commerce-users::common.back_to_login') }}</a></p>
@endsection
