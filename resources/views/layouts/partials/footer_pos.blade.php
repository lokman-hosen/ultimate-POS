<!-- POS Static Bottom Navigation Bar -->
@php
    $enabled_modules = !empty(session('business.enabled_modules')) ? session('business.enabled_modules') : [];
    if (!is_array($enabled_modules)) {
        $enabled_modules = [];
    }
    
    // POS Active check
    $is_pos_active = request()->is('pos/create*') || 
                     request()->is('pos/*/edit*') || 
                     request()->is('pos/payment*') || 
                     request()->is('sells/pos/create*') || 
                     request()->segment(1) == 'pos';
    $is_home_active = request()->is('home') || request()->is('/');
    $is_tables_active = request()->is('modules/tables*');
    $is_kitchen_active = request()->is('modules/kitchen*');
    $is_sells_active = (request()->is('sells*') || request()->is('sells/index')) && !$is_pos_active;
@endphp

<footer class="no-print pos-bottom-nav-container">
    {{-- 1. Home --}}
    <a href="{{ action([\App\Http\Controllers\HomeController::class, 'index']) }}" 
       class="pos-footer-nav-item @if($is_home_active) active @endif" title="@lang('home.home')">
        <div class="pos-footer-nav-inner">
            <svg xmlns="http://www.w3.org/2000/svg" class="pos-footer-nav-icon" width="20" height="20" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                <path d="M5 12l-2 0l9 -9l9 9l-2 0" />
                <path d="M5 12v7a2 2 0 0 0 2 2h10a2 2 0 0 0 2 -2v-7" />
                <path d="M9 21v-6a2 2 0 0 1 2 -2h2a2 2 0 0 1 2 2v6" />
            </svg>
            <span class="pos-footer-nav-label">@lang('home.home')</span>
        </div>
    </a>

    {{-- 2. POS --}}
    <a href="{{ action([\App\Http\Controllers\SellPosController::class, 'create']) }}" 
       class="pos-footer-nav-item pos-footer-nav-item--pos @if($is_pos_active) active @endif" title="@lang('sale.pos_sale')">
        <div class="pos-footer-nav-inner">
            <svg xmlns="http://www.w3.org/2000/svg" class="pos-footer-nav-icon" width="20" height="20" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                <path d="M6 19m-2 0a2 2 0 1 0 4 0a2 2 0 1 0 -4 0" />
                <path d="M17 19m-2 0a2 2 0 1 0 4 0a2 2 0 1 0 -4 0" />
                <path d="M17 17h-11v-14h-2" />
                <path d="M6 5l14 1l-1 7h-13" />
            </svg>
            <span class="pos-footer-nav-label">@lang('sale.pos_sale')</span>
        </div>
    </a>

    {{-- 3. Tables (Restaurant business validated) --}}
    @if(in_array('tables', $enabled_modules) && (auth()->user()->can('access_tables') || auth()->user()->can('superadmin') || auth()->user()->can('admin')))
    <a href="{{ action([\App\Http\Controllers\Restaurant\TableController::class, 'index']) }}" 
       class="pos-footer-nav-item pos-footer-nav-item--tables @if($is_tables_active) active @endif" title="@lang('restaurant.tables')">
        <div class="pos-footer-nav-inner">
            <svg xmlns="http://www.w3.org/2000/svg" class="pos-footer-nav-icon" width="20" height="20" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                <path d="M4 4m0 2a2 2 0 0 1 2 -2h12a2 2 0 0 1 2 2v12a2 2 0 0 1 -2 2h-12a2 2 0 0 1 -2 -2z" />
                <path d="M4 10h16" />
                <path d="M10 4v16" />
            </svg>
            <span class="pos-footer-nav-label">@lang('restaurant.tables')</span>
        </div>
    </a>
    @endif

    {{-- 4. Kitchen (Restaurant kitchen validated) --}}
    @if(in_array('kitchen', $enabled_modules) && (auth()->user()->can('access_kitchen') || auth()->user()->can('superadmin') || auth()->user()->can('admin')))
    <a href="{{ action([\App\Http\Controllers\Restaurant\KitchenController::class, 'index']) }}" 
       class="pos-footer-nav-item @if($is_kitchen_active) active @endif" title="@lang('restaurant.kitchen')">
        <div class="pos-footer-nav-inner">
            <svg xmlns="http://www.w3.org/2000/svg" class="pos-footer-nav-icon" width="20" height="20" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                <path d="M19 3v12h-5c-.023 -3.681 .184 -7.406 5 -12zm0 12v6h-1v-3m-10 -15v6c0 1.66 1.34 3 3 3h1v-9h-4zm0 0v-3m-3 0v3c0 1.66 1.34 3 3 3h1v-9h-4z" />
            </svg>
            <span class="pos-footer-nav-label">@lang('restaurant.kitchen')</span>
        </div>
    </a>
    @endif

    {{-- 5. Sells --}}
    @if(auth()->user()->can('sell.view') || auth()->user()->can('direct_sell.access') || auth()->user()->can('view_own_sell_only') || auth()->user()->can('superadmin') || auth()->user()->can('admin'))
    <a href="{{ action([\App\Http\Controllers\SellController::class, 'index']) }}" 
       class="pos-footer-nav-item @if($is_sells_active) active @endif" title="@lang('sale.sells')">
        <div class="pos-footer-nav-inner">
            <svg xmlns="http://www.w3.org/2000/svg" class="pos-footer-nav-icon" width="20" height="20" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                <path d="M9 5h10l2 2l-2 2h-10a1 1 0 0 1 -1 -1v-2a1 1 0 0 1 1 -1z" />
                <path d="M13 13h7l2 2l-2 2h-7a1 1 0 0 1 -1 -1v-2a1 1 0 0 1 1 -1z" />
                <path d="M5 5m-2 0a2 2 0 1 0 4 0a2 2 0 1 0 -4 0" />
                <path d="M5 15m-2 0a2 2 0 1 0 4 0a2 2 0 1 0 -4 0" />
            </svg>
            <span class="pos-footer-nav-label">@lang('sale.sells')</span>
        </div>
    </a>
    @endif
</footer>

<style>
    .pos-bottom-nav-container {
        position: fixed;
        bottom: 0;
        left: 0;
        right: 0;
        width: 100vw;
        max-width: 100%;
        height: 52px;
        background: linear-gradient(90deg, #033624 0%, #064e3b 25%, #0f766e 55%, #064e3b 80%, #033624 100%);
        border-top: 1px solid rgba(255, 255, 255, 0.12);
        box-shadow: 0 -4px 20px rgba(0, 0, 0, 0.25);
        z-index: 1040;
        display: flex;
        align-items: stretch;
        justify-content: space-evenly;
        padding: 0;
        margin: 0;
    }
    .pos-footer-nav-item {
        flex: 1 1 0;
        display: flex;
        align-items: center;
        justify-content: center;
        text-decoration: none !important;
        color: rgba(255, 255, 255, 0.72) !important;
        transition: all 0.2s cubic-bezier(0.4, 0, 0.2, 1);
        padding: 4px 6px;
        cursor: pointer;
        position: relative;
    }
    .pos-footer-nav-item:hover {
        color: #ffffff !important;
    }
    .pos-footer-nav-item:hover .pos-footer-nav-inner {
        background: rgba(255, 255, 255, 0.08);
        transform: translateY(-1px);
    }
    .pos-footer-nav-inner {
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        gap: 2px;
        padding: 4px 16px;
        border-radius: 12px;
        transition: all 0.2s ease;
        width: auto;
        min-width: 60px;
    }
    .pos-footer-nav-item.active {
        color: #ffffff !important;
    }
    .pos-footer-nav-item.active .pos-footer-nav-inner {
        background: rgba(255, 255, 255, 0.18);
        border: 1px solid rgba(255, 255, 255, 0.25);
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.15);
        padding: 4px 22px;
    }
    .pos-footer-nav-icon {
        width: 19px;
        height: 19px;
        transition: transform 0.2s ease;
    }
    .pos-footer-nav-item.active .pos-footer-nav-icon {
        transform: scale(1.05);
    }
    .pos-footer-nav-label {
        font-size: 11px;
        font-weight: 600;
        letter-spacing: 0.02em;
        line-height: 1.1;
        text-align: center;
        white-space: nowrap;
    }
</style>