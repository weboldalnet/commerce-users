@extends('commerce-users::site.layouts.auth')
@section('title', __('commerce-users::auth.register_done.title'))
@section('cu_icon', 'fa-envelope-circle-check')
@section('cu_title', __('commerce-users::auth.register_done.heading'))
@section('cu_lead')
    @if($email)
        {!! __('commerce-users::auth.register_done.sent_to', ['email' => '<strong>' . e($email) . '</strong>']) !!}
    @else
        {{ __('commerce-users::auth.register_done.sent') }}
    @endif
    {{ __('commerce-users::auth.register_done.click_link') }}
@endsection

@section('cu_content')
    <p class="cu-hint text-center mb-0">
        {{ trans_choice('commerce-users::auth.register_done.expiry', $expireHours) }}
    </p>

    <div class="cu-divider"></div>

    <form method="POST" action="{{ route('commerce-users.verification.resend') }}" novalidate>
        @csrf
        <div class="form-group">
            <label for="cu_email">{{ __('commerce-users::common.fields.email') }}</label>
            <input type="email" name="email" id="cu_email" value="{{ old('email', $email) }}" autocomplete="email"
                   class="form-control @error('email') is-invalid @enderror" required>
            @error('email') <div class="invalid-feedback">{{ $message }}</div> @enderror
        </div>
        <button type="submit" class="btn btn-outline-primary cu-btn-block">{{ __('commerce-users::common.resend_activation') }}</button>
    </form>
@endsection

@section('cu_after')
    <p class="cu-auth-alt"><a href="{{ route('commerce-users.login') }}">{{ __('commerce-users::auth.register_done.continue_to_login') }}</a></p>
@endsection
