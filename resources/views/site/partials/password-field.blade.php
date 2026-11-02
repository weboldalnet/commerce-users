{{-- Jelszómező megjelenítő gombbal. Paraméterek: $name, $id, $label, $autocomplete, $hint (opcionális), $required --}}
<div class="form-group">
    <label for="{{ $id }}">{{ $label }} @if($required ?? true)<span class="cu-req">*</span>@endif</label>
    <div class="cu-password">
        <input type="password" name="{{ $name }}" id="{{ $id }}"
               class="form-control @error($name) is-invalid @enderror"
               autocomplete="{{ $autocomplete ?? 'current-password' }}" @if($required ?? true) required @endif>
        <button type="button" class="cu-password-toggle js-cu-password-toggle" aria-label="Jelszó megjelenítése" aria-controls="{{ $id }}">
            <i class="fa-regular fa-eye"></i>
        </button>
    </div>
    @error($name) <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
    @if(!empty($hint))
        <span class="cu-hint">{{ $hint }}</span>
    @endif
</div>
