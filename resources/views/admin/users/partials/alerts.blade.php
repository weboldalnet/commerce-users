@foreach(['success' => 'alert-success', 'error' => 'alert-danger', 'info' => 'alert-info'] as $cuFlashKey => $cuFlashClass)
    @if(session($cuFlashKey))
        <div class="alert {{ $cuFlashClass }} alert-dismissible fade show" role="alert">
            {{ session($cuFlashKey) }}
            <button type="button" class="close" data-dismiss="alert" aria-label="Bezárás"><span aria-hidden="true">&times;</span></button>
        </div>
    @endif
@endforeach
