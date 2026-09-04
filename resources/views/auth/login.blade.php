@extends('layouts.auth2')
@section('title', __('lang_v1.login'))
@inject('request', 'Illuminate\Http\Request')
@section('content')
    @php
        $username = old('username');
        $password = null;
        if (config('app.env') == 'demo') {
            $username = 'admin';
            $password = '123456';

            $demo_types = [
                'all_in_one' => 'admin',
                'super_market' => 'admin',
                'pharmacy' => 'admin-pharmacy',
                'electronics' => 'admin-electronics',
                'services' => 'admin-services',
                'restaurant' => 'admin-restaurant',
                'superadmin' => 'superadmin',
                'woocommerce' => 'woocommerce_user',
                'essentials' => 'admin-essentials',
                'manufacturing' => 'manufacturer-demo',
            ];

            if (!empty($_GET['demo_type']) && array_key_exists($_GET['demo_type'], $demo_types)) {
                $username = $demo_types[$_GET['demo_type']];
            }
        }
    @endphp

    <div class="pos-login-wrap">

{{--        @if (config('app.env') == 'demo')--}}
{{--            <div class="pos-card">--}}
{{--                <p class="pos-card__eyebrow">Demo shops</p>--}}
{{--                <h3 class="pos-card__title">Click a business to log in instantly</h3>--}}
{{--                <p class="pos-card__desc">--}}
{{--                    Demos are for example purposes only — this application can be used for many other similar--}}
{{--                    businesses.--}}
{{--                </p>--}}

{{--                <div class="pos-chip-row">--}}
{{--                    <a href="?demo_type=all_in_one" data-toggle="tooltip"--}}
{{--                       title="Showcases all feature available in the application." data-admin="{{ $demo_types['all_in_one'] }}"--}}
{{--                       class="demo-login pos-chip">--}}
{{--                        <i class="fas fa-star"></i> All In One--}}
{{--                    </a>--}}
{{--                    <a href="?demo_type=pharmacy" data-toggle="tooltip"--}}
{{--                       title="Shops with products having expiry dates." data-admin="{{ $demo_types['pharmacy'] }}"--}}
{{--                       class="demo-login pos-chip">--}}
{{--                        <i class="fas fa-medkit"></i> Pharmacy--}}
{{--                    </a>--}}
{{--                    <a href="?demo_type=services" data-toggle="tooltip"--}}
{{--                       title="For all service providers like Web Development, Restaurants, Repairing, Plumber, Salons, Beauty Parlors etc."--}}
{{--                       data-admin="{{ $demo_types['services'] }}"--}}
{{--                       class="demo-login pos-chip">--}}
{{--                        <i class="fas fa-wrench"></i> Multi-Service Center--}}
{{--                    </a>--}}
{{--                    <a href="?demo_type=electronics" data-toggle="tooltip"--}}
{{--                       title="Products having IMEI or Serial number code." data-admin="{{ $demo_types['electronics'] }}"--}}
{{--                       class="demo-login pos-chip">--}}
{{--                        <i class="fas fa-laptop"></i> Electronics & Mobile Shop--}}
{{--                    </a>--}}
{{--                    <a href="?demo_type=super_market" data-toggle="tooltip"--}}
{{--                       title="Super market & Similar kind of shops." data-admin="{{ $demo_types['super_market'] }}"--}}
{{--                       class="demo-login pos-chip">--}}
{{--                        <i class="fas fa-shopping-cart"></i> Super Market--}}
{{--                    </a>--}}
{{--                    <a href="?demo_type=restaurant" data-toggle="tooltip"--}}
{{--                       title="Restaurants, Salons and other similar kind of shops." data-admin="{{ $demo_types['restaurant'] }}"--}}
{{--                       class="demo-login pos-chip">--}}
{{--                        <i class="fas fa-utensils"></i> Restaurant--}}
{{--                    </a>--}}
{{--                </div>--}}

{{--                <p class="pos-card__eyebrow" style="color:var(--amber-400);margin-top:1.25rem;">Premium optional--}}
{{--                    modules</p>--}}

{{--                <div class="pos-chip-row">--}}
{{--                    <a href="?demo_type=superadmin" data-toggle="tooltip" title="SaaS & Superadmin extension Demo"--}}
{{--                       data-admin="{{ $demo_types['superadmin'] }}"--}}
{{--                       class="demo-login pos-chip pos-chip--premium">--}}
{{--                        <i class="fas fa-university"></i> SaaS / Superadmin--}}
{{--                    </a>--}}
{{--                    <a href="?demo_type=woocommerce" data-toggle="tooltip"--}}
{{--                       title="WooCommerce demo user - Open web shop in minutes!!" data-admin="{{ $demo_types['woocommerce'] }}"--}}
{{--                       class="demo-login pos-chip pos-chip--premium">--}}
{{--                        <i class="fab fa-wordpress"></i> WooCommerce--}}
{{--                    </a>--}}
{{--                    <a href="?demo_type=essentials" data-toggle="tooltip"--}}
{{--                       title="Essentials & HRM (human resource management) Module Demo"--}}
{{--                       data-admin="{{ $demo_types['essentials'] }}"--}}
{{--                       class="demo-login pos-chip pos-chip--premium">--}}
{{--                        <i class="fas fa-check-circle"></i> Essentials & HRM--}}
{{--                    </a>--}}
{{--                    <a href="?demo_type=manufacturing" data-toggle="tooltip" title="Manufacturing module demo"--}}
{{--                       data-admin="{{ $demo_types['manufacturing'] }}"--}}
{{--                       class="demo-login pos-chip pos-chip--premium">--}}
{{--                        <i class="fas fa-industry"></i> Manufacturing Module--}}
{{--                    </a>--}}
{{--                    <a href="?demo_type=superadmin" data-toggle="tooltip" title="Project module demo"--}}
{{--                       data-admin="{{ $demo_types['superadmin'] }}"--}}
{{--                       class="demo-login pos-chip pos-chip--premium">--}}
{{--                        <i class="fas fa-project-diagram"></i> Project Module--}}
{{--                    </a>--}}
{{--                    <a href="?demo_type=services" data-toggle="tooltip" title="Advance repair module demo"--}}
{{--                       data-admin="{{ $demo_types['services'] }}"--}}
{{--                       class="demo-login pos-chip pos-chip--premium">--}}
{{--                        <i class="fas fa-wrench"></i> Advance Repair Module--}}
{{--                    </a>--}}
{{--                    <a href="{{ url('docs') }}" target="_blank" data-toggle="tooltip"--}}
{{--                       title="Connector Module / API Documentation"--}}
{{--                       class="pos-chip pos-chip--docs">--}}
{{--                        <i class="fas fa-network-wired"></i> API Docs--}}
{{--                    </a>--}}
{{--                </div>--}}
{{--            </div>--}}
{{--        @endif--}}

        <div class="pos-card pos-card--login">
            <div class="pos-login-badge">
                <img src="{{ asset('img/logo-icon-arrow.png') }}" alt="{{ config('app.name', 'YaigoPos') }}">
{{--                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M9 6l6 6-6 6"/></svg>--}}
            </div>

            <div class="pos-login-head">
                <h1 class="pos-h1">@lang('lang_v1.welcome_back')</h1>
                <p class="pos-p">@lang('lang_v1.login_to_your') {{ config('app.name', 'YaigoPos') }}</p>
            </div>

            <form method="POST" action="{{ route('login') }}" id="login-form">
                {{ csrf_field() }}

                <div class="form-group has-feedback pos-form-group {{ $errors->has('username') ? ' has-error' : '' }}">
                    <label class="pos-label">@lang('lang_v1.username')</label>
                    <div class="pos-input-wrap">
                        <span class="pos-input-icon">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.75" stroke-linecap="round" stroke-linejoin="round">
                                <circle cx="12" cy="8" r="3.5"/>
                                <path d="M5 20c0-3.5 3-6 7-6s7 2.5 7 6"/>
                            </svg>
                        </span>
                        <input class="pos-input pos-input--icon" name="username" required autofocus
                               placeholder="@lang('lang_v1.username')" data-last-active-input="" id="username"
                               type="text" value="{{ $username }}" />
                    </div>
                    @if ($errors->has('username'))
                        <span class="pos-error">{{ $errors->first('username') }}</span>
                    @endif
                </div>

                <div class="form-group has-feedback pos-form-group {{ $errors->has('password') ? ' has-error' : '' }}">
                    <div class="pos-label-row">
                        <label class="pos-label">@lang('lang_v1.password')</label>
                        @if (config('app.env') != 'demo')
                            <a href="{{ route('password.request') }}" class="pos-forgot"
                               tabindex="-1">@lang('lang_v1.forgot_your_password')</a>
                        @endif
                    </div>
                    <div class="pos-input-wrap">
                        <span class="pos-input-icon">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.75" stroke-linecap="round" stroke-linejoin="round">
                                <rect x="5" y="10.5" width="14" height="9" rx="2"/>
                                <path d="M8 10.5V7.5a4 4 0 0 1 8 0v3"/>
                            </svg>
                        </span>
                        <input class="pos-input pos-input--icon" id="password" type="password" name="password"
                               value="{{ $password }}" required placeholder="@lang('lang_v1.password')" />
                        <button type="button" id="show_hide_icon" class="pos-eye-btn">
                            <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" viewBox="0 0 24 24"
                                 stroke-width="1.5" stroke="#6b7280" fill="none" stroke-linecap="round"
                                 stroke-linejoin="round">
                                <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                <path d="M10 12a2 2 0 1 0 4 0a2 2 0 0 0 -4 0" />
                                <path
                                        d="M21 12c-2.4 4 -5.4 6 -9 6c-3.6 0 -6.6 -2 -9 -6c2.4 -4 5.4 -6 9 -6c3.6 0 6.6 2 9 6" />
                            </svg>
                        </button>
                    </div>
                    @if ($errors->has('password'))
                        <span class="pos-error">{{ $errors->first('password') }}</span>
                    @endif
                </div>

                <label class="pos-checkbox-row">
                    <input type="checkbox" name="remember" {{ old('remember') ? 'checked' : '' }}>
                    <span>@lang('lang_v1.remember_me')</span>
                </label>

                @if (config('constants.enable_recaptcha'))
                    <div style="margin-top:.75rem;">
                        <div class="g-recaptcha" data-sitekey="{{ config('constants.google_recaptcha_key') }}"></div>
                        @if ($errors->has('g-recaptcha-response'))
                            <span class="pos-error">{{ $errors->first('g-recaptcha-response') }}</span>
                        @endif
                    </div>
                @endif

                <button type="submit" class="pos-submit">
                    @lang('lang_v1.login')
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.25" stroke-linecap="round" stroke-linejoin="round"><path d="M9 6l6 6-6 6"/></svg>
                </button>
            </form>

            {{-- Decorative only — not wired to any auth backend. Remove or hook up before shipping. --}}
{{--            <div class="pos-divider"><span>OR</span></div>--}}
{{--            <button type="button" class="pos-btn-outline">--}}
{{--                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.75" stroke-linecap="round" stroke-linejoin="round">--}}
{{--                    <path d="M12 3a5 5 0 0 0-5 5v2a7 7 0 0 0 1.2 3.9"/>--}}
{{--                    <path d="M12 3a5 5 0 0 1 5 5v2c0 1 -.1 2 -.4 2.9"/>--}}
{{--                    <path d="M8 10v-2a4 4 0 0 1 8 0v2"/>--}}
{{--                    <path d="M12 10v3.5"/>--}}
{{--                    <path d="M9 21c1-1.5 1.4-3 1.4-5.5"/>--}}
{{--                    <path d="M15 21c1-1.8 1.4-3.6 1.4-6.5"/>--}}
{{--                    <path d="M5 13.5c0 3 .8 5.6 2.3 7.5"/>--}}
{{--                </svg>--}}
{{--                Login with Fingerprint--}}
{{--            </button>--}}

            @if (!($request->segment(1) == 'business' && $request->segment(2) == 'register'))
                @if (config('constants.allow_registration'))
                    <div class="pos-register-cta">
                        <a href="{{ route('business.getRegister') }}@if (!empty(request()->lang)) {{ '?lang=' . request()->lang }} @endif">
                            {{ __('business.not_yet_registered') }}
                            <strong>{{ __('business.register_now') }}</strong>
                        </a>
                    </div>
                @endif
            @endif
        </div>
    </div>
@stop

@section('javascript')
    <script type="text/javascript">
        $(document).ready(function() {
            $('#show_hide_icon').off('click');
            $('.change_lang').click(function() {
                window.location = "{{ route('login') }}?lang=" + $(this).attr('value');
            });
            $('a.demo-login').click(function(e) {
                e.preventDefault();
                $('#username').val($(this).data('admin'));
                $('#password').val("{{ $password }}");
                $('form#login-form').submit();
            });

            $('#show_hide_icon').on('click', function(e) {
                e.preventDefault();
                const passwordInput = $('#password');

                if (passwordInput.attr('type') === 'password') {
                    passwordInput.attr('type', 'text');
                    $('#show_hide_icon').html(
                        '<svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" viewBox="0 0 24 24" stroke-width="1.5" stroke="#6b7280" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M10.585 10.587a2 2 0 0 0 2.829 2.828"/><path d="M16.681 16.673a8.717 8.717 0 0 1 -4.681 1.327c-3.6 0 -6.6 -2 -9 -6c1.272 -2.12 2.712 -3.678 4.32 -4.674m2.86 -1.146a9.055 9.055 0 0 1 1.82 -.18c3.6 0 6.6 2 9 6c-.666 1.11 -1.379 2.067 -2.138 2.87"/><path d="M3 3l18 18"/></svg>'
                    );
                } else if (passwordInput.attr('type') === 'text') {
                    passwordInput.attr('type', 'password');
                    $('#show_hide_icon').html(
                        '<svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" viewBox="0 0 24 24" stroke-width="1.5" stroke="#6b7280" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M10 12a2 2 0 1 0 4 0a2 2 0 0 0 -4 0"/><path d="M21 12c-2.4 4 -5.4 6 -9 6c-3.6 0 -6.6 -2 -9 -6c2.4 -4 5.4 -6 9 -6c3.6 0 6.6 2 9 6"/></svg>'
                    );
                }
            });
        });
    </script>
@endsection