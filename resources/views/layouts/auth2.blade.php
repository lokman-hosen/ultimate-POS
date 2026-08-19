<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <!-- Tell the browser to be responsive to screen width -->
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title') - {{ config('app.name', 'POS') }}</title>
    @if(!route_is('login') and !route_is('password.request'))
        @include('layouts.partials.css')
    @endif


    @include('layouts.partials.extracss_auth')

    <!-- Brand typography for the login page -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
            href="https://fonts.googleapis.com/css2?family=Space+Grotesk:wght@500;600;700&family=Inter:wght@400;500;600&family=IBM+Plex+Mono:wght@400;500&display=swap"
            rel="stylesheet">

    <!-- Custom login page stylesheet (plain CSS, loaded after app.css so it always wins) -->
    <link rel="stylesheet" href="{{ asset('css/custom.css') }}">

    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
    <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
    <script src='https://www.google.com/recaptcha/api.js'></script>

    <style>
        .wizard>.content {
            background-color: white !important;
        }
    </style>
</head>

<body class="pace-done" data-new-gr-c-s-check-loaded="14.1172.0" data-gr-ext-installed="" cz-shortcut-listen="true">
@inject('request', 'Illuminate\Http\Request')
@if (session('status') && session('status.success'))
    <input type="hidden" id="status_span" data-status="{{ session('status.success') }}"
           data-msg="{{ session('status.msg') }}">
@endif

<div class="pos-auth-page">

    @if(!route_is('business.getRegister') and !route_is('password.request') and !route_is('pricing'))
        {{-- ================= LEFT: brand / caption panel ================= --}}
        <aside class="pos-brand">
{{--            <div class="pos-brand__dotgrid"></div>--}}

            <div class="pos-brand__body">
                <div class="pos-brand__top">
                    <a href="{{ url('/') }}" class="pos-brand__logo">
                        <span class="pos-brand__logo-badge">
                            <img src="{{ asset('img/logo-small.png') }}" alt="{{ config('app.name', 'UltimatePOS') }}">
                        </span>
                        <span class="pos-badge-pill">POS</span>
                    </a>

                    {{-- Utility links that used to live next to the logo --}}
                    <div class="pos-brand__utility">
                        @if (config('constants.SHOW_REPAIR_STATUS_LOGIN_SCREEN') && Route::has('repair-status'))
                            <a href="{{ action([\Modules\Repair\Http\Controllers\CustomerRepairStatusController::class, 'index']) }}">
                                @lang('repair::lang.repair_status')
                            </a>
                        @endif

                        @if (Route::has('member_scanner'))
                            <a href="{{ action([\Modules\Gym\Http\Controllers\MemberController::class, 'member_scanner']) }}">
                                @lang('gym::lang.gym_member_profile')
                            </a>
                        @endif
                    </div>
                </div>

                <div class="pos-eyebrow-row">
                    <p class="pos-eyebrow">Access terminal</p>
                    <span class="pos-progress-line"></span>
                </div>

                <h1 class="pos-headline">Every sale, every shop,<br><span class="pos-headline__accent">one screen.</span></h1>
                <p class="pos-subtext">
                    From pharmacy counters to restaurant floors — manage inventory, staff and sales
                    without leaving this screen.
                </p>

                <ul class="pos-feature-grid">
                    <li class="pos-feature-chip">
                        <span class="pos-feature-icon pos-feature-icon--a">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.75" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M21 8l-9-5-9 5 9 5 9-5z"/>
                                <path d="M3 8v8l9 5 9-5V8"/>
                                <path d="M12 13v8"/>
                            </svg>
                        </span>
                        <span class="pos-feature-label">Real-time inventory sync</span>
                        <span class="pos-feature-check">
                            <svg viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M16.7 5.3a1 1 0 010 1.4l-7.4 7.4a1 1 0 01-1.4 0L3.3 9.5a1 1 0 111.4-1.4l3.6 3.6 6.7-6.7a1 1 0 011.4 0z" clip-rule="evenodd"/></svg>
                        </span>
                    </li>
                    <li class="pos-feature-chip">
                        <span class="pos-feature-icon pos-feature-icon--b">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.75" stroke-linecap="round" stroke-linejoin="round">
                                <circle cx="8" cy="12" r="5"/>
                                <path d="M14 8a5 5 0 0 1 0 8"/>
                                <path d="M11 10.2c.5-.6 1.2-1 2-1"/>
                            </svg>
                        </span>
                        <span class="pos-feature-label">Multi-outlet & multi-currency</span>
                        <span class="pos-feature-check">
                            <svg viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M16.7 5.3a1 1 0 010 1.4l-7.4 7.4a1 1 0 01-1.4 0L3.3 9.5a1 1 0 111.4-1.4l3.6 3.6 6.7-6.7a1 1 0 011.4 0z" clip-rule="evenodd"/></svg>
                        </span>
                    </li>
                    <li class="pos-feature-chip">
                        <span class="pos-feature-icon pos-feature-icon--c">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.75" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M7 18a4.5 4.5 0 0 1-.5-8.98A5.5 5.5 0 0 1 17.3 8.3 4 4 0 0 1 17 18H7z"/>
                            </svg>
                        </span>
                        <span class="pos-feature-label">Works online or offline</span>
                        <span class="pos-feature-check">
                            <svg viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M16.7 5.3a1 1 0 010 1.4l-7.4 7.4a1 1 0 01-1.4 0L3.3 9.5a1 1 0 111.4-1.4l3.6 3.6 6.7-6.7a1 1 0 011.4 0z" clip-rule="evenodd"/></svg>
                        </span>
                    </li>
                </ul>
            </div>

            <div class="pos-brand__footer">
                <div class="pos-brand__total">
                    <span class="pos-brand__total-label">Businesses served</span>
                    <span class="pos-brand__total-value">10,000+</span>
                </div>
                <div class="pos-trust-row">
                    <span class="pos-trust-stars" aria-hidden="true">
                        <svg viewBox="0 0 20 20" fill="currentColor"><path d="M10 1.5l2.5 5.6 6 .6-4.5 4 1.3 6-5.3-3.2-5.3 3.2 1.3-6-4.5-4 6-.6z"/></svg>
                        <svg viewBox="0 0 20 20" fill="currentColor"><path d="M10 1.5l2.5 5.6 6 .6-4.5 4 1.3 6-5.3-3.2-5.3 3.2 1.3-6-4.5-4 6-.6z"/></svg>
                        <svg viewBox="0 0 20 20" fill="currentColor"><path d="M10 1.5l2.5 5.6 6 .6-4.5 4 1.3 6-5.3-3.2-5.3 3.2 1.3-6-4.5-4 6-.6z"/></svg>
                        <svg viewBox="0 0 20 20" fill="currentColor"><path d="M10 1.5l2.5 5.6 6 .6-4.5 4 1.3 6-5.3-3.2-5.3 3.2 1.3-6-4.5-4 6-.6z"/></svg>
                        <svg viewBox="0 0 20 20" fill="currentColor"><path d="M10 1.5l2.5 5.6 6 .6-4.5 4 1.3 6-5.3-3.2-5.3 3.2 1.3-6-4.5-4 6-.6z"/></svg>
                    </span>
                    <span class="pos-trust-text">Trusted by businesses worldwide</span>
                </div>
            </div>

{{--            <div class="pos-tear"></div>--}}
            <span class="pos-seam-toggle" aria-hidden="true">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M9 6l6 6-6 6"/></svg>
            </span>
        </aside>
    @endif

    {{-- ================= RIGHT: utility nav + page content ================= --}}
    <div class="pos-content">

        <div class="pos-topnav">

            {{-- mobile-only utility links (the brand panel's copy is hidden below md) --}}
            <div class="pos-topnav__mobile-utility">
                @if (config('constants.SHOW_REPAIR_STATUS_LOGIN_SCREEN') && Route::has('repair-status'))
                    <a href="{{ action([\Modules\Repair\Http\Controllers\CustomerRepairStatusController::class, 'index']) }}">
                        @lang('repair::lang.repair_status')
                    </a>
                @endif

                @if (Route::has('member_scanner'))
                    <a href="{{ action([\Modules\Gym\Http\Controllers\MemberController::class, 'member_scanner']) }}">
                        @lang('gym::lang.gym_member_profile')
                    </a>
                @endif
            </div>

            @if (!($request->segment(1) == 'business' && $request->segment(2) == 'register'))
                @if (config('constants.allow_registration'))
                    <div class="pos-register-pill">
                        <a href="{{ route('business.getRegister') }}@if (!empty(request()->lang)) {{ '?lang=' . request()->lang }} @endif">
                            {{ __('business.register') }}
                        </a>
                    </div>

                    @if (Route::has('pricing') && config('app.env') != 'demo' && $request->segment(1) != 'pricing')
                        <a class="language-button" href="{{ action([\Modules\Superadmin\Http\Controllers\PricingController::class, 'index']) }}">@lang('superadmin::lang.pricing')</a>
                    @endif
                @endif
            @endif

            @if ($request->segment(1) != 'login')
                <a class="language-button" href="{{ action([\App\Http\Controllers\Auth\LoginController::class, 'login']) }}
                    @if (!empty(request()->lang)) {{ '?lang=' . request()->lang }} @endif">{{ __('business.sign_in') }}
                </a>
            @endif

            @include('layouts.partials.language_btn')
        </div>

        <div class="pos-main">
            @yield('content')
        </div>
    </div>
</div>

@include('layouts.partials.javascripts')

<!-- Scripts -->
<script src="{{ asset('js/login.js?v=' . $asset_v) }}"></script>

@yield('javascript')

<script type="text/javascript">
    $(document).ready(function() {
        $('.select2_register').select2();
    });
</script>
</body>

</html>