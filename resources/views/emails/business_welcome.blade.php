@component('mail::message')
# @lang('mail.yaigo_welcome_greeting', ['name' => $owner_name])

@lang('mail.yaigo_welcome_success', ['business' => $business_name])

@if ($has_attachment)
@lang('mail.yaigo_welcome_attachment')

@if (! empty($send_to))
@lang('mail.yaigo_welcome_send_back', ['email' => $send_to])
@endif
@endif

@lang('mail.yaigo_welcome_username', ['username' => $username])

@component('mail::button', ['url' => $login_url])
@lang('mail.yaigo_welcome_login')
@endcomponent

@lang('mail.yaigo_welcome_questions')

@lang('mail.regards')<br>
@lang('mail.yaigo_welcome_team')
@endcomponent
