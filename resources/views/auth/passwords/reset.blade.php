@extends('layouts.auth2')

@section('title', __('lang_v1.reset_password'))

@section('content')
    <div class="pos-login-wrap">
        <div class="pos-card">
            <div style="margin-bottom:1.5rem;">
                <h1 class="pos-h1">@lang('lang_v1.reset_password')</h1>
            </div>

            <form method="POST" action="{{ route('password.request') }}">
                {{ csrf_field() }}

                <input type="hidden" name="token" value="{{ $token }}">

                <div class="form-group has-feedback pos-form-group {{ $errors->has('email') ? ' has-error' : '' }}">
                    <label class="pos-label">@lang('Email')</label>
                    <div class="pos-input-wrap">
                        <input id="email" type="email" class="pos-input" name="email"
                               value="{{ $email ?? old('email') }}" required autofocus
                               placeholder="@lang('lang_v1.email_address')">
                    </div>
                    @if ($errors->has('email'))
                        <span class="pos-error">{{ $errors->first('email') }}</span>
                    @endif
                </div>

                <div class="form-group has-feedback pos-form-group {{ $errors->has('password') ? ' has-error' : '' }}">
                    <label class="pos-label">@lang('lang_v1.password')</label>
                    <div class="pos-input-wrap">
                        <input id="password" type="password" class="pos-input" name="password" required
                               placeholder="@lang('lang_v1.password')">
                    </div>
                    @if ($errors->has('password'))
                        <span class="pos-error">{{ $errors->first('password') }}</span>
                    @endif
                </div>

                <div class="form-group has-feedback pos-form-group {{ $errors->has('password_confirmation') ? ' has-error' : '' }}">
                    <label class="pos-label">@lang('business.confirm_password')</label>
                    <div class="pos-input-wrap">
                        <input id="password" type="password" class="pos-input" name="password_confirmation"
                               required placeholder="@lang('business.confirm_password')">
                    </div>
                    @if ($errors->has('password_confirmation'))
                        <span class="pos-error">{{ $errors->first('password_confirmation') }}</span>
                    @endif
                </div>

                <button type="submit" class="pos-submit">@lang('lang_v1.reset_password')</button>
            </form>
        </div>
    </div>
@endsection