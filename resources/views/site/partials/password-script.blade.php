{{-- A jelszómezők megjelenítő gombja (password-field). Keretrendszer nélkül, minden oldalon egyszer. --}}
<script>
    document.addEventListener('click', function (e) {
        var btn = e.target.closest('.js-cu-password-toggle');
        if (!btn) {
            return;
        }
        var input = document.getElementById(btn.getAttribute('aria-controls'));
        if (!input) {
            return;
        }
        var show = input.type === 'password';
        input.type = show ? 'text' : 'password';
        btn.setAttribute('aria-label', show ? @json(__('commerce-users::common.password.hide')) : @json(__('commerce-users::common.password.show')));
        btn.querySelector('i').className = show ? 'fa-regular fa-eye-slash' : 'fa-regular fa-eye';
    });
</script>
