{{-- Fiók-gomb a fejlécbe (a befogadó húzza be, pl. a webshop gombsora).
     Vendégnek a belépő oldalra visz, bejelentkezve lenyíló a fiókmenüvel.
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
                <i class="fa-solid fa-user-check"></i>
            </button>
            <div class="dropdown-menu dropdown-menu-right cu-account-menu">
                <h6 class="dropdown-header">Szia, {{ $cuUser->first_name }}!</h6>
                @foreach(\Weboldalnet\CommerceUsers\CommerceUsers::accountMenu() as $cuItem)
                    <a class="dropdown-item" href="{{ route($cuItem['route']) }}"><i class="fa-solid {{ $cuItem['icon'] }} fa-fw mr-1"></i> {{ $cuItem['label'] }}</a>
                @endforeach
                <div class="dropdown-divider"></div>
                <form method="POST" action="{{ route('commerce-users.logout') }}" class="m-0">
                    @csrf
                    <button type="submit" class="dropdown-item"><i class="fa-solid fa-right-from-bracket fa-fw mr-1"></i> Kijelentkezés</button>
                </form>
            </div>
        </div>
    @else
        <a href="{{ route('commerce-users.login') }}" class="{{ $cuBtnClass }} cu-account-btn" title="Bejelentkezés" aria-label="Bejelentkezés">
            <i class="fa-solid fa-user"></i>
        </a>
    @endif
@endif
