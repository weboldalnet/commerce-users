@extends('admin.layouts.layout')
@section('title', 'Vásárló adatlapja')

@section('content')
    @php
        $cuStatus = $customer->status();
        $cuStatusBadges = ['active' => 'badge-success', 'unverified' => 'badge-warning', 'disabled' => 'badge-secondary'];
    @endphp
    <div class="container-fluid mt-3 mb-150" style="max-width: 1200px">
        @include('commerce-users::admin.users.partials.alerts')

        <div class="d-flex flex-wrap align-items-end justify-content-between mb-3">
            <div>
                <span class="text-muted fs-14 text-uppercase fw-600">Vásárló</span>
                <h2 class="fw-700 mb-1">{{ $customer->full_name }}</h2>
                <span class="badge {{ $cuStatusBadges[$cuStatus] }} fs-13">{{ \Weboldalnet\CommerceUsers\Models\CommerceUser::STATUSES[$cuStatus] }}</span>
            </div>
            <a href="{{ route('admin.webshop.users.index') }}" class="btn btn-outline-secondary"><i class="fa fa-arrow-left"></i> Vásárlók</a>
        </div>

        <div class="row">
            <div class="col-lg-7">
                <div class="content-box bordered mb-3">
                    <h4 class="fw-700 mb-3">Adatok</h4>
                    <table class="table table-sm mb-0">
                        <tr><th class="text-left w-25">Név</th><td class="text-left">{{ $customer->full_name }}</td></tr>
                        <tr><th class="text-left">E-mail-cím</th><td class="text-left text-break"><a href="mailto:{{ $customer->email }}">{{ $customer->email }}</a></td></tr>
                        <tr><th class="text-left">Telefonszám</th><td class="text-left">{{ $customer->phone ?: '–' }}</td></tr>
                        <tr><th class="text-left">Regisztráció</th><td class="text-left">{{ $customer->created_at?->format('Y. m. d. H:i') }}</td></tr>
                        <tr><th class="text-left">Aktiválva</th><td class="text-left">{{ $customer->email_verified_at ? $customer->email_verified_at->format('Y. m. d. H:i') : 'még nem' }}</td></tr>
                        <tr><th class="text-left">Utolsó belépés</th><td class="text-left">{{ $customer->last_login_at ? $customer->last_login_at->format('Y. m. d. H:i') : '–' }}</td></tr>
                    </table>
                </div>

                <div class="content-box bordered mb-3">
                    <h4 class="fw-700 mb-3">Címek</h4>
                    @if($shippingAddresses->isEmpty() && $billingAddresses->isEmpty())
                        <p class="text-muted mb-0">Nincs mentett címe.</p>
                    @else
                        <div class="row">
                            @foreach(['Szállítási címek' => $shippingAddresses, 'Számlázási címek' => $billingAddresses] as $cuTitle => $cuList)
                                <div class="col-md-6">
                                    <h5 class="fs-16 fw-600">{{ $cuTitle }}</h5>
                                    @forelse($cuList as $cuAddress)
                                        <div class="border rounded p-2 mb-2 fs-14 text-left">
                                            @if($cuAddress->is_default)<span class="badge badge-primary float-right">Elsődleges</span>@endif
                                            <strong>{{ $cuAddress->name }}</strong>
                                            @if($cuAddress->label)<span class="text-muted">· {{ $cuAddress->label }}</span>@endif<br>
                                            @if($cuAddress->company){{ $cuAddress->company }}<br>@endif
                                            {{ $cuAddress->one_line }}
                                            @if($cuAddress->tax_number)<br>Adószám: {{ $cuAddress->tax_number }}@endif
                                            @if($cuAddress->phone)<br>Tel.: {{ $cuAddress->phone }}@endif
                                        </div>
                                    @empty
                                        <p class="text-muted fs-14">Nincs.</p>
                                    @endforelse
                                </div>
                            @endforeach
                        </div>
                    @endif
                </div>

                {{-- A befogadó (webshop) a vásárló rendeléseit teheti ide --}}
                @includeIf('admin.webshop.users.partials.customer-orders', ['commerceUser' => $customer])
            </div>

            <div class="col-lg-5">
                <div class="content-box bordered mb-3">
                    <h4 class="fw-700 mb-3">Műveletek</h4>

                    @unless($customer->isVerified())
                        <form method="POST" action="{{ route('admin.webshop.users.verify', $customer) }}" class="mb-2">
                            @csrf
                            <button type="submit" class="btn btn-success btn-block"><i class="fa fa-check"></i> Aktiválás kézzel</button>
                        </form>
                        <span class="d-block text-muted fs-14 mb-3">Ha a vásárló nem kapta meg a levelet, és máshogy igazolta magát.</span>

                        <form method="POST" action="{{ route('admin.webshop.users.resend-activation', $customer) }}" class="mb-3">
                            @csrf
                            <button type="submit" class="btn btn-outline-primary btn-block"><i class="fa fa-envelope"></i> Aktiváló levél újraküldése</button>
                        </form>
                    @endunless

                    <form method="POST" action="{{ route('admin.webshop.users.toggle-active', $customer) }}" class="mb-2">
                        @csrf
                        @if($customer->is_active)
                            <button type="submit" class="btn btn-outline-warning btn-block"><i class="fa fa-ban"></i> Fiók letiltása</button>
                        @else
                            <button type="submit" class="btn btn-outline-success btn-block"><i class="fa fa-unlock"></i> Fiók engedélyezése</button>
                        @endif
                    </form>
                    <span class="d-block text-muted fs-14 mb-3">Letiltott fiókkal nem lehet belépni; a fiók és a rendelések megmaradnak.</span>

                    <form method="POST" action="{{ route('admin.webshop.users.destroy', $customer) }}"
                          onsubmit="return confirm('Biztosan törlöd ezt a fiókot és a mentett címeit? A leadott rendelések megmaradnak.');">
                        @csrf @method('DELETE')
                        <button type="submit" class="btn btn-outline-danger btn-block"><i class="fa fa-trash-alt"></i> Fiók törlése</button>
                    </form>
                    <span class="d-block text-muted fs-14">Pl. adattörlési kérésre. A rendelések a megrendelő adataival megmaradnak.</span>
                </div>
            </div>
        </div>
    </div>
@endsection
