<style>
    .pos-header-btn-cat {
        background-color: #4f46e5 !important;
        border-color: #4338ca !important;
        color: white !important;
    }
    .pos-header-btn-cat:hover {
        background-color: #4338ca !important;
        box-shadow: 0 4px 6px -1px rgba(79, 70, 229, 0.3) !important;
    }
    .pos-header-btn-cat svg { color: white !important; }

    .pos-header-btn-brand {
        background-color: #7c3aed !important;
        border-color: #6d28d9 !important;
        color: white !important;
    }
    .pos-header-btn-brand:hover {
        background-color: #6d28d9 !important;
        box-shadow: 0 4px 6px -1px rgba(124, 58, 237, 0.3) !important;
    }
    .pos-header-btn-brand svg { color: white !important; }

    .pos-header-btn-featured {
        background-color: #f59e0b !important;
        border-color: #d97706 !important;
        color: white !important;
    }
    .pos-header-btn-featured:hover {
        background-color: #d97706 !important;
        box-shadow: 0 4px 6px -1px rgba(245, 158, 11, 0.3) !important;
    }
    .pos-header-btn-featured svg { color: white !important; }

    /* Hide scrollbar for category horizontal list while keeping swipe/scrollability */
    #custom_pos_category_list {
        -ms-overflow-style: none;  /* IE and Edge */
        scrollbar-width: none;  /* Firefox */
        user-select: none;
        -webkit-user-select: none;
        cursor: grab;
        scroll-behavior: smooth;
    }
    #custom_pos_category_list.active-dragging {
        cursor: grabbing;
        scroll-behavior: auto;
    }
    #custom_pos_category_list::-webkit-scrollbar {
        display: none;
        width: 0;
        height: 0;
    }

    .custom-category-btn.active-category {
        background-color: #0f172a !important;
        color: white !important;
        border-color: #0f172a !important;
        box-shadow: 0 4px 10px rgba(15, 23, 42, 0.2) !important;
    }
</style>
<div class="pos-sidebar-root tw-shadow-[rgba(17,_17,_26,_0.08)_0px_0px_16px] tw-rounded-2xl tw-bg-white tw-border tw-border-slate-100" style="padding: 6px; overflow: hidden; height: 100%; display: flex; flex-direction: column;">
<div class="tw-flex tw-items-start tw-gap-2 tw-flex-wrap" style="margin: 0 0 6px 0; padding: 0 4px; flex-shrink: 0;">
    @if (!empty($categories))
        <div class="tw-flex-1 tw-min-w-[140px]" id="product_category_div">
            <div class="tw-dw-drawer tw-dw-drawer-end">
                <input id="my-drawer-4{{ $drawer_id_suffix ?? '' }}" type="checkbox" class="tw-dw-drawer-toggle">
                <div class="tw-dw-drawer-content">
                    <!-- Page content here -->
                    <label for="my-drawer-4{{ $drawer_id_suffix ?? '' }}"
                        class="pos-header-btn-cat tw-dw-btn tw-dw-btn-sm tw-group tw-w-full tw-h-9 tw-min-h-[2.25rem] tw-rounded-full tw-flex tw-flex-row tw-items-center tw-flex-nowrap tw-gap-1.5 tw-px-3 tw-text-sm tw-font-semibold tw-normal-case tw-shadow-sm tw-transition-all tw-duration-200 hover:-tw-translate-y-0.5 focus:tw-ring-2 focus:tw-ring-indigo-400 focus:tw-ring-offset-1">
                        <svg xmlns="http://www.w3.org/2000/svg"
                            class="tw-w-4 md:tw-w-5 tw-flex-shrink-0 tw-text-indigo-600 tw-transition-transform tw-duration-200 group-hover:tw-scale-110 icon icon-tabler icon-tabler-category-plus" width="44" height="44"
                            viewBox="0 0 24 24" stroke-width="1.75" stroke="currentColor" fill="none"
                            stroke-linecap="round" stroke-linejoin="round">
                            <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                            <path d="M4 4h6v6h-6zm10 0h6v6h-6zm-10 10h6v6h-6zm10 3h6m-3 -3v6" />
                        </svg>
                        <span class="tw-truncate tw-flex-1 tw-text-left">@lang('category.category')</span>
                        {{-- Active filter badge: shown by JS when a category is selected --}}
                        <span class="pos-active-filter-badge pos-filter-badge-cat tw-dw-badge tw-dw-badge-md tw-bg-indigo-50 tw-border tw-border-indigo-200 tw-text-indigo-700 tw-gap-1 tw-max-w-[110px] tw-flex-shrink-0 tw-font-semibold tw-text-[12px] tw-px-2">
                            <span class="pos-active-filter-name tw-truncate"></span>
                            <button type="button" class="pos-filter-chip-clear tw-text-indigo-400" data-clear="category" aria-label="Clear">
                                <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3.5" stroke-linecap="round" stroke-linejoin="round"><path d="M18 6 6 18"/><path d="m6 6 12 12"/></svg>
                            </button>
                        </span>
                        {{-- Count badge: hidden when filter is active --}}
                        <span class="pos-count-badge tw-dw-badge tw-dw-badge-sm tw-bg-indigo-50 tw-border-indigo-100 tw-text-indigo-700 tw-font-bold tw-text-[11px] tw-flex-shrink-0 group-hover:tw-bg-white group-hover:tw-border-indigo-200 tw-transition-colors">{{ count($categories) }}</span>
                    </label>
                </div>
                <div class="tw-dw-drawer-side" style="z-index: 4000">
                    <label for="my-drawer-4{{ $drawer_id_suffix ?? '' }}" aria-label="close sidebar"
                        class="tw-dw-drawer-overlay overlay-category"></label>
                    <div class="tw-dw-menu pos-drawer-panel pos-drawer-category tw-min-h-full tw-bg-white">

                        {{-- Header: title + count + close --}}
                        <div class="tw-flex tw-items-start tw-justify-between tw-gap-3 tw-px-6 tw-pt-[22px] tw-pb-4 tw-border-b tw-border-slate-100">
                            <div class="tw-flex-1 tw-min-w-0">
                                <h3 class="tw-text-xl tw-font-bold tw-text-slate-900 tw-m-0 tw-leading-tight tw-tracking-tight">@lang('category.category')</h3>
                                <div class="tw-font-mono tw-text-[11px] tw-tracking-[0.08em] tw-uppercase tw-text-slate-500 tw-mt-1.5"><span>{{ count($categories) }}</span> @lang('category.category')</div>
                            </div>
                            <button type="button" class="tw-w-9 tw-h-9 tw-rounded-[10px] tw-bg-slate-100 tw-text-slate-500 tw-border-0 tw-cursor-pointer tw-inline-flex tw-items-center tw-justify-center tw-transition-all tw-duration-[160ms] tw-flex-shrink-0 hover:tw-bg-slate-200 hover:tw-text-slate-900 focus-visible:tw-outline focus-visible:tw-outline-2 focus-visible:tw-outline-indigo-500 focus-visible:tw-outline-offset-2 close-side-bar-category" aria-label="Close">
                                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.25" stroke-linecap="round" stroke-linejoin="round"><path d="M18 6 6 18"/><path d="m6 6 12 12"/></svg>
                            </button>
                        </div>

                        {{-- Body: card grid --}}
                        <div class="pos-drawer-body tw-px-6 tw-pt-[18px] tw-pb-6 tw-overflow-y-auto tw-flex-1">
                            <div class="row pos-card-grid pos-card-grid-categories" data-label-all="@lang('messages.all')" style="margin-right: 0; margin-left: -8px;">
                                <div class="col-md-4 col-xs-6 tw-mb-3 tw-cursor-pointer main-category-div main-category no-print"
                                    data-value="all" data-parent="0" style="padding-left: 4px; padding-right: 4px;">
                                    <div class="pos-card cat">
                                        <h4 class="pos-card-name">@lang('lang_v1.all_category')</h4>
                                    </div>
                                </div>
                                @foreach ($categories as $category)
                                    <div class="col-md-4 col-xs-6 tw-mb-3 tw-cursor-pointer main-category-div main-category no-print"
                                        data-value="{{ $category['id'] }}" data-name="{{ $category['name'] }}" data-parent="0"
                                        style="padding-left: 4px; padding-right: 4px;">
                                        <div class="pos-card cat">
                                            <h4 class="pos-card-name">{{ $category['name'] }}</h4>
                                            @if (!empty($category['sub_categories']))
                                                <span class="pos-card-subcat-dot"></span>
                                            @endif
                                        </div>
                                    </div>
                                @endforeach
                                @foreach ($categories as $category)
                                    @if (!empty($category['sub_categories']))
                                        <div class="all-sub-category" data-category-id="{{ $category['id'] }}" style="display: none">
                                            @foreach ($category['sub_categories'] as $sc)
                                                @if ($sc['parent_id'] != 0)
                                                    <div class="col-md-4 col-xs-6 tw-mb-3 tw-cursor-pointer product_category no-print"
                                                        data-value="{{ $sc['id'] }}" data-name="{{ $sc['name'] }}"
                                                        style="padding-left: 4px; padding-right: 4px;">
                                                        <div class="pos-card cat">
                                                            <h4 class="pos-card-name">{{ $sc['name'] }}</h4>
                                                        </div>
                                                    </div>
                                                @endif
                                            @endforeach
                                        </div>
                                    @endif
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif

    @if (!empty($brands))
        <div class="tw-flex-1 tw-min-w-[140px]" id="product_brand_div">
            <div class="tw-dw-drawer tw-dw-drawer-end">
                <input id="my-drawer-brand{{ $drawer_id_suffix ?? '' }}" type="checkbox" class="tw-dw-drawer-toggle">
                <div class="tw-dw-drawer-content">
                    <!-- Page content here -->
                    <label for="my-drawer-brand{{ $drawer_id_suffix ?? '' }}"
                        class="pos-header-btn-brand tw-dw-btn tw-dw-btn-sm tw-group tw-w-full tw-h-9 tw-min-h-[2.25rem] tw-rounded-full tw-flex tw-flex-row tw-items-center tw-flex-nowrap tw-gap-1.5 tw-px-3 tw-text-sm tw-font-semibold tw-normal-case tw-shadow-sm tw-transition-all tw-duration-200 hover:-tw-translate-y-0.5 focus:tw-ring-2 focus:tw-ring-violet-400 focus:tw-ring-offset-1">
                        <svg xmlns="http://www.w3.org/2000/svg" class="tw-w-4 md:tw-w-5 tw-flex-shrink-0 tw-text-violet-600 tw-transition-transform tw-duration-200 group-hover:tw-scale-110 icon icon-tabler icon-tabler-brand-beats"
                            width="44" height="44" viewBox="0 0 24 24" stroke-width="1.75" stroke="currentColor"
                            fill="none" stroke-linecap="round" stroke-linejoin="round">
                            <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                            <path d="M12 12m-9 0a9 9 0 1 0 18 0a9 9 0 1 0 -18 0" />
                            <path d="M12.5 12.5m-3.5 0a3.5 3.5 0 1 0 7 0a3.5 3.5 0 1 0 -7 0" />
                            <path d="M9 12v-8" />
                        </svg>
                        <span class="tw-truncate tw-flex-1 tw-text-left">@lang('brand.brands')</span>
                        {{-- Active filter badge: shown by JS when a brand is selected --}}
                        <span class="pos-active-filter-badge pos-filter-badge-brand tw-dw-badge tw-dw-badge-md tw-bg-violet-50 tw-border tw-border-violet-200 tw-text-violet-700 tw-gap-1 tw-max-w-[110px] tw-flex-shrink-0 tw-font-semibold tw-text-[12px] tw-px-2">
                            <span class="pos-active-filter-name tw-truncate"></span>
                            <button type="button" class="pos-filter-chip-clear tw-text-violet-400" data-clear="brand" aria-label="Clear">
                                <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3.5" stroke-linecap="round" stroke-linejoin="round"><path d="M18 6 6 18"/><path d="m6 6 12 12"/></svg>
                            </button>
                        </span>
                        {{-- Count badge: hidden when filter is active --}}
                        <span class="pos-count-badge tw-dw-badge tw-dw-badge-sm tw-bg-violet-50 tw-border-violet-100 tw-text-violet-700 tw-font-bold tw-text-[11px] tw-flex-shrink-0 group-hover:tw-bg-white group-hover:tw-border-violet-200 tw-transition-colors">{{ count($brands) }}</span>
                    </label>
                </div>
                <div class="tw-dw-drawer-side" style="z-index: 4000">
                    <label for="my-drawer-brand{{ $drawer_id_suffix ?? '' }}" aria-label="close sidebar"
                        class="tw-dw-drawer-overlay overlay-brand"></label>
                    <div class="tw-dw-menu pos-drawer-panel pos-drawer-brand tw-min-h-full tw-bg-white">

                        <div class="tw-flex tw-items-start tw-justify-between tw-gap-3 tw-px-6 tw-pt-[22px] tw-pb-4 tw-border-b tw-border-slate-100">
                            <div class="tw-flex-1 tw-min-w-0">
                                <h3 class="tw-text-xl tw-font-bold tw-text-slate-900 tw-m-0 tw-leading-tight tw-tracking-tight">@lang('brand.brands')</h3>
                                <div class="tw-font-mono tw-text-[11px] tw-tracking-[0.08em] tw-uppercase tw-text-slate-500 tw-mt-1.5"><span>{{ count($brands) }}</span> @lang('brand.brands')</div>
                            </div>
                            <button type="button" class="tw-w-9 tw-h-9 tw-rounded-[10px] tw-bg-slate-100 tw-text-slate-500 tw-border-0 tw-cursor-pointer tw-inline-flex tw-items-center tw-justify-center tw-transition-all tw-duration-[160ms] tw-flex-shrink-0 hover:tw-bg-slate-200 hover:tw-text-slate-900 focus-visible:tw-outline focus-visible:tw-outline-2 focus-visible:tw-outline-violet-500 focus-visible:tw-outline-offset-2 close-side-bar-brand" aria-label="Close">
                                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.25" stroke-linecap="round" stroke-linejoin="round"><path d="M18 6 6 18"/><path d="m6 6 12 12"/></svg>
                            </button>
                        </div>

                        <div class="pos-drawer-body tw-px-6 tw-pt-[18px] tw-pb-6 tw-overflow-y-auto tw-flex-1">
                            <div class="row pos-card-grid pos-card-grid-brands" style="margin-right: 0; margin-left: -8px;">
                                @foreach ($brands as $key => $brand)
                                    <div class="col-md-4 col-xs-6 tw-mb-3 tw-cursor-pointer product_brand no-print"
                                        data-value="{{ $key }}" data-name="{{ $brand }}"
                                        style="padding-left: 4px; padding-right: 4px;">
                                        <div class="pos-card brand">
                                            <h4 class="pos-card-name">{{ $brand }}</h4>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif

    <!-- used in repair : filter for service/product -->
    <div class="col-md-6 hide" id="product_service_div">
        {!! Form::select(
            'is_enabled_stock',
            ['' => __('messages.all'), 'product' => __('sale.product'), 'service' => __('lang_v1.service')],
            null,
            ['id' => 'is_enabled_stock', 'class' => 'select2', 'name' => null, 'style' => 'width:100% !important'],
        ) !!}
    </div>

    <div class="tw-flex-1 tw-min-w-[140px]" id="feature_product_div">
        <button type="button" id="show_featured_products"
            class="pos-header-btn-featured tw-dw-btn tw-dw-btn-sm tw-group tw-w-full tw-h-9 tw-min-h-[2.25rem] tw-rounded-full tw-flex-nowrap tw-gap-2 tw-px-3 tw-text-sm tw-font-semibold tw-normal-case tw-shadow-sm tw-transition-all tw-duration-200 hover:-tw-translate-y-0.5 focus:tw-ring-2 focus:tw-ring-amber-400 focus:tw-ring-offset-1">
            <svg xmlns="http://www.w3.org/2000/svg"
                class="tw-w-4 md:tw-w-5 tw-flex-shrink-0 tw-text-amber-500 tw-transition-transform tw-duration-200 group-hover:tw-scale-110 group-hover:tw-rotate-12 icon icon-tabler icon-tabler-star" width="44" height="44"
                viewBox="0 0 24 24" stroke-width="1.75" stroke="currentColor" fill="none"
                stroke-linecap="round" stroke-linejoin="round">
                <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                <path d="M12 17.75l-6.172 3.245 1.179 -6.873 -5 -4.867 6.9 -1 3.086 -6.253 3.086 6.253 6.9 1 -5 4.867 1.179 6.873z" />
            </svg>
            <span class="tw-truncate">@lang('lang_v1.featured_products')</span>
            @php $featured_products_count = !empty($featured_products) ? count($featured_products) : 0; @endphp
            {{-- Badge is always rendered (hidden when count is 0) so JS can refresh it on location change --}}
            <span id="featured_products_count" class="tw-dw-badge tw-dw-badge-sm tw-bg-amber-50 tw-border-amber-100 tw-text-amber-700 tw-font-bold tw-text-[11px] group-hover:tw-bg-white group-hover:tw-border-amber-200 tw-transition-colors @if ($featured_products_count == 0) tw-hidden @endif">{{ $featured_products_count }}</span>
        </button>
    </div>
</div>

{{-- Horizontal Category Pills Filter (Swipeable) --}}
@if (!empty($categories))
    <div id="custom_pos_category_list" class="tw-flex tw-gap-2 tw-mb-2 tw-overflow-x-auto tw-pb-1 tw-px-1" style="flex-shrink: 0;">
        <button type="button" class="custom-category-btn active-category tw-px-4 tw-py-1.5 tw-rounded-full tw-border tw-border-slate-200 tw-bg-white tw-text-slate-700 tw-text-sm tw-font-semibold tw-whitespace-nowrap tw-shrink-0 tw-cursor-pointer tw-transition-all hover:tw-bg-slate-50 hover:tw-border-slate-300 hover:tw-text-slate-900 active:tw-scale-95" data-val="all">
            @lang('lang_v1.all')
        </button>
        @foreach ($categories as $category)
            <button type="button" class="custom-category-btn tw-px-4 tw-py-1.5 tw-rounded-full tw-border tw-border-slate-200 tw-bg-white tw-text-slate-700 tw-text-sm tw-font-semibold tw-whitespace-nowrap tw-shrink-0 tw-cursor-pointer tw-transition-all hover:tw-bg-slate-50 hover:tw-border-slate-300 hover:tw-text-slate-900 active:tw-scale-95" data-val="{{ $category['id'] }}" data-name="{{ $category['name'] }}">
                {{ $category['name'] }}
            </button>
        @endforeach
    </div>
@endif

<div class="row" style="margin: 0; flex: 1 1 0; min-height: 0; display: flex; flex-direction: column; overflow: hidden;">
    <input type="hidden" id="suggestion_page" value="1">
    <div class="col-md-12" style="padding: 0; flex: 1 1 0; min-height: 0; display: flex; flex-direction: column; height: 100%;">
        <div id="product_list_body" class="eq-height-row tw-overflow-y-auto tw-overflow-x-hidden" style="padding-right: 4px; flex: 1 1 0; height: 100%; max-height: 100%;">
            <div id="featured_products_box" style="display: none;">
                @if (!empty($featured_products))
                    @include('sale_pos.partials.featured_products')
                @endif
            </div>
            {{-- Hidden template: source of truth for the empty-state markup. JS injects this into #featured_products_box when there are no featured products to display (page load OR after filter). --}}
            <div id="featured_empty_state_template" style="display: none;">
                <div class="tw-w-full tw-flex tw-flex-wrap tw-items-center tw-justify-center tw-gap-x-2 tw-gap-y-1 tw-py-3 tw-px-3 tw-text-amber-800 tw-text-xs md:tw-text-sm">
                    <svg xmlns="http://www.w3.org/2000/svg" class="tw-w-4 md:tw-w-5 tw-flex-shrink-0" viewBox="0 0 24 24" stroke-width="1.75" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                        <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                        <path d="M12 12m-9 0a9 9 0 1 0 18 0a9 9 0 1 0 -18 0" />
                        <path d="M12 8h.01" />
                        <path d="M11 12h1v4h1" />
                    </svg>
                    <span>@lang('lang_v1.featured_products_empty_msg')</span>
                    <a href="{{ url('business-location') }}" target="_blank" class="tw-inline-flex tw-items-center tw-gap-1 tw-font-semibold tw-text-amber-900 tw-underline tw-decoration-amber-400 tw-underline-offset-2 hover:tw-text-amber-950 hover:tw-decoration-amber-700 tw-transition-colors">
                        @lang('business.business_location')
                        <svg xmlns="http://www.w3.org/2000/svg" class="tw-w-3 md:tw-w-4 tw-flex-shrink-0" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                            <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                            <path d="M12 6h-6a2 2 0 0 0 -2 2v10a2 2 0 0 0 2 2h10a2 2 0 0 0 2 -2v-6" />
                            <path d="M11 13l9 -9" />
                            <path d="M15 4h5v5" />
                        </svg>
                    </a>
                </div>
            </div>
            <div id="product_list_items" class="tw-w-full"></div>
        </div>
    </div>
    <div class="col-md-12 text-center" id="suggestion_page_loader" style="display: none;">
        <i class="fa fa-spinner fa-spin fa-2x"></i>
    </div>
</div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const catSlider = document.getElementById('custom_pos_category_list');
        if (!catSlider) return;

        // Drag / swipe to scroll
        let isDown = false;
        let startX;
        let scrollLeft;
        let hasMoved = false;

        catSlider.addEventListener('mousedown', (e) => {
            isDown = true;
            hasMoved = false;
            catSlider.classList.add('active-dragging');
            startX = e.pageX - catSlider.offsetLeft;
            scrollLeft = catSlider.scrollLeft;
        });

        catSlider.addEventListener('mouseleave', () => {
            isDown = false;
            catSlider.classList.remove('active-dragging');
        });

        catSlider.addEventListener('mouseup', () => {
            isDown = false;
            catSlider.classList.remove('active-dragging');
        });

        catSlider.addEventListener('mousemove', (e) => {
            if (!isDown) return;
            e.preventDefault();
            const x = e.pageX - catSlider.offsetLeft;
            const walk = (x - startX) * 1.5;
            if (Math.abs(walk) > 4) {
                hasMoved = true;
            }
            catSlider.scrollLeft = scrollLeft - walk;
        });

        // Wheel horizontal scroll
        catSlider.addEventListener('wheel', (e) => {
            if (e.deltaY !== 0) {
                e.preventDefault();
                catSlider.scrollLeft += e.deltaY;
            }
        }, { passive: false });

        // Category button click handling
        const catBtns = catSlider.querySelectorAll('.custom-category-btn');
        catBtns.forEach(btn => {
            btn.addEventListener('click', function(e) {
                if (hasMoved) {
                    e.stopImmediatePropagation();
                    e.preventDefault();
                    hasMoved = false;
                    return;
                }

                const val = this.getAttribute('data-val');
                const name = this.getAttribute('data-name');
                const isAlreadyActive = this.classList.contains('active-category');

                if (val === 'all' || isAlreadyActive) {
                    // Activate 'All' and reset category filter
                    catBtns.forEach(b => b.classList.remove('active-category'));
                    const allBtn = catSlider.querySelector('.custom-category-btn[data-val="all"]');
                    if (allBtn) allBtn.classList.add('active-category');

                    global_p_category_id = null;
                    if (typeof pos_set_filter_chip === 'function') {
                        pos_set_filter_chip('category', null);
                    }
                    var $grid = $('.pos-card-grid-categories');
                    if ($grid.length) {
                        $grid.find('.pos-subcat-strip').remove();
                        $grid.removeData('open-cat');
                        $grid.find('.pos-card.cat').removeClass('is-active');
                    }
                    $('input#suggestion_page').val(1);
                    get_product_suggestion_list(null, global_brand_id, $('input#location_id').val(), null);
                    if (typeof get_featured_products === 'function') {
                        get_featured_products();
                    }
                } else {
                    // Select specific category
                    catBtns.forEach(b => b.classList.remove('active-category'));
                    this.classList.add('active-category');

                    global_p_category_id = val;
                    if (typeof pos_set_filter_chip === 'function') {
                        pos_set_filter_chip('category', name);
                    }
                    $('input#suggestion_page').val(1);
                    get_product_suggestion_list(global_p_category_id, global_brand_id, $('input#location_id').val(), null);
                    if (typeof get_featured_products === 'function') {
                        get_featured_products();
                    }
                }
            });
        });

        // Sync horizontal category buttons when filtered from drawer or filter chip clear
        $(document).on('click', '.main-category, .product_category, .pos-subcat-pill, .pos-filter-chip-clear', function() {
            setTimeout(function() {
                catBtns.forEach(b => b.classList.remove('active-category'));
                if (typeof global_p_category_id !== 'undefined' && global_p_category_id) {
                    const matchBtn = catSlider.querySelector('.custom-category-btn[data-val="' + global_p_category_id + '"]');
                    if (matchBtn) {
                        matchBtn.classList.add('active-category');
                    }
                } else {
                    const allBtn = catSlider.querySelector('.custom-category-btn[data-val="all"]');
                    if (allBtn) allBtn.classList.add('active-category');
                }
            }, 60);
        });

        // Sync on form reset
        $(document).on('sell_form_reset', function() {
            catBtns.forEach(b => b.classList.remove('active-category'));
            const allBtn = catSlider.querySelector('.custom-category-btn[data-val="all"]');
            if (allBtn) allBtn.classList.add('active-category');
        });
    });
</script>
