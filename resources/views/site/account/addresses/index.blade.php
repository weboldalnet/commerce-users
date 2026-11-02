@extends('commerce-users::site.layouts.account')
@section('title', 'Címeim')
@section('cu_title', 'Címeim')

@section('cu_content')
    @foreach([
        'shipping' => ['title' => 'Szállítási címek', 'addresses' => $shippingAddresses, 'new' => 'Új szállítási cím',
            'empty' => 'Még nincs mentett szállítási címed. A pénztárban az elsődleges cím töltődik ki magától.'],
        'billing' => ['title' => 'Számlázási címek', 'addresses' => $billingAddresses, 'new' => 'Új számlázási cím',
            'empty' => 'Még nincs mentett számlázási címed.'],
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
                                <span class="cu-address-label">{{ $cuAddress->label ?: ($cuType === 'billing' ? 'Számlázási cím' : 'Szállítási cím') }}</span>
                                @if($cuAddress->is_default)
                                    <span class="cu-badge"><i class="fa-solid fa-check"></i> Elsődleges</span>
                                @endif
                            </div>
                            <div class="cu-address-body">
                                <strong>{{ $cuAddress->name }}</strong><br>
                                @if($cuAddress->company){{ $cuAddress->company }}<br>@endif
                                {{ $cuAddress->zip }} {{ $cuAddress->city }}<br>
                                {{ $cuAddress->address }}
                                @if($cuAddress->tax_number)<br><span class="text-muted">Adószám: {{ $cuAddress->tax_number }}</span>@endif
                                @if($cuAddress->phone)<br><span class="text-muted">Tel.: {{ $cuAddress->phone }}</span>@endif
                            </div>
                            <div class="cu-address-actions">
                                <a href="{{ route('commerce-users.addresses.edit', $cuAddress->id) }}" class="btn btn-sm btn-outline-primary">Szerkesztés</a>
                                @unless($cuAddress->is_default)
                                    <form method="POST" action="{{ route('commerce-users.addresses.default', $cuAddress->id) }}">
                                        @csrf
                                        <button type="submit" class="btn btn-sm btn-outline-secondary">Legyen elsődleges</button>
                                    </form>
                                @endunless
                                <form method="POST" action="{{ route('commerce-users.addresses.destroy', $cuAddress->id) }}"
                                      onsubmit="return confirm('Biztosan törlöd ezt a címet?');">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-outline-danger">Törlés</button>
                                </form>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>
    @endforeach
@endsection
