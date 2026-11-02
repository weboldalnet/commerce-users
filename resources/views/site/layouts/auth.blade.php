{{-- Belépés, regisztráció, elfelejtett jelszó: középre zárt kártya a projekt site-layoutjában.
     A gyerek-nézetek szekciói: title, cu_title, cu_lead (opcionális), cu_content, cu_after (kártya alatt). --}}
@extends('site.layouts.layout')

@push('styles')
    <meta name="robots" content="noindex, nofollow">
    <link rel="stylesheet" href="/packages/commerce-users/site/css/commerce-users.css">
@endpush

@section('content')
    <div class="cu-page cu-auth-page">
        <div class="container">
            <div class="cu-auth-card @yield('cu_card_class')">
                @hasSection('cu_icon')
                    <div class="cu-auth-icon"><i class="fa-solid @yield('cu_icon')"></i></div>
                @endif
                <h1 class="@hasSection('cu_icon') text-center @endif">@yield('cu_title')</h1>
                @hasSection('cu_lead')
                    <p class="cu-lead @hasSection('cu_icon') text-center @endif">@yield('cu_lead')</p>
                @endif

                @include('commerce-users::site.partials.flash')

                @yield('cu_content')
            </div>

            @yield('cu_after')
        </div>
    </div>

    @include('commerce-users::site.partials.password-script')
@endsection
