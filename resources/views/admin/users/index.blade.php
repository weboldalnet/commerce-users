@extends('admin.layouts.layout')
@section('title', 'Vásárlók')

@section('content')
    @php
        $cuStatusBadges = ['active' => 'badge-success', 'unverified' => 'badge-warning', 'disabled' => 'badge-secondary'];
    @endphp
    <div class="container-fluid mt-3 mb-150" style="max-width: 1200px">
        @include('commerce-users::admin.users.partials.alerts')

        <div class="d-flex flex-wrap align-items-end justify-content-between mb-3">
            <div>
                <h2 class="fw-700 mb-1">Vásárlók</h2>
                <span class="text-muted fs-14">{{ $total }} regisztrált fiók. A vásárlók a webshopban maguk regisztrálnak.</span>
            </div>
        </div>

        <div class="content-box bordered mb-3">
            <form method="GET" action="{{ route('admin.webshop.users.index') }}" class="form-row align-items-end">
                <div class="form-group col-md-6 mb-md-0">
                    <label for="cu_search">Keresés</label>
                    <input type="search" name="search" id="cu_search" value="{{ request('search') }}" class="form-control"
                           placeholder="Név, e-mail-cím vagy telefonszám">
                </div>
                <div class="form-group col-md-3 mb-md-0">
                    <label for="cu_status">Állapot</label>
                    <select name="status" id="cu_status" class="form-control">
                        <option value="">Mind</option>
                        @foreach($statuses as $cuKey => $cuLabel)
                            <option value="{{ $cuKey }}" @selected(request('status') === $cuKey)>{{ $cuLabel }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group col-md-3 mb-0">
                    <button type="submit" class="btn btn-primary"><i class="fa fa-search"></i> Szűrés</button>
                    @if(request('search') || request('status'))
                        <a href="{{ route('admin.webshop.users.index') }}" class="btn btn-link">Törlés</a>
                    @endif
                </div>
            </form>
        </div>

        <div class="content-box table-responsive mb-3">
            <table class="table table-hover mb-0 cu-admin-table">
                <thead>
                <tr>
                    <th class="text-left">Név</th>
                    <th class="text-left">E-mail-cím</th>
                    <th>Regisztráció</th>
                    <th>Utolsó belépés</th>
                    <th>Állapot</th>
                    <th></th>
                </tr>
                </thead>
                <tbody>
                @forelse($users as $cuUser)
                    <tr>
                        <td class="text-left fw-600">
                            <a href="{{ route('admin.webshop.users.show', $cuUser) }}">{{ $cuUser->full_name }}</a>
                        </td>
                        <td class="text-left text-break">{{ $cuUser->email }}</td>
                        <td>{{ $cuUser->created_at?->format('Y. m. d.') }}</td>
                        <td>{{ $cuUser->last_login_at ? $cuUser->last_login_at->format('Y. m. d. H:i') : '–' }}</td>
                        <td><span class="badge {{ $cuStatusBadges[$cuUser->status()] }} fs-13">{{ $statuses[$cuUser->status()] }}</span></td>
                        <td class="text-right">
                            <a href="{{ route('admin.webshop.users.show', $cuUser) }}" class="btn btn-sm btn-outline-primary" title="Adatlap">
                                <i class="fa fa-eye"></i>
                            </a>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="6" class="text-center text-muted py-4">
                        {{ request('search') || request('status') ? 'Nincs a szűrésnek megfelelő vásárló.' : 'Még nincs regisztrált vásárló.' }}
                    </td></tr>
                @endforelse
                </tbody>
            </table>
        </div>

        {{ $users->links('pagination::bootstrap-4') }}
    </div>
@endsection
