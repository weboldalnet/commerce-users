{{-- Fiókoldal: balra a fiókmenü (a befogadó által hozzáadott pontokkal, pl. Rendeléseim),
     jobbra a tartalom. A gyerek-nézetek szekciói: title, cu_title, cu_content. --}}
@extends('site.layouts.layout')

@push('styles')
    <meta name="robots" content="noindex, nofollow">
    <link rel="stylesheet" href="/packages/commerce-users/site/css/commerce-users.css">
@endpush

@section('content')
    @php
        $cuMenu = \Weboldalnet\CommerceUsers\CommerceUsers::accountMenu();
        $cuRoute = optional(request()->route())->getName();
    @endphp
    <div class="cu-page cu-account-page">
        <div class="container">
            <div class="cu-account-head">
                <span class="cu-kicker">{{ __('commerce-users::common.my_account') }}</span>
                <h1>@yield('cu_title')</h1>
            </div>

            <div class="row">
                <aside class="col-lg-3 mb-4">
                    <nav class="cu-nav" aria-label="{{ __('commerce-users::account.menu.label') }}">
                        @if($commerceUser)
                            <div class="cu-nav-hello">
                                {{ __('commerce-users::account.menu.signed_in_as') }}
                                <strong>{{ $commerceUser->full_name }}</strong>
                            </div>
                        @endif
                        @foreach($cuMenu as $cuItem)
                            @php($cuActive = $cuRoute && \Illuminate\Support\Str::is($cuItem['activeRoutes'] ?: [$cuItem['route']], $cuRoute))
                            <a href="{{ $cuItem['url'] }}" class="@if($cuActive) is-active @endif" @if($cuActive) aria-current="page" @endif>
                                <i class="fa-solid {{ $cuItem['icon'] }}"></i> {{ $cuItem['label'] }}
                            </a>
                        @endforeach
                        <form method="POST" action="{{ \Weboldalnet\CommerceUsers\CommerceUsers::route('commerce-users.logout') }}">
                            @csrf
                            <button type="submit"><i class="fa-solid fa-right-from-bracket"></i> {{ __('commerce-users::common.logout') }}</button>
                        </form>
                    </nav>
                </aside>

                <div class="col-lg-9">
                    @include('commerce-users::site.partials.flash')
                    @yield('cu_content')
                </div>
            </div>
        </div>
    </div>

    @include('commerce-users::site.partials.password-script')
@endsection
