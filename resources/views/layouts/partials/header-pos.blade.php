<!-- default value -->
@php
    $go_back_url = action([\App\Http\Controllers\SellPosController::class, 'index']);
    $transaction_sub_type = '';
    $view_suspended_sell_url = action([\App\Http\Controllers\SellController::class, 'index']) . '?suspended=1';
    $pos_redirect_url = action([\App\Http\Controllers\SellPosController::class, 'create']);
@endphp

@if (!empty($pos_module_data))
    @foreach ($pos_module_data as $key => $value)
        @php
            if (!empty($value['go_back_url'])) {
                $go_back_url = $value['go_back_url'];
            }

            if (!empty($value['transaction_sub_type'])) {
                $transaction_sub_type = $value['transaction_sub_type'];
                $view_suspended_sell_url .= '&transaction_sub_type=' . $transaction_sub_type;
                $pos_redirect_url .= '?sub_type=' . $transaction_sub_type;
            }
        @endphp
    @endforeach
@endif
<input type="hidden" name="transaction_sub_type" id="transaction_sub_type" value="{{ $transaction_sub_type }}">
@inject('request', 'Illuminate\Http\Request')
<style>
    .pos-location-highlight {
        background-color: #eff6ff !important; 
        border: 1px solid #bfdbfe !important; 
        border-radius: 8px;
        padding: 4px 12px;
        display: flex;
        align-items: center;
        gap: 8px;
    }
    .pos-location-highlight p { margin: 0; }
    .pos-location-highlight strong {
        color: #1e40af !important; 
        margin: 0;
        display: flex;
        align-items: center;
        gap: 6px;
        font-size: 14px;
        white-space: nowrap;
    }
    .pos-location-highlight select.form-control {
        background-color: #ffffff !important;
        border: 1px solid #93c5fd !important;
        color: #1e3a8a !important;
        font-weight: 600;
        border-radius: 6px;
        height: 32px;
        padding: 2px 8px;
        box-shadow: 0 1px 2px rgba(0,0,0,0.05);
        width: 100%;
    }
    .pos-location-name-text {
        font-weight: 700;
        color: #1e40af !important;
        font-size: 15px;
        white-space: nowrap;
    }
    
    /* Make right buttons colorful and unified */
    .pos-header-action-btn {
        background-color: #eef2ff !important;
        border-color: #c7d2fe !important;
        transition: all 0.2s ease;
    }
    .pos-header-action-btn:hover {
        background-color: #e0e7ff !important;
        border-color: #a5b4fc !important;
    }

    /* Screen between 768px and 1285px: compact icon-only buttons, hide datetime badge & dividers */
    @media (min-width: 768px) and (max-width: 1285px) {
        .pos-header-datetime {
            display: none !important;
        }
        .pos-location-highlight {
            padding: 3px 8px !important;
            gap: 4px !important;
        }
        .pos-location-label {
            display: none !important;
        }
        .pos-nav-divider {
            display: none !important;
        }
        #pos_header_more_options {
            gap: 3px !important;
            flex-shrink: 0 !important;
            width: auto !important;
            justify-content: flex-end !important;
        }
        #pos_header_more_options > a,
        #pos_header_more_options > button {
            width: 36px !important;
            height: 36px !important;
            min-width: 36px !important;
            padding: 0 !important;
            display: inline-flex !important;
            align-items: center !important;
            justify-content: center !important;
            border-radius: 8px !important;
        }
        #pos_header_more_options strong {
            margin: 0 !important;
            padding: 0 !important;
            display: inline-flex !important;
            align-items: center !important;
            justify-content: center !important;
        }
        #pos_header_more_options .pos-btn-text {
            display: none !important;
        }
        #pos_header_more_options svg {
            width: 18px !important;
            height: 18px !important;
            margin: 0 !important;
        }
    }

    /* Large screens (>= 1286px) */
    @media (min-width: 1286px) {
        .pos-header-datetime {
            display: flex !important;
        }
        #pos_header_more_options > a.tw-h-10,
        #pos_header_more_options > button.tw-h-10 {
            width: 40px !important;
            height: 40px !important;
            min-width: 40px;
        }
        #pos_header_more_options > a.tw-h-10 strong,
        #pos_header_more_options > button.tw-h-10 strong {
            margin: 0 !important;
        }
        #pos_header_more_options > a.tw-h-auto,
        #pos_header_more_options > button.tw-h-auto {
            padding: 6px 14px !important;
            height: 40px !important;
            white-space: nowrap;
        }
        #pos_header_more_options {
            gap: 4px !important;
        }
    }
</style>
<div class="col-md-12 no-print pos-header">
    <input type="hidden" id="pos_redirect_url" value="{{ $pos_redirect_url }}">
    <div
        class="tw-flex tw-flex-col md:tw-flex-row tw-items-center tw-justify-between tw-shadow-[rgba(17,_17,_26,_0.1)_0px_0px_16px] tw-bg-white tw-rounded-xl tw-mx-0 tw-mt-1 tw-mb-0 md:tw-mb-0 pos-header-inner" style="padding: 4px 12px !important; min-height: 0;">
        <div class="tw-w-full md:tw-w-auto tw-flex-1 tw-min-w-0">
            <div class="tw-flex tw-items-center pos-location-highlight">
                <p><strong>
                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M9 11a3 3 0 1 0 6 0a3 3 0 0 0 -6 0"/><path d="M17.657 16.657l-4.243 4.243a2 2 0 0 1 -2.827 0l-4.244 -4.243a8 8 0 1 1 11.314 0z"/></svg>
                    <span class="pos-location-label">@lang('sale.location'): &nbsp;</span>
                </strong></p>
                <div style="min-width: 120px; max-width: 260px; flex: 1;">
                    @if (empty($transaction->location_id))
                        @if (count($business_locations) > 1)
                            {!! Form::select(
                                'select_location_id',
                                $business_locations,
                                $default_location->id ?? null,
                                ['class' => 'form-control input-sm', 'id' => 'select_location_id', 'required', 'autofocus'],
                                $bl_attributes,
                            ) !!}
                        @else
                            <span class="pos-location-name-text">{{ $default_location->name }}</span>
                        @endif
                    @else
                        <span class="pos-location-name-text">{{ $transaction->location->name }}</span>
                    @endif
                </div>
                <div
                    class="pos-header-datetime tw-hidden tw-items-center tw-gap-1.5 tw-bg-[#646EE4] hover:tw-bg-[#414aac] tw-h-10 tw-px-3 tw-rounded-md tw-shrink-0">
                    <span class="curr_datetime text-white tw-font-semibold tw-text-xs tw-leading-none">{{ @format_datetime('now') }}</span>
                    <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-keyboard hover-q text-white tw-opacity-70 tw-cursor-pointer" width="14" height="14" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true" data-container="body" data-toggle="popover" data-placement="bottom" data-content="@include('sale_pos.partials.keyboard_shortcuts_details')" data-html="true" data-trigger="hover" data-original-title="" title=""><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M2 6m0 2a2 2 0 0 1 2 -2h16a2 2 0 0 1 2 2v8a2 2 0 0 1 -2 2h-16a2 2 0 0 1 -2 -2z"/><path d="M6 10l0 .01"/><path d="M10 10l0 .01"/><path d="M14 10l0 .01"/><path d="M18 10l0 .01"/><path d="M6 14l0 .01"/><path d="M18 14l0 .01"/><path d="M10 14l4 0"/></svg>
                </div>

                @if (empty($pos_settings['hide_product_suggestion']))
                    <button type="button" title="{{ __('lang_v1.view_products') }}" data-placement="bottom"
                        class="pos-header-action-btn tw-shadow-[rgba(17,_17,_26,_0.1)_0px_0px_16px] tw-cursor-pointer tw-border tw-flex tw-items-center tw-justify-center tw-rounded-md tw-w-10 tw-h-10 tw-text-gray-600 btn-modal tw-ml-auto tw-block md:tw-hidden active:tw-scale-95 tw-transition-transform"
                        data-toggle="modal" data-target="#mobile_product_suggestion_modal">
                        <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-shopping-bag tw-text-[#00935F]" width="20" height="20" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M6.331 8h11.339a2 2 0 0 1 1.977 2.304l-1.255 8.152a3 3 0 0 1 -2.966 2.544h-6.852a3 3 0 0 1 -2.965 -2.544l-1.255 -8.152a2 2 0 0 1 1.977 -2.304z"/><path d="M9 11v-5a3 3 0 0 1 6 0v5"/></svg>
                    </button>
                @endif

                <span class="tw-block md:tw-hidden @if(empty($pos_settings['hide_product_suggestion'])) tw-ml-0 @else tw-ml-auto @endif">
                    <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-menu-2 hamburger tw-ml-2 tw-mr-1 tw-cursor-pointer tw-inline-block" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"
                        onclick="document.getElementById('pos_header_more_options').classList.toggle('tw-hidden')"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M4 6l16 0"/><path d="M4 12l16 0"/><path d="M4 18l16 0"/></svg>
                </span>

            </div>
        </div>

        <div class="tw-w-full md:tw-w-auto !tw-p-0 tw-flex tw-items-center md:tw-justify-end tw-gap-2 md:tw-gap-1 tw-flex-col md:tw-flex-row tw-hidden md:tw-flex"
            id="pos_header_more_options">
            {{-- ===== Navigation ===== --}}
            <a href="{{ $go_back_url }}" title="{{ __('lang_v1.go_back') }}"
                class="pos-header-action-btn tw-shadow-[rgba(17,_17,_26,_0.1)_0px_0px_16px] tw-cursor-pointer tw-border tw-flex tw-items-center tw-justify-center tw-rounded-md md:tw-w-10 tw-w-auto tw-h-10 tw-text-gray-600 active:tw-scale-95 tw-transition-transform">
                <strong class="!tw-m-3">
                    <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-chevron-left tw-text-[#009EE4] tw-inline-block" width="20" height="20" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M15 6l-9 6l9 6"/></svg>
                    <span class="tw-inline md:tw-hidden">{{ __('lang_v1.go_back') }}</span>
                </strong>
            </a>
            <span class="pos-nav-divider tw-inline-block tw-w-px tw-h-[18px] tw-bg-[#e2e8f0] tw-flex-shrink-0 tw-self-center tw-rounded-[1px] tw-mx-[3px]"></span>

            {{-- ===== Sale Operations ===== --}}
            <button type="button"
                class="pos-header-action-btn tw-shadow-[rgba(17,_17,_26,_0.1)_0px_0px_16px] tw-cursor-pointer tw-border tw-flex tw-items-center tw-justify-center tw-rounded-md md:tw-w-10 tw-w-auto tw-h-10 tw-text-gray-600 active:tw-scale-95 tw-transition-transform popover-default"
                id="return_sale" title="@lang('lang_v1.sell_return')" data-toggle="popover" data-trigger="click"
                data-content='<div class="m-8"><input type="text" class="form-control" placeholder="@lang('sale.invoice_no')" id="send_for_sell_return_invoice_no"></div><div class="w-100 text-center"><button type="button" class="tw-dw-btn tw-dw-btn-error tw-text-white tw-dw-btn-sm" id="send_for_sell_return">@lang('lang_v1.send')</button></div>'
                data-html="true" data-placement="bottom">
                <strong class="!tw-m-3">
                    <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-arrow-back-up tw-text-[#EF4B53] tw-inline-block" width="20" height="20" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M9 14l-4 -4l4 -4"/><path d="M5 10h11a4 4 0 1 1 0 8h-1"/></svg>
                    <span class="tw-inline md:tw-hidden">{{ __('lang_v1.sell_return') }}</span>
                </strong>
            </button>

            <button type="button" id="view_suspended_sales" title="{{ __('lang_v1.view_suspended_sales') }}"
                class="pos-header-action-btn tw-shadow-[rgba(17,_17,_26,_0.1)_0px_0px_16px] tw-cursor-pointer tw-border tw-flex tw-items-center tw-justify-center tw-rounded-md md:tw-w-10 tw-w-auto tw-h-10 tw-text-gray-600 active:tw-scale-95 tw-transition-transform btn-modal"
                data-container=".view_modal" data-href="{{ $view_suspended_sell_url }}">
                <strong class="!tw-m-3">
                    <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-player-pause tw-text-[#A5ADBB] tw-inline-block" width="20" height="20" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M6 5m0 1a1 1 0 0 1 1 -1h2a1 1 0 0 1 1 1v12a1 1 0 0 1 -1 1h-2a1 1 0 0 1 -1 -1z"/><path d="M14 5m0 1a1 1 0 0 1 1 -1h2a1 1 0 0 1 1 1v12a1 1 0 0 1 -1 1h-2a1 1 0 0 1 -1 -1z"/></svg>
                    <span class="tw-inline md:tw-hidden">{{ __('lang_v1.view_suspended_sales') }}</span>
                </strong>
            </button>

            @if (!isset($pos_settings['hide_recent_trans']) || $pos_settings['hide_recent_trans'] == 0)
                <button type="button"
                    class="md:tw-hidden tw-shadow-[rgba(17,_17,_26,_0.1)_0px_0px_16px] tw-bg-white hover:tw-bg-white/60 tw-cursor-pointer tw-border-2 tw-flex tw-items-center tw-justify-center tw-rounded-md md:tw-w-10 tw-w-auto tw-h-10 tw-text-gray-600 active:tw-scale-95 tw-transition-transform"
                    data-toggle="modal" data-target="#recent_transactions_modal" id="recent-transactions">
                        <strong class="!tw-m-3">
                            <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-history tw-text-[#646EE4] tw-inline-block" width="20" height="20" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M12 8l0 4l2 2"/><path d="M3.05 11a9 9 0 1 1 .5 4m-.5 5v-5h5"/></svg>
                            <span class="tw-inline md:tw-hidden">{{ __('lang_v1.recent_transactions') }}</span>
                        </strong>
                </button>
            @endif

            @if (auth()->user()->can('view_cash_register') || auth()->user()->can('close_cash_register'))
                <span class="pos-nav-divider tw-inline-block tw-w-px tw-h-[18px] tw-bg-[#e2e8f0] tw-flex-shrink-0 tw-self-center tw-rounded-[1px] tw-mx-[3px]"></span>
            @endif

            {{-- ===== Cash Register ===== --}}
            @can('view_cash_register')
                <button type="button" id="register_details" title="{{ __('cash_register.register_details') }}"
                    class="pos-header-action-btn tw-shadow-[rgba(17,_17,_26,_0.1)_0px_0px_16px] tw-cursor-pointer tw-border tw-flex tw-items-center tw-justify-center tw-rounded-md md:tw-w-10 tw-w-auto tw-h-10 tw-text-gray-600 active:tw-scale-95 tw-transition-transform btn-modal"
                    data-container=".register_details_modal"
                    data-href="{{ action([\App\Http\Controllers\CashRegisterController::class, 'getRegisterDetails']) }}">

                    <strong class="!tw-m-3">
                        <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-briefcase tw-text-[#00935F] tw-inline-block" width="20" height="20" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M3 7m0 2a2 2 0 0 1 2 -2h14a2 2 0 0 1 2 2v9a2 2 0 0 1 -2 2h-14a2 2 0 0 1 -2 -2z"/><path d="M8 7v-2a2 2 0 0 1 2 -2h4a2 2 0 0 1 2 2v2"/><path d="M12 12l0 .01"/><path d="M3 13a20 20 0 0 0 18 0"/></svg>
                        <span class="tw-inline md:tw-hidden">{{ __('cash_register.register_details') }}</span>
                    </strong>
                </button>
            @endcan

            @can('close_cash_register')
                <button type="button" id="close_register" title="{{ __('cash_register.close_register') }}"
                    class="pos-header-action-btn tw-shadow-[rgba(17,_17,_26,_0.1)_0px_0px_16px] tw-cursor-pointer tw-border tw-flex tw-items-center tw-justify-center tw-rounded-md md:tw-w-10 tw-w-auto tw-h-10 tw-text-gray-600 active:tw-scale-95 tw-transition-transform btn-modal"
                    data-container=".close_register_modal"
                    data-href="{{ action([\App\Http\Controllers\CashRegisterController::class, 'getCloseRegister']) }}">
                    <strong class="!tw-m-3">
                        <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-square-rounded-x tw-text-[#EF4B53] tw-inline-block" width="20" height="20" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M10 10l4 4m0 -4l-4 4"/><path d="M12 3c7.2 0 9 1.8 9 9s-1.8 9 -9 9s-9 -1.8 -9 -9s1.8 -9 9 -9z"/></svg>
                        <span class="tw-inline md:tw-hidden">{{ __('cash_register.close_register') }}</span>
                    </strong>
                </button>
            @endcan

            @if (!empty($pos_settings['inline_service_staff']) || in_array('tables', $enabled_modules) || in_array('service_staff', $enabled_modules))
                <span class="pos-nav-divider tw-inline-block tw-w-px tw-h-[18px] tw-bg-[#e2e8f0] tw-flex-shrink-0 tw-self-center tw-rounded-[1px] tw-mx-[3px]"></span>
            @endif

            {{-- ===== Service Staff ===== --}}
            @if (!empty($pos_settings['inline_service_staff']))
                <button type="button" id="show_service_staff_availability"
                    title="{{ __('lang_v1.service_staff_availability') }}"
                    class="pos-header-action-btn tw-shadow-[rgba(17,_17,_26,_0.1)_0px_0px_16px] tw-cursor-pointer tw-border tw-flex tw-items-center tw-justify-center tw-rounded-md md:tw-w-10 tw-w-auto tw-h-10 tw-text-gray-600 active:tw-scale-95 tw-transition-transform"
                    data-container=".view_modal"
                    data-href="{{ action([\App\Http\Controllers\SellPosController::class, 'showServiceStaffAvailibility']) }}">
                    <strong class="!tw-m-3">
                        <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-users tw-text-[#646EE4] tw-inline-block" width="20" height="20" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M9 7m-4 0a4 4 0 1 0 8 0a4 4 0 1 0 -8 0"/><path d="M3 21v-2a4 4 0 0 1 4 -4h4a4 4 0 0 1 4 4v2"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/><path d="M21 21v-2a4 4 0 0 0 -3 -3.85"/></svg>
                        <span class="tw-inline md:tw-hidden">{{ __('lang_v1.service_staff_availability') }}</span>
                    </strong>
                </button>
            @endif

            @if (
                !empty($pos_settings['inline_service_staff']) ||
                    (in_array('tables', $enabled_modules) || in_array('service_staff', $enabled_modules)))
                <button type="button"
                    class="pos-header-action-btn tw-shadow-[rgba(17,_17,_26,_0.1)_0px_0px_16px] tw-cursor-pointer tw-border tw-flex tw-items-center tw-justify-center tw-rounded-md md:tw-w-10 tw-w-auto tw-h-10 tw-text-gray-600 active:tw-scale-95 tw-transition-transform popover-default"
                    id="service_staff_replacement" title="{{ __('restaurant.service_staff_replacement') }}"
                    data-toggle="popover" data-trigger="click"
                    data-content='<div class="m-8"><input type="text" class="form-control" placeholder="@lang('sale.invoice_no')" id="send_for_sell_service_staff_invoice_no"></div><div class="w-100 text-center"><button type="button" class="tw-dw-btn tw-dw-btn-xs tw-dw-btn-outline tw-dw-btn-error" id="send_for_sercice_staff_replacement">@lang('lang_v1.send')</button></div>'
                    data-html="true" data-placement="bottom">

                    <strong class="!tw-m-3">
                        <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-user-plus tw-text-[#646EE4] tw-inline-block" width="20" height="20" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M8 7a4 4 0 1 0 8 0a4 4 0 0 0 -8 0"/><path d="M16 19h6"/><path d="M19 16v6"/><path d="M6 21v-2a4 4 0 0 1 4 -4h4"/></svg>
                        <span class="tw-inline md:tw-hidden">{{ __('restaurant.service_staff_replacement') }}</span>
                    </strong>
                </button>
            @endif

            <span class="pos-nav-divider tw-inline-block tw-w-px tw-h-[18px] tw-bg-[#e2e8f0] tw-flex-shrink-0 tw-self-center tw-rounded-[1px] tw-mx-[3px]"></span>

            {{-- ===== Tools ===== --}}
            <button title="@lang('lang_v1.calculator')" id="btnCalculator" type="button"
                class="pos-header-action-btn tw-shadow-[rgba(17,_17,_26,_0.1)_0px_0px_16px] tw-cursor-pointer tw-border tw-flex tw-items-center tw-justify-center tw-rounded-md md:tw-w-10 tw-w-auto tw-h-10 tw-text-gray-600 active:tw-scale-95 tw-transition-transform popover-default"
                data-toggle="popover" data-trigger="click" data-content='@include('layouts.partials.calculator')' data-html="true"
                data-placement="bottom">

                <strong class="!tw-m-3">
                    <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-calculator tw-text-[#00935F] tw-inline-block" width="20" height="20" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M4 3m0 2a2 2 0 0 1 2 -2h12a2 2 0 0 1 2 2v14a2 2 0 0 1 -2 2h-12a2 2 0 0 1 -2 -2z"/><path d="M8 7m0 1a1 1 0 0 1 1 -1h6a1 1 0 0 1 1 1v1a1 1 0 0 1 -1 1h-6a1 1 0 0 1 -1 -1z"/><path d="M8 14l0 .01"/><path d="M12 14l0 .01"/><path d="M16 14l0 .01"/><path d="M8 17l0 .01"/><path d="M12 17l0 .01"/><path d="M16 17l0 .01"/></svg>
                    <span class="tw-inline md:tw-hidden">{{ __('lang_v1.calculator') }}</span>
                </strong>
            </button>

            <button type="button" title="{{ __('lang_v1.full_screen') }}"
                class="pos-header-action-btn tw-shadow-[rgba(17,_17,_26,_0.1)_0px_0px_16px] tw-cursor-pointer tw-border tw-flex tw-items-center tw-justify-center tw-rounded-md md:tw-w-10 tw-w-auto tw-h-10 tw-text-gray-600 active:tw-scale-95 tw-transition-transform"
                id="full_screen">
                <strong class="!tw-m-3">
                    <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-maximize tw-text-[#646EE4] tw-inline-block" width="20" height="20" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M4 8v-2a2 2 0 0 1 2 -2h2"/><path d="M4 16v2a2 2 0 0 0 2 2h2"/><path d="M16 4h2a2 2 0 0 1 2 2v2"/><path d="M16 20h2a2 2 0 0 0 2 -2v-2"/></svg>
                    <span class="tw-inline md:tw-hidden">Full Screen</span>
                </strong>
            </button>

            @if (!empty($pos_settings['customer_display_screen']))
                <a href="{{route('pos_display')}}" id="customer_display_screen"  onclick="window.open(this.href, 'customer_display', 'width='+screen.width+',height='+screen.height+',top=0,left=0'); return false;"   title="{{ __('lang_v1.customer_display_screen') }}"
                    class="pos-header-action-btn tw-shadow-[rgba(17,_17,_26,_0.1)_0px_0px_16px] tw-cursor-pointer tw-border tw-flex tw-items-center tw-justify-center tw-rounded-md md:tw-w-10 tw-w-auto tw-h-10 tw-text-gray-600 active:tw-scale-95 tw-transition-transform">
                    <strong class="!tw-m-3">
                        <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-device-desktop tw-text-[#646EE4] tw-inline-block" width="20" height="20" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M3 5a1 1 0 0 1 1 -1h16a1 1 0 0 1 1 1v10a1 1 0 0 1 -1 1h-16a1 1 0 0 1 -1 -1v-10z"/><path d="M7 20h10"/><path d="M9 16v4"/><path d="M15 16v4"/></svg>
                        <span class="tw-inline md:tw-hidden">{{ __('lang_v1.customer_display_screen') }}</span>
                    </strong>
                </a>
            @endif

            @if ((Module::has('Repair') && $transaction_sub_type != 'repair') || (in_array('pos_sale', $enabled_modules) && !empty($transaction_sub_type) && auth()->user()->can('sell.create')) || auth()->user()->can('expense.add'))
                <span class="pos-nav-divider tw-inline-block tw-w-px tw-h-[18px] tw-bg-[#e2e8f0] tw-flex-shrink-0 tw-self-center tw-rounded-[1px] tw-mx-[3px]"></span>
            @endif

            {{-- ===== Actions ===== --}}
            @if (Module::has('Repair') && $transaction_sub_type != 'repair')
                @include('repair::layouts.partials.pos_header')
            @endif

            @if (in_array('pos_sale', $enabled_modules) && !empty($transaction_sub_type))
                @can('sell.create')
                    <a href="{{ action([\App\Http\Controllers\SellPosController::class, 'create']) }}"
                        title="@lang('sale.pos_sale')"
                        class="pos-header-action-btn tw-shadow-[rgba(17,_17,_26,_0.1)_0px_0px_16px] tw-cursor-pointer tw-border tw-w-auto tw-h-auto tw-py-1 tw-px-4 active:tw-scale-95 tw-transition-transform tw-rounded-md">
                        <strong class="tw-inline-flex tw-items-center tw-gap-1.5"><svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-layout-grid tw-text-[#00935F]" width="18" height="18" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M4 4m0 1a1 1 0 0 1 1 -1h4a1 1 0 0 1 1 1v4a1 1 0 0 1 -1 1h-4a1 1 0 0 1 -1 -1z"/><path d="M14 4m0 1a1 1 0 0 1 1 -1h4a1 1 0 0 1 1 1v4a1 1 0 0 1 -1 1h-4a1 1 0 0 1 -1 -1z"/><path d="M4 14m0 1a1 1 0 0 1 1 -1h4a1 1 0 0 1 1 1v4a1 1 0 0 1 -1 1h-4a1 1 0 0 1 -1 -1z"/><path d="M14 14m0 1a1 1 0 0 1 1 -1h4a1 1 0 0 1 1 1v4a1 1 0 0 1 -1 1h-4a1 1 0 0 1 -1 -1z"/></svg><span class="pos-btn-text">@lang('sale.pos_sale')</span></strong>
                    </a>
                @endcan
            @endif
            @can('expense.add')
                <button type="button" title="{{ __('expense.add_expense') }}" data-placement="bottom"
                    class="pos-header-action-btn tw-shadow-[rgba(17,_17,_26,_0.1)_0px_0px_16px] tw-cursor-pointer tw-border tw-w-auto tw-h-auto tw-py-1 tw-px-4 active:tw-scale-95 tw-transition-transform tw-rounded-md btn-modal"
                    id="add_expense">
                    <strong class="tw-inline-flex tw-items-center tw-gap-1.5"><svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-circle-minus" width="18" height="18" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M3 12a9 9 0 1 0 18 0a9 9 0 0 0 -18 0"/><path d="M9 12l6 0"/></svg><span class="pos-btn-text">@lang('expense.add_expense')</span></strong>
                </button>
            @endcan

        </div>
    </div>
</div>

<div class="modal fade" id="service_staff_modal" tabindex="-1" role="dialog"
    aria-labelledby="gridSystemModalLabel">
</div>
