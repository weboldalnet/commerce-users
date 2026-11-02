{{-- Webshop felhasználó modul admin menüpontja — webshop NÉLKÜLI projektbe (`commerce-users:extend --view=sidebar`).
     Webshop projektben NE tedd be: ott a webshop menüjében már ott a „Vásárlók” pont. --}}
@if(\Weboldalnet\CommerceUsers\CommerceUsers::enabled())
    <div class="mb-1">
        <a class="menu-point collapsed" data-toggle="collapse" href="#commerceUsersCollapse" role="button">
            <span><i class="fas fa-users mr-1"></i>Vásárlók</span>
            <i class="fa-solid fa-chevron-down"></i>
        </a>
        <div class="collapse collapse-box" id="commerceUsersCollapse">
            <div class="collapse-menu-points">
                <a href="{{ route('admin.webshop.users.index') }}" class="fw-800">
                    Vásárlói fiókok <i class="fa-solid fa-chevron-right"></i>
                </a>
            </div>
        </div>
    </div>
@endif
