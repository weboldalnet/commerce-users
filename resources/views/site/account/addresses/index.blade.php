@extends('commerce-users::site.layouts.account')
@section('title', __('commerce-users::account.addresses.title'))
@section('cu_title', __('commerce-users::account.addresses.title'))

@section('cu_content')
    @foreach([
        'shipping' => ['title' => __('commerce-users::account.addresses.shipping_title'), 'addresses' => $shippingAddresses, 'new' => __('commerce-users::account.addresses.new_shipping'),
            'empty' => __('commerce-users::account.addresses.empty_shipping')],
        'billing' => ['title' => __('commerce-users::account.addresses.billing_title'), 'addresses' => $billingAddresses, 'new' => __('commerce-users::account.addresses.new_billing'),
            'empty' => __('commerce-users::account.addresses.empty_billing')],
    ] as $cuType => $cuGroup)
        <div class="cu-card">
            <div class="cu-card-title">
                <h2>{{ $cuGroup['title'] }}</h2>
                @if($cuGroup['addresses']->count() < $maxPerType)
                    <a href="{{ route('commerce-users.addresses.create', $cuType) }}" class="btn btn-sm btn-primary">
                        <i class="fa-solid fa-plus"></i> {{ $cuGroup['new'] }}
                    </a>
                @endif
            </div>

            @if($cuGroup['addresses']->isEmpty())
                <div class="cu-empty">{{ $cuGroup['empty'] }}</div>
            @else
                <div class="cu-address-grid">
                    @foreach($cuGroup['addresses'] as $cuAddress)
                        <div class="cu-address @if($cuAddress->is_default) is-default @endif">
                            <div class="cu-address-head">
                                <span class="cu-address-label">{{ $cuAddress->label ?: ($cuType === 'billing' ? __('commerce-users::account.addresses.billing_label') : __('commerce-users::account.addresses.shipping_label')) }}</span>
                                @if($cuAddress->is_default)
                                    <span class="cu-badge"><i class="fa-solid fa-check"></i> {{ __('commerce-users::account.addresses.default_badge') }}</span>
                                @endif
                            </div>
                            <div class="cu-address-body">
                                <strong>{{ $cuAddress->name }}</strong><br>
                                @if($cuAddress->company){{ $cuAddress->company }}<br>@endif
                                {{ $cuAddress->zip }} {{ $cuAddress->city }}<br>
                                {{ $cuAddress->address }}
                                @if($cuAddress->tax_number)<br><span class="text-muted">{{ __('commerce-users::account.addresses.tax_number_line', ['number' => $cuAddress->tax_number]) }}</span>@endif
                                @if($cuAddress->phone)<br><span class="text-muted">{{ __('commerce-users::account.addresses.phone_line', ['phone' => $cuAddress->phone]) }}</span>@endif
                            </div>
                            <div class="cu-address-actions">
                                <a href="{{ route('commerce-users.addresses.edit', $cuAddress->id) }}" class="btn btn-sm btn-outline-primary">{{ __('commerce-users::account.addresses.edit') }}</a>
                                @unless($cuAddress->is_default)
                                    <form method="POST" action="{{ route('commerce-users.addresses.default', $cuAddress->id) }}">
                                        @csrf
                                        <button type="submit" class="btn btn-sm btn-outline-secondary">{{ __('commerce-users::account.addresses.make_default') }}</button>
                                    </form>
                                @endunless
                                <form method="POST" action="{{ route('commerce-users.addresses.destroy', $cuAddress->id) }}"
                                      onsubmit="return confirm(@js(__('commerce-users::account.addresses.delete_confirm')));">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-outline-danger">{{ __('commerce-users::account.addresses.delete') }}</button>
                                </form>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>
    @endforeach
@endsection
