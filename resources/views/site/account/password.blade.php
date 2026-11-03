@extends('commerce-users::site.layouts.account')
@section('title', __('commerce-users::account.password.title'))
@section('cu_title', __('commerce-users::account.password.title'))

@section('cu_content')
    <div class="cu-card" style="max-width: 560px">
        <form method="POST" action="{{ route('commerce-users.password.change') }}" novalidate>
            @csrf @method('PUT')
            {{-- a böngésző jelszókezelője tudja, melyik fiókhoz menti az új jelszót --}}
            <input type="email" name="username" value="{{ $user->email }}" autocomplete="username" class="d-none" tabindex="-1" aria-hidden="true">

            @include('commerce-users::site.partials.password-field', [
                'name' => 'current_password', 'id' => 'cu_current_password', 'label' => __('commerce-users::account.password.current'),
                'autocomplete' => 'current-password',
            ])
            @include('commerce-users::site.partials.password-field', [
                'name' => 'password', 'id' => 'cu_password', 'label' => __('commerce-users::common.fields.new_password'), 'autocomplete' => 'new-password',
                'hint' => __('commerce-users::common.password.min_hint', ['min' => $minPassword]),
            ])
            @include('commerce-users::site.partials.password-field', [
                'name' => 'password_confirmation', 'id' => 'cu_password_confirmation', 'label' => __('commerce-users::common.fields.new_password_confirmation'),
                'autocomplete' => 'new-password',
            ])

            <button type="submit" class="btn btn-primary">{{ __('commerce-users::common.password.save') }}</button>
        </form>
    </div>
@endsection
