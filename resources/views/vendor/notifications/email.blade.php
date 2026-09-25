@component('mail::message')
{{-- Greeting --}}
@if (! empty($greeting))
# {{ $greeting }}
@else
@if ($level == 'error')
# @lang('mail.whoops')
@else
# @lang('mail.hello')
@endif
@endif

{{-- Intro Lines --}}
@foreach ($introLines as $line)
{!! $line !!}

@endforeach

{{-- Action Button --}}
@isset($actionText)
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
@component('mail::button', ['url' => $actionUrl, 'color' => $color])
{{ $actionText }}
@endcomponent
@endisset

{{-- Outro Lines --}}
@foreach ($outroLines as $line)
{!! $line !!}

@endforeach

{{-- Salutation --}}
@if (! empty($salutation))
{{ $salutation }}
@else
@lang('mail.regards')<br>
{{ config('app.name') }}
@endif

{{-- Subcopy (plain-text copy of the link; notifications can opt out with viewData hide_action_url) --}}
@if (isset($actionText) && empty($hide_action_url))
@component('mail::subcopy')
@lang('mail.trouble_clicking', ['actionText' => $actionText]) [{{ $actionUrl }}]({{ $actionUrl }})
@endcomponent
@endif
@endcomponent
