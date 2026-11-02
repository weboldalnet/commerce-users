@extends('commerce-users::site.layouts.account')
@section('title', 'Jelszócsere')
@section('cu_title', 'Jelszócsere')

@section('cu_content')
    <div class="cu-card" style="max-width: 560px">
        <form method="POST" action="{{ route('commerce-users.password.change') }}" novalidate>
            @csrf @method('PUT')
            {{-- a böngésző jelszókezelője tudja, melyik fiókhoz menti az új jelszót --}}
            <input type="email" name="username" value="{{ $user->email }}" autocomplete="username" class="d-none" tabindex="-1" aria-hidden="true">

            @include('commerce-users::site.partials.password-field', [
                'name' => 'current_password', 'id' => 'cu_current_password', 'label' => 'Jelenlegi jelszó',
                'autocomplete' => 'current-password',
            ])
            @include('commerce-users::site.partials.password-field', [
                'name' => 'password', 'id' => 'cu_password', 'label' => 'Új jelszó', 'autocomplete' => 'new-password',
                'hint' => 'Legalább ' . $minPassword . ' karakter.',
            ])
            @include('commerce-users::site.partials.password-field', [
                'name' => 'password_confirmation', 'id' => 'cu_password_confirmation', 'label' => 'Új jelszó még egyszer',
                'autocomplete' => 'new-password',
            ])

            <button type="submit" class="btn btn-primary">Jelszó mentése</button>
        </form>
    </div>
@endsection
