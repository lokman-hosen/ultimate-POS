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
                            <img src="{{ asset('img/logo-small.png') }}" alt="{{ config('app.name', 'YaigoPos') }}">
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
                        <a href="{{ route('business.getRegister') }}@if (!empty(request()->lang)) {{ '?lang=' . request()->lang }} @endif" style="display: inline-flex; align-items: center;">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 640 640"
                                 style="width: 1.5em; height: 1.5em; fill: currentColor; margin-right: 5px;"
                            >
                                <path d="M285.7 368C384.2 368 464 447.8 464 546.3C464 562.7 450.7 576 434.3 576L77.7 576C61.3 576 48 562.7 48 546.3C48 447.8 127.8 368 226.3 368L285.7 368zM528 144C541.3 144 552 154.7 552 168L552 216L600 216C613.3 216 624 226.7 624 240C624 253.3 613.3 264 600 264L552 264L552 312C552 325.3 541.3 336 528 336C514.7 336 504 325.3 504 312L504 264L456 264C442.7 264 432 253.3 432 240C432 226.7 442.7 216 456 216L504 216L504 168C504 154.7 514.7 144 528 144zM256 312C189.7 312 136 258.3 136 192C136 125.7 189.7 72 256 72C322.3 72 376 125.7 376 192C376 258.3 322.3 312 256 312z"/>
                            </svg>
                            {{ __('business.register') }}
                        </a>
                    </div>

                    @if (Route::has('pricing') && config('app.env') != 'demo' && $request->segment(1) != 'pricing')
                        <a class="language-button" href="{{ action([\Modules\Superadmin\Http\Controllers\PricingController::class, 'index']) }}"
                           style="display: inline-flex; align-items: center;">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 640 640"
                                 style="width: 1.5em; height: 1.5em; fill: currentColor;">
                                <path d="M296 88C296 74.7 306.7 64 320 64C333.3 64 344 74.7 344 88L344 128L400 128C417.7 128 432 142.3 432 160C432 177.7 417.7 192 400 192L285.1 192C260.2 192 240 212.2 240 237.1C240 259.6 256.5 278.6 278.7 281.8L370.3 294.9C424.1 302.6 464 348.6 464 402.9C464 463.2 415.1 512 354.9 512L344 512L344 552C344 565.3 333.3 576 320 576C306.7 576 296 565.3 296 552L296 512L224 512C206.3 512 192 497.7 192 480C192 462.3 206.3 448 224 448L354.9 448C379.8 448 400 427.8 400 402.9C400 380.4 383.5 361.4 361.3 358.2L269.7 345.1C215.9 337.5 176 291.4 176 237.1C176 176.9 224.9 128 285.1 128L296 128L296 88z"/>
                            </svg>
                            @lang('superadmin::lang.pricing')
                        </a>
                    @endif
                @endif
            @endif

            @if ($request->segment(1) != 'login')
                <a class="language-button" href="{{action([\App\Http\Controllers\Auth\LoginController::class,'login'])}}
                    @if (!empty(request()->lang)) {{ '?lang='.request()->lang}} @endif"
                   style="display: inline-flex; align-items: center;">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 640 640"
                         style="width: 1.5em; height: 1.5em; fill: currentColor;">
                        <path d="M416 160L480 160C497.7 160 512 174.3 512 192L512 448C512 465.7 497.7 480 480 480L416 480C398.3 480 384 494.3 384 512C384 529.7 398.3 544 416 544L480 544C533 544 576 501 576 448L576 192C576 139 533 96 480 96L416 96C398.3 96 384 110.3 384 128C384 145.7 398.3 160 416 160zM406.6 342.6C419.1 330.1 419.1 309.8 406.6 297.3L278.6 169.3C266.1 156.8 245.8 156.8 233.3 169.3C220.8 181.8 220.8 202.1 233.3 214.6L306.7 288L96 288C78.3 288 64 302.3 64 320C64 337.7 78.3 352 96 352L306.7 352L233.3 425.4C220.8 437.9 220.8 458.2 233.3 470.7C245.8 483.2 266.1 483.2 278.6 470.7L406.6 342.7z"/>
                    </svg>
                    {{ __('business.sign_in') }}
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