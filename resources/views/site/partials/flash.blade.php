{{-- Visszajelzések (success / info / warning / error) a munkamenetből. --}}
@foreach(['success' => 'alert-success', 'info' => 'alert-info', 'warning' => 'alert-warning', 'error' => 'alert-danger'] as $cuFlashKey => $cuFlashClass)
    @if(session($cuFlashKey))
        <div class="alert {{ $cuFlashClass }} fs-16" role="{{ $cuFlashKey === 'error' ? 'alert' : 'status' }}">
            {{ session($cuFlashKey) }}
        </div>
    @endif
@endforeach
