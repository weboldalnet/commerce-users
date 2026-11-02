@extends('commerce-users::site.layouts.account')
@php
    $cuIsBilling = $type === \Weboldalnet\CommerceUsers\Models\CommerceUserAddress::TYPE_BILLING;
    $cuTitle = $address->exists
        ? ($cuIsBilling ? 'Számlázási cím szerkesztése' : 'Szállítási cím szerkesztése')
        : ($cuIsBilling ? 'Új számlázási cím' : 'Új szállítási cím');
@endphp
@section('title', $cuTitle)
@section('cu_title', $cuTitle)

@section('cu_content')
    <div class="cu-card">
        <form method="POST" novalidate
              action="{{ $address->exists ? route('commerce-users.addresses.update', $address->id) : route('commerce-users.addresses.store', $type) }}">
            @csrf
            @if($address->exists) @method('PUT') @endif

            <div class="form-row">
                <div class="form-group col-md-6">
                    <label for="cu_name">{{ $cuIsBilling ? 'Számlázási név' : 'Címzett neve' }} <span class="cu-req">*</span></label>
                    <input type="text" name="name" id="cu_name" value="{{ old('name', $address->name) }}" maxlength="255"
                           autocomplete="name" class="form-control @error('name') is-invalid @enderror" required>
                    @error('name') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    @if($cuIsBilling)
                        <span class="cu-hint">Magánszemélyként a neved, cégként a cégnév.</span>
                    @endif
                </div>
                <div class="form-group col-md-6">
                    <label for="cu_label">Megnevezés</label>
                    <input type="text" name="label" id="cu_label" value="{{ old('label', $address->label) }}" maxlength="100"
                           class="form-control @error('label') is-invalid @enderror" placeholder="{{ $cuIsBilling ? 'pl. Cég' : 'pl. Otthon, Iroda' }}">
                    @error('label') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    <span class="cu-hint">Nem kötelező — csak neked segít a címek között.</span>
                </div>
            </div>

            @if($cuIsBilling)
                <div class="form-row">
                    <div class="form-group col-md-6">
                        <label for="cu_company">Cégnév</label>
                        <input type="text" name="company" id="cu_company" value="{{ old('company', $address->company) }}" maxlength="255"
                               autocomplete="organization" class="form-control @error('company') is-invalid @enderror">
                        @error('company') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                    <div class="form-group col-md-6">
                        <label for="cu_tax_number">Adószám</label>
                        <input type="text" name="tax_number" id="cu_tax_number" value="{{ old('tax_number', $address->tax_number) }}" maxlength="30"
                               class="form-control @error('tax_number') is-invalid @enderror" placeholder="12345678-1-12">
                        @error('tax_number') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        <span class="cu-hint">Céges számlához.</span>
                    </div>
                </div>
            @endif

            <div class="form-row">
                <div class="form-group col-sm-4 col-md-3">
                    <label for="cu_zip">Irányítószám <span class="cu-req">*</span></label>
                    <input type="text" name="zip" id="cu_zip" value="{{ old('zip', $address->zip) }}" maxlength="10" inputmode="numeric"
                           autocomplete="postal-code" class="form-control @error('zip') is-invalid @enderror" required>
                    @error('zip') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
                <div class="form-group col-sm-8 col-md-9">
                    <label for="cu_city">Település <span class="cu-req">*</span></label>
                    <input type="text" name="city" id="cu_city" value="{{ old('city', $address->city) }}" maxlength="100"
                           autocomplete="address-level2" class="form-control @error('city') is-invalid @enderror" required>
                    @error('city') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
            </div>
            <div class="form-group">
                <label for="cu_address">Utca, házszám <span class="cu-req">*</span></label>
                <input type="text" name="address" id="cu_address" value="{{ old('address', $address->address) }}" maxlength="255"
                       autocomplete="street-address" class="form-control @error('address') is-invalid @enderror" required>
                @error('address') <div class="invalid-feedback">{{ $message }}</div> @enderror
                <span class="cu-hint">Emelet, ajtó is ide jöhet.</span>
            </div>

            @unless($cuIsBilling)
                <div class="form-group" style="max-width: 320px">
                    <label for="cu_phone">Telefonszám</label>
                    <input type="tel" name="phone" id="cu_phone" value="{{ old('phone', $address->phone) }}" maxlength="30"
                           autocomplete="tel" class="form-control @error('phone') is-invalid @enderror">
                    @error('phone') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    <span class="cu-hint">A futár ezen keresi a címzettet.</span>
                </div>
            @endunless

            @if($isFirst)
                <p class="cu-hint mb-3">Ez lesz az elsődleges {{ $cuIsBilling ? 'számlázási' : 'szállítási' }} címed.</p>
            @elseif(!$address->is_default)
                <div class="custom-control custom-checkbox mb-3">
                    <input type="checkbox" class="custom-control-input" id="cu_is_default" name="is_default" value="1" @checked(old('is_default'))>
                    <label class="custom-control-label font-weight-normal" for="cu_is_default">Legyen ez az elsődleges cím</label>
                </div>
            @endif

            <div class="d-flex flex-wrap gap-2">
                <button type="submit" class="btn btn-primary mr-2 mb-2">Cím mentése</button>
                <a href="{{ route('commerce-users.addresses.index') }}" class="btn btn-outline-secondary mb-2">Mégse</a>
            </div>
        </form>
    </div>
@endsection
