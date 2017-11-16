@component('emails.html.layout')
    {{-- Header --}}
    @slot('header')
        @component('emails.html.header', ['url' => config('avorg.website_url')])
            {{ config('app.name') }}
        @endcomponent
    @endslot

    {{-- Body --}}
    {{ $slot }}

    {{-- Subcopy --}}
    @if (isset($subcopy))
        @slot('subcopy')
            @component('emails.html.subcopy')
                {{ $subcopy }}
            @endcomponent
        @endslot
    @endif

    {{-- Footer --}}
    @slot('footer')
        @component('emails.html.footer')
            &copy; {{ date('Y') }} {{ config('app.name') }}. All rights reserved.
        @endcomponent
    @endslot
@endcomponent