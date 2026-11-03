@extends('commerce-users::site.layouts.auth')
@section('title', __('commerce-users::auth.register.title'))
@section('cu_title', __('commerce-users::auth.register.title'))
@section('cu_lead', __('commerce-users::auth.register.lead'))
@section('cu_card_class', 'is-wide')

@section('cu_content')
    <form method="POST" action="{{ route('commerce-users.register.submit') }}" novalidate>
        @csrf
        <div class="form-row">
            <div class="form-group col-sm-6">
                <label for="cu_last_name">{{ __('commerce-users::common.fields.last_name') }} <span class="cu-req">*</span></label>
                <input type="text" name="last_name" id="cu_last_name" value="{{ old('last_name') }}" autocomplete="family-name"
                       class="form-control @error('last_name') is-invalid @enderror" maxlength="100" required>
                @error('last_name') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>
            <div class="form-group col-sm-6">
                <label for="cu_first_name">{{ __('commerce-users::common.fields.first_name') }} <span class="cu-req">*</span></label>
                <input type="text" name="first_name" id="cu_first_name" value="{{ old('first_name') }}" autocomplete="given-name"
                       class="form-control @error('first_name') is-invalid @enderror" maxlength="100" required>
                @error('first_name') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>
        </div>

        <div class="form-group">
            <label for="cu_email">{{ __('commerce-users::common.fields.email') }} <span class="cu-req">*</span></label>
            <input type="email" name="email" id="cu_email" value="{{ old('email') }}" autocomplete="email"
                   class="form-control @error('email') is-invalid @enderror" maxlength="255" required>
            @error('email')
                <div class="invalid-feedback">
                    {{ $message }}
                    @if($message === __('commerce-users::auth.register.email_unique'))
                        <a href="{{ route('commerce-users.login', ['email' => old('email')]) }}">{{ __('commerce-users::common.login') }}</a> ·
                        <a href="{{ route('commerce-users.password.request') }}">{{ __('commerce-users::auth.register.new_password_link') }}</a>
                    @endif
                </div>
            @enderror
            <span class="cu-hint">{{ __('commerce-users::auth.register.email_hint') }}</span>
        </div>

        <div class="form-row">
            <div class="col-sm-6">
                @include('commerce-users::site.partials.password-field', [
                    'name' => 'password', 'id' => 'cu_password', 'label' => __('commerce-users::common.fields.password'), 'autocomplete' => 'new-password',
                    'hint' => __('commerce-users::common.password.min_hint', ['min' => $minPassword]),
                ])
            </div>
            <div class="col-sm-6">
                @include('commerce-users::site.partials.password-field', [
                    'name' => 'password_confirmation', 'id' => 'cu_password_confirmation', 'label' => __('commerce-users::common.fields.password_confirmation'),
                    'autocomplete' => 'new-password',
                ])
            </div>
        </div>

        {{-- csapda-mező: ember nem látja és nem tölti ki --}}
        <div class="cu-hp" aria-hidden="true">
            <label for="cu_website">{{ __('commerce-users::auth.register.honeypot') }}</label>
            <input type="text" name="website" id="cu_website" tabindex="-1" autocomplete="off">
        </div>

        <div class="form-group">
            <div class="custom-control custom-checkbox">
                <input type="checkbox" class="custom-control-input @error('accept_privacy') is-invalid @enderror" id="cu_accept_privacy"
                       name="accept_privacy" value="1" @checked(old('accept_privacy')) required>
                <label class="custom-control-label font-weight-normal" for="cu_accept_privacy">
                    @if($privacyUrl)
                        {!! __('commerce-users::auth.register.accept_privacy', ['link' => '<a href="' . e($privacyUrl) . '" target="_blank" rel="noopener" class="cu-link">' . e(__('commerce-users::auth.register.privacy_policy')) . '</a>']) !!}
                    @else
                        {{ __('commerce-users::auth.register.accept_privacy', ['link' => __('commerce-users::auth.register.privacy_policy')]) }}
                    @endif
                    <span class="cu-req">*</span>
                </label>
                @error('accept_privacy') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
            </div>
        </div>

        <button type="submit" class="btn btn-primary btn-lg cu-btn-block">{{ __('commerce-users::auth.register.submit') }}</button>
    </form>
@endsection

@section('cu_after')
    <p class="cu-auth-alt">{{ __('commerce-users::auth.register.has_account') }} <a href="{{ route('commerce-users.login') }}">{{ __('commerce-users::auth.register.login_link') }}</a></p>
@endsection
