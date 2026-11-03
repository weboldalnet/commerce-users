@extends('commerce-users::site.layouts.account')
@php
    $cuIsBilling = $type === \Weboldalnet\CommerceUsers\Models\CommerceUserAddress::TYPE_BILLING;
    $cuTitle = $address->exists
        ? ($cuIsBilling ? __('commerce-users::account.address_form.edit_billing') : __('commerce-users::account.address_form.edit_shipping'))
        : ($cuIsBilling ? __('commerce-users::account.addresses.new_billing') : __('commerce-users::account.addresses.new_shipping'));
@endphp
@section('title', $cuTitle)
@section('cu_title', $cuTitle)

@section('cu_content')
    <div class="cu-card">
        <form method="POST" novalidate
              action="{{ $address->exists ? \Weboldalnet\CommerceUsers\CommerceUsers::route('commerce-users.addresses.update', $address->id) : \Weboldalnet\CommerceUsers\CommerceUsers::route('commerce-users.addresses.store', $type) }}">
            @csrf
            @if($address->exists) @method('PUT') @endif

            <div class="form-row">
                <div class="form-group col-md-6">
                    <label for="cu_name">{{ $cuIsBilling ? __('commerce-users::account.address_form.billing_name') : __('commerce-users::account.address_form.recipient_name') }} <span class="cu-req">*</span></label>
                    <input type="text" name="name" id="cu_name" value="{{ old('name', $address->name) }}" maxlength="255"
                           autocomplete="name" class="form-control @error('name') is-invalid @enderror" required>
                    @error('name') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    @if($cuIsBilling)
                        <span class="cu-hint">{{ __('commerce-users::account.address_form.billing_name_hint') }}</span>
                    @endif
                </div>
                <div class="form-group col-md-6">
                    <label for="cu_label">{{ __('commerce-users::account.address_form.label') }}</label>
                    <input type="text" name="label" id="cu_label" value="{{ old('label', $address->label) }}" maxlength="100"
                           class="form-control @error('label') is-invalid @enderror" placeholder="{{ $cuIsBilling ? __('commerce-users::account.address_form.label_placeholder_billing') : __('commerce-users::account.address_form.label_placeholder_shipping') }}">
                    @error('label') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    <span class="cu-hint">{{ __('commerce-users::account.address_form.label_hint') }}</span>
                </div>
            </div>

            @if($cuIsBilling)
                <div class="form-row">
                    <div class="form-group col-md-6">
                        <label for="cu_company">{{ __('commerce-users::account.address_form.company') }}</label>
                        <input type="text" name="company" id="cu_company" value="{{ old('company', $address->company) }}" maxlength="255"
                               autocomplete="organization" class="form-control @error('company') is-invalid @enderror">
                        @error('company') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                    <div class="form-group col-md-6">
                        <label for="cu_tax_number">{{ __('commerce-users::account.address_form.tax_number') }}</label>
                        <input type="text" name="tax_number" id="cu_tax_number" value="{{ old('tax_number', $address->tax_number) }}" maxlength="30"
                               class="form-control @error('tax_number') is-invalid @enderror" placeholder="12345678-1-12">
                        @error('tax_number') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        <span class="cu-hint">{{ __('commerce-users::account.address_form.tax_number_hint') }}</span>
                    </div>
                </div>
            @endif

            <div class="form-row">
                <div class="form-group col-sm-4 col-md-3">
                    <label for="cu_zip">{{ __('commerce-users::account.address_form.zip') }} <span class="cu-req">*</span></label>
                    <input type="text" name="zip" id="cu_zip" value="{{ old('zip', $address->zip) }}" maxlength="10" inputmode="numeric"
                           autocomplete="postal-code" class="form-control @error('zip') is-invalid @enderror" required>
                    @error('zip') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
                <div class="form-group col-sm-8 col-md-9">
                    <label for="cu_city">{{ __('commerce-users::account.address_form.city') }} <span class="cu-req">*</span></label>
                    <input type="text" name="city" id="cu_city" value="{{ old('city', $address->city) }}" maxlength="100"
                           autocomplete="address-level2" class="form-control @error('city') is-invalid @enderror" required>
                    @error('city') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
            </div>
            <div class="form-group">
                <label for="cu_address">{{ __('commerce-users::account.address_form.address') }} <span class="cu-req">*</span></label>
                <input type="text" name="address" id="cu_address" value="{{ old('address', $address->address) }}" maxlength="255"
                       autocomplete="street-address" class="form-control @error('address') is-invalid @enderror" required>
                @error('address') <div class="invalid-feedback">{{ $message }}</div> @enderror
                <span class="cu-hint">{{ __('commerce-users::account.address_form.address_hint') }}</span>
            </div>

            @unless($cuIsBilling)
                <div class="form-group" style="max-width: 320px">
                    <label for="cu_phone">{{ __('commerce-users::common.fields.phone') }}</label>
                    <input type="tel" name="phone" id="cu_phone" value="{{ old('phone', $address->phone) }}" maxlength="30"
                           autocomplete="tel" class="form-control @error('phone') is-invalid @enderror">
                    @error('phone') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    <span class="cu-hint">{{ __('commerce-users::account.address_form.phone_hint') }}</span>
                </div>
            @endunless

            @if($isFirst)
                <p class="cu-hint mb-3">{{ $cuIsBilling ? __('commerce-users::account.address_form.first_billing') : __('commerce-users::account.address_form.first_shipping') }}</p>
            @elseif(!$address->is_default)
                <div class="custom-control custom-checkbox mb-3">
                    <input type="checkbox" class="custom-control-input" id="cu_is_default" name="is_default" value="1" @checked(old('is_default'))>
                    <label class="custom-control-label font-weight-normal" for="cu_is_default">{{ __('commerce-users::account.address_form.set_default') }}</label>
                </div>
            @endif

            <div class="d-flex flex-wrap gap-2">
                <button type="submit" class="btn btn-primary mr-2 mb-2">{{ __('commerce-users::account.address_form.save') }}</button>
                <a href="{{ \Weboldalnet\CommerceUsers\CommerceUsers::route('commerce-users.addresses.index') }}" class="btn btn-outline-secondary mb-2">{{ __('commerce-users::account.address_form.cancel') }}</a>
            </div>
        </form>
    </div>
@endsection
