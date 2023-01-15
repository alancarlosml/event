@component('mail::layout')
    {{-- Header --}}
    @slot('header')
        @component('mail::header', ['url' => config('app.url')])
            {{ config('app.name') }}
        @endcomponent
        {{-- <table role="presentation" border="0" cellpadding="0" cellspacing="0" width="100%">
            <tr>
                <td class="logo" style="text-align: left;">
                    <h1>
                        <a href="http://www.lojadeeventos.com.br" target="_blank">
                        <img src="E:\Programas\xampp\htdocs\event\public\assets\img\logo_principal.png" alt="Loja de Eventos" style="width: 200px">
                        </a>
                    </h1>
                </td>
            </tr>
        </table> --}}
    @endslot

    {{-- <td valign="top" class="bg_white" style="padding: 1em 2.5em 0 2.5em;">
        <table role="presentation" border="0" cellpadding="0" cellspacing="0" width="100%">
            <tr>
                <td class="logo" style="text-align: left;">
                    <h1>
                        <a href="http://www.lojadeeventos.com.br" target="_blank">
                        <img src="E:\Programas\xampp\htdocs\event\public\assets\img\logo_principal.png" alt="Loja de Eventos" style="width: 200px">
                        </a>
                    </h1>
                </td>
            </tr>
        </table>
    </td> --}}

    {{-- Body --}}
    {{ $slot }}

    {{-- Subcopy --}}
    @isset($subcopy)
        @slot('subcopy')
            @component('mail::subcopy')
                {{ $subcopy }}
            @endcomponent
        @endslot
    @endisset

    {{-- Footer --}}
    @slot('footer')
        @component('mail::footer')
            © {{ date('Y') }} {{ config('app.name') }}. @lang('Todos os direitos reservador.')
        @endcomponent
    @endslot
@endcomponent
