@extends('commerce-users::site.layouts.account')
@section('title', __('commerce-users::account.profile.title'))
@section('cu_title', __('commerce-users::account.profile.title'))

@section('cu_content')
    <div class="cu-card">
        <div class="cu-card-title"><h2>{{ __('commerce-users::account.profile.details') }}</h2></div>
        <form method="POST" action="{{ route('commerce-users.account.update') }}" novalidate>
            @csrf @method('PUT')
            <div class="form-row">
                <div class="form-group col-sm-6">
                    <label for="cu_last_name">{{ __('commerce-users::common.fields.last_name') }} <span class="cu-req">*</span></label>
                    <input type="text" name="last_name" id="cu_last_name" value="{{ old('last_name', $user->last_name) }}"
                           autocomplete="family-name" class="form-control @error('last_name') is-invalid @enderror" maxlength="100" required>
                    @error('last_name') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
                <div class="form-group col-sm-6">
                    <label for="cu_first_name">{{ __('commerce-users::common.fields.first_name') }} <span class="cu-req">*</span></label>
                    <input type="text" name="first_name" id="cu_first_name" value="{{ old('first_name', $user->first_name) }}"
                           autocomplete="given-name" class="form-control @error('first_name') is-invalid @enderror" maxlength="100" required>
                    @error('first_name') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
            </div>
            <div class="form-row">
                <div class="form-group col-sm-6">
                    <label for="cu_email">{{ __('commerce-users::common.fields.email') }}</label>
                    <input type="email" id="cu_email" value="{{ $user->email }}" class="form-control" readonly aria-describedby="cu_email_hint">
                    <span class="cu-hint" id="cu_email_hint">{{ __('commerce-users::account.profile.email_hint') }}</span>
                </div>
                <div class="form-group col-sm-6">
                    <label for="cu_phone">{{ __('commerce-users::common.fields.phone') }}</label>
                    <input type="tel" name="phone" id="cu_phone" value="{{ old('phone', $user->phone) }}" autocomplete="tel"
                           class="form-control @error('phone') is-invalid @enderror" maxlength="30" placeholder="+36 30 123 4567">
                    @error('phone') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    <span class="cu-hint">{{ __('commerce-users::account.profile.phone_hint') }}</span>
                </div>
            </div>
            <button type="submit" class="btn btn-primary">{{ __('commerce-users::account.profile.save') }}</button>
        </form>
    </div>

    <div class="cu-card">
        <div class="cu-card-title"><h2>{{ __('commerce-users::account.profile.account') }}</h2></div>
        <dl class="cu-data">
            <dt>{{ __('commerce-users::account.profile.registered_at') }}</dt>
            <dd>{{ $user->created_at?->format(__('commerce-users::common.date')) }}</dd>
            <dt>{{ __('commerce-users::account.profile.last_login_at') }}</dt>
            <dd>{{ $user->last_login_at ? $user->last_login_at->format(__('commerce-users::common.datetime')) : '–' }}</dd>
            @if($addressesEnabled)
                <dt>{{ __('commerce-users::account.profile.shipping_address') }}</dt>
                <dd>{{ $defaultShipping?->one_line ?? __('commerce-users::account.profile.not_saved') }}</dd>
                <dt>{{ __('commerce-users::account.profile.billing_address') }}</dt>
                <dd>{{ $defaultBilling?->one_line ?? __('commerce-users::account.profile.not_saved') }}</dd>
            @endif
        </dl>
        @if($addressesEnabled)
            <a href="{{ route('commerce-users.addresses.index') }}" class="btn btn-outline-primary mt-3">{{ __('commerce-users::account.profile.manage_addresses') }}</a>
        @endif
    </div>
@endsection
