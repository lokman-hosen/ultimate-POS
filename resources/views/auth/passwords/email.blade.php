@extends('layouts.auth2')

@section('title', __('lang_v1.reset_password'))

@section('content')
    <div class="pos-center-screen">
    <div class="pos-login-wrap">
        <div class="pos-card">
            <div style="margin-bottom:1.5rem;">
                <h1 class="pos-h1">@lang('lang_v1.forgot_your_password')</h1>
                <p class="pos-p">@lang('lang_v1.send_password_reset_link_help')</p>
            </div>

            @if (session('status') && is_string(session('status')))
                <div class="pos-alert" role="alert">{{ session('status') }}</div>
            @endif

            <form method="POST" action="{{ route('password.email') }}">
                {{ csrf_field() }}
                <div class="form-group has-feedback pos-form-group {{ $errors->has('email') ? ' has-error' : '' }}">
                    <label class="pos-label" for="email">@lang('lang_v1.email_address')</label>
                    <div class="pos-input-wrap">
                        <input id="email" type="email" class="pos-input" name="email" value="{{ old('email') }}"
                               required autofocus placeholder="@lang('lang_v1.email_address')">
                    </div>
                    @if ($errors->has('email'))
                        <span class="pos-error">{{ $errors->first('email') }}</span>
                    @endif
                </div>

                <button type="submit" class="pos-submit">@lang('lang_v1.send_password_reset_link')</button>
            </form>

            <p class="pos-back-link"><a href="{{ route('login') }}">@lang('lang_v1.back_to_login')</a></p>
        </div>
    </div>
    </div>
@endsection
