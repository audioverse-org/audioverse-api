@component('emails.html.message')
{{-- Greeting --}}
@if (! empty($greeting))
    <h1>{{ $greeting }}</h1>
@else
    @if ($level == 'error')
        # Whoops!
    @else
        # Hello!
    @endif
@endif

{{-- Intro Lines --}}
@foreach ($introLines as $line)
    <p>{{ $line }}</p>
@endforeach

@if (isset($details))
    @if (isset($details['admin']))
        @component('emails.html.donation_admin', ['details' => $details])
        @endcomponent
    @else
        @component('emails.html.donation', ['details' => $details])
        @endcomponent
    @endif
@endif
{{-- Action Button --}}
@if (isset($actionText))
    <?php
    switch ($level) {
        case 'success':
            $color = 'green';
            break;
        case 'error':
            $color = 'red';
            break;
        default:
            $color = 'blue';
    }
    ?>
    @component('emails.html.button', ['url' => $actionUrl, 'color' => $color])
    {{ $actionText }}
    @endcomponent
@endif

{{-- Outro Lines --}}
@foreach ($outroLines as $line)
    <p>{{ $line }}</p>
@endforeach

<!-- Signature -->
@if (!empty($signature))
    <p>{{ $signature['signoff'] }},<br><img src="{{ $signature['image'] }}" style="width:130px;"><br>{{ $signature['name'] }}</p>
@else
    <p>Regards,<br>{{ config('app.name') }}</p>
@endif
<!-- Subcopy -->
@if (isset($actionText))
    @component('emails.html.subcopy')
    If you’re having trouble clicking the "{{ $actionText }}" button, copy and paste the URL below
    into your web browser: [{{ $actionUrl }}]({{ $actionUrl }})
    @endcomponent
@endif
@endcomponent