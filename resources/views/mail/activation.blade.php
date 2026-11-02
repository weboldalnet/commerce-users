{{-- Aktiváló levél a regisztráció után — a core levélsablonjában (logó, márka-lábléc). --}}
@extends('mail.layouts.mail_layout')

@section('content')
    @php($cuColor = \Weboldalnet\CommerceUsers\Support\Theme::buttonColor())
    <tr>
        <td colspan="2" style="padding:20px 20px 30px 20px;">
            <div style="margin-bottom: 15px; color: #000; text-align: center;">
                <p style="font-size: 20px; margin-top: 5px; font-weight: bold">
                    Kedves {{ $user->first_name }}!
                </p>
                <p style="font-size: 16px; margin-bottom: 4px">
                    Köszönjük, hogy regisztráltál a(z) {{ config('app.shop_name') }} webáruházban.
                    A fiókod aktiválásához kattints az alábbi gombra.
                </p>

                <div style="text-align: center; margin: 20px 0;">
                    <a href="{{ $activationUrl }}"
                       style="border-radius: 6px; padding: 10px 22px; display: inline-block; text-decoration: none; color: #fff;
                              font-size: 16px; font-weight: bold; background-color: {{ $cuColor }}; border: none;">
                        Fiók aktiválása
                    </a>
                </div>

                <p style="font-size: 14px; color: #666;">
                    A link {{ $expireHours }} óráig érvényes. Ha nem te regisztráltál, nyugodtan hagyd figyelmen kívül ezt a levelet —
                    aktiválás nélkül a fiók nem használható.
                </p>
                <p style="font-size: 13px; color: #888; word-break: break-all;">
                    Ha a gomb nem működik, másold be ezt a címet a böngésződbe:<br>{{ $activationUrl }}
                </p>
            </div>
        </td>
    </tr>
@endsection
