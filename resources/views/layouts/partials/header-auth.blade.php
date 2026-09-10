@inject('request', 'Illuminate\Http\Request')

<div class="container-fluid">
    <!-- Language changer -->
    <div class="row">
        <div class="tw-absolute tw-top-2 md:tw-top-5 tw-left-4 md:tw-left-8 tw-flex tw-items-center tw-gap-4"
             style="text-align: left">

            <a href="{{ url('/') }}">
                <div class="width-50">
                    <img src="{{ asset('img/logo-small.png') }}" alt="lock" class="tw-object-fill opacity-50" />
                </div>
            </a>

            @if(config('constants.SHOW_REPAIR_STATUS_LOGIN_SCREEN') && Route::has('repair-status'))
                <a class="tw-text-white tw-font-medium tw-text-sm md:tw-text-base hover:tw-text-white"
                   href="{{ action([\Modules\Repair\Http\Controllers\CustomerRepairStatusController::class, 'index']) }}">
                    @lang('repair::lang.repair_status')
                </a>
            @endif

            @if(Route::has('member_scanner'))
                <a class="tw-text-white tw-font-medium tw-text-sm md:tw-text-base hover:tw-text-white"
                   href="{{ action([\Modules\Gym\Http\Controllers\MemberController::class, 'member_scanner']) }}">
                    @lang('gym::lang.gym_member_profile')
                </a>
            @endif

        </div>

        <div class="tw-absolute tw-top-3 md:tw-top-8 tw-right-4 md:tw-right-10 tw-flex tw-items-center tw-gap-4 md:tw-gap-2"
             style="text-align: left">

            {{-- Sign In --}}
            @if (!($request->segment(1) == 'business' && $request->segment(2) == 'register') && $request->segment(1) != 'login')
                <a class="tw-text-black tw-font-medium tw-text-sm md:tw-text-base language-button"
                   href="{{ action([\App\Http\Controllers\Auth\LoginController::class, 'login']) }}@if (!empty(request()->lang))?lang={{ request()->lang }}@endif"
                   style="display: inline-flex; align-items: center;"
                >
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 640 640"
                         style="width: 1.5em; height: 1.5em; fill: currentColor;">
                        <path d="M416 160L480 160C497.7 160 512 174.3 512 192L512 448C512 465.7 497.7 480 480 480L416 480C398.3 480 384 494.3 384 512C384 529.7 398.3 544 416 544L480 544C533 544 576 501 576 448L576 192C576 139 533 96 480 96L416 96C398.3 96 384 110.3 384 128C384 145.7 398.3 160 416 160zM406.6 342.6C419.1 330.1 419.1 309.8 406.6 297.3L278.6 169.3C266.1 156.8 245.8 156.8 233.3 169.3C220.8 181.8 220.8 202.1 233.3 214.6L306.7 288L96 288C78.3 288 64 302.3 64 320C64 337.7 78.3 352 96 352L306.7 352L233.3 425.4C220.8 437.9 220.8 458.2 233.3 470.7C245.8 483.2 266.1 483.2 278.6 470.7L406.6 342.7z"/>
                    </svg>
                    {{ __('business.sign_in') }}
                </a>
            @endif

            <!-- Register -->
            <div class="tw-rounded-full tw-h-10 md:tw-h-12 tw-w-24 tw-flex tw-items-center tw-justify-center">

                @if (!($request->segment(1) == 'business' && $request->segment(2) == 'register'))

                    <!-- Register URL -->
                    @if (config('constants.allow_registration'))
                        <a href="{{ route('business.getRegister') }}@if (!empty(request()->lang))?lang={{ request()->lang }}@endif"
                           class="tw-text-black tw-font-medium tw-text-sm md:tw-text-base hover:tw-text-white language-button"
                           style="display: inline-flex; align-items: center;"
                        >
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 640 640"
                                 style="width: 1.5em; height: 1.5em; fill: currentColor; margin-right: 5px;"
                            >
                                <path d="M285.7 368C384.2 368 464 447.8 464 546.3C464 562.7 450.7 576 434.3 576L77.7 576C61.3 576 48 562.7 48 546.3C48 447.8 127.8 368 226.3 368L285.7 368zM528 144C541.3 144 552 154.7 552 168L552 216L600 216C613.3 216 624 226.7 624 240C624 253.3 613.3 264 600 264L552 264L552 312C552 325.3 541.3 336 528 336C514.7 336 504 325.3 504 312L504 264L456 264C442.7 264 432 253.3 432 240C432 226.7 442.7 216 456 216L504 216L504 168C504 154.7 514.7 144 528 144zM256 312C189.7 312 136 258.3 136 192C136 125.7 189.7 72 256 72C322.3 72 376 125.7 376 192C376 258.3 322.3 312 256 312z"/>
                            </svg>
                            {{ __('business.register') }}
                        </a>

                        <!-- Pricing URL -->
                        @if (Route::has('pricing') && config('app.env') != 'demo' && $request->segment(1) != 'pricing')
                            <a class="tw-text-black tw-font-medium tw-text-sm md:tw-text-base hover:tw-text-white"
                               href="{{ action([\Modules\Superadmin\Http\Controllers\PricingController::class, 'index']) }}">
                                @lang('superadmin::lang.pricing')
                            </a>
                        @endif
                    @endif
                @endif
            </div>
            @include('layouts.partials.language_btn')
        </div>
    </div>
</div>
