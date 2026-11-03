{{-- Fiók-gomb a fejlécbe (a befogadó húzza be, pl. a webshop gombsora).
     Vendégnek (kulcs ikon) a belépő oldalra visz, bejelentkezve (felhasználó ikon) lenyíló a fiókmenüvel.
     Paraméter: $btnClass (a befogadó gombjainak osztálya, hogy illeszkedjen). --}}
@php
    $cuUser = \Weboldalnet\CommerceUsers\CommerceUsers::user();
    $cuBtnClass = $btnClass ?? 'btn btn-outline-light';
@endphp
@if(\Weboldalnet\CommerceUsers\CommerceUsers::enabled())
    @if($cuUser)
        <div class="dropdown cu-account-btn">
            <button type="button" class="{{ $cuBtnClass }}" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"
                    title="Fiókom" aria-label="Fiókom">
                <i class="fa-solid fa-user fa-fw"></i>
            </button>
            {{-- Segédosztályokkal, mert a modul stíluslapja csak a fiókoldalakon töltődik be:
                 a Kijelentkezés a doboz aljáig ér, fölötte-alatta nincs üres sáv; oldalt 16 px (a Bootstrap 24 px-e helyett) --}}
            <div class="dropdown-menu dropdown-menu-right cu-account-menu pt-1 pb-0 overflow-hidden">
                <h6 class="dropdown-header px-3">Szia, {{ $cuUser->first_name }}!</h6>
                @foreach(\Weboldalnet\CommerceUsers\CommerceUsers::accountMenu() as $cuItem)
                    <a class="dropdown-item px-3" href="{{ route($cuItem['route']) }}"><i class="fa-solid {{ $cuItem['icon'] }} fa-fw mr-1"></i> {{ $cuItem['label'] }}</a>
                @endforeach
                <div class="dropdown-divider mt-1 mb-0"></div>
                {{-- Link, nem <button>: a befogadó fejlécének gomb-szabályai (pl. a core „.desktop-menu
                     .dropdown.show button”) a nyitott lenyíló minden gombját kiszínezik --}}
                <form method="POST" action="{{ route('commerce-users.logout') }}" class="m-0">
                    @csrf
                    <a href="{{ route('commerce-users.logout') }}" class="dropdown-item px-3" role="button"
                       onclick="event.preventDefault(); this.closest('form').submit();"><i class="fa-solid fa-right-from-bracket fa-fw mr-1"></i> Kijelentkezés</a>
                </form>
            </div>
        </div>
    @else
        <a href="{{ route('commerce-users.login') }}" class="{{ $cuBtnClass }} cu-account-btn" title="Bejelentkezés" aria-label="Bejelentkezés">
            <i class="fa-solid fa-key fa-fw"></i>
        </a>
    @endif
@endif
