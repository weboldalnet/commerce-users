@extends('commerce-users::site.layouts.auth')
@section('title', __('commerce-users::auth.activation.expired_title'))
@section('cu_icon', 'fa-hourglass-end')
@section('cu_title', __('commerce-users::auth.activation.expired_heading'))
@section('cu_lead', __('commerce-users::auth.activation.expired_lead'))

@section('cu_content')
    <form method="POST" action="{{ route('commerce-users.verification.resend') }}" novalidate>
        @csrf
        <div class="form-group">
            <label for="cu_email">{{ __('commerce-users::common.fields.email') }} <span class="cu-req">*</span></label>
            <input type="email" name="email" id="cu_email" value="{{ old('email') }}" autocomplete="email"
                   class="form-control @error('email') is-invalid @enderror" required>
            @error('email') <div class="invalid-feedback">{{ $message }}</div> @enderror
        </div>
        <button type="submit" class="btn btn-primary cu-btn-block">{{ __('commerce-users::auth.activation.request_new') }}</button>
    </form>
@endsection

@section('cu_after')
    <p class="cu-auth-alt"><a href="{{ route('commerce-users.login') }}">{{ __('commerce-users::common.back_to_login') }}</a></p>
@endsection
