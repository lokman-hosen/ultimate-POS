@extends('layouts.app')
@section('title', __('barcode.print_labels'))

@section('css')
<style>
    .product-section-card {
        background: #ffffff;
        border-radius: 14px;
        border: 1px solid #e2e8f0;
        box-shadow: 0 1px 3px rgba(15, 23, 42, 0.04), 0 1px 2px rgba(15, 23, 42, 0.02);
        padding: 22px 24px;
        margin-bottom: 24px;
        transition: all 0.2s ease;
    }
    .product-section-card:hover {
        box-shadow: 0 4px 12px rgba(15, 23, 42, 0.06);
    }
    .product-section-header {
        display: flex;
        align-items: center;
        gap: 12px;
        margin-bottom: 20px;
        padding-bottom: 14px;
        border-bottom: 1px solid #f1f5f9;
    }
    .product-section-badge {
        width: 32px;
        height: 32px;
        border-radius: 50%;
        background: #2563eb;
        color: #ffffff;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: 700;
        font-size: 14px;
        flex-shrink: 0;
        box-shadow: 0 2px 4px rgba(37, 99, 235, 0.25);
    }
    .product-section-title {
        margin: 0;
        font-size: 16px;
        font-weight: 700;
        color: #0f172a;
        line-height: 1.2;
    }
    .product-section-subtitle {
        margin: 3px 0 0 0;
        font-size: 12px;
        color: #64748b;
    }
    .label-info-card {
        background: #f8fafc;
        border: 1px solid #e2e8f0;
        border-radius: 12px;
        padding: 14px 16px;
        transition: all 0.2s ease;
        display: flex;
        flex-direction: column;
        justify-content: space-between;
        height: 100%;
    }
    .label-info-card:hover {
        background: #ffffff;
        border-color: #cbd5e1;
        box-shadow: 0 2px 6px rgba(0, 0, 0, 0.04);
    }
    .label-info-card.active-card {
        background: #f0fdf4;
        border-color: #bbf7d0;
    }
    .barcode-preview-container {
        background: #f8fafc;
        border: 1px solid #e2e8f0;
        border-radius: 12px;
        padding: 16px;
    }
    .mini-sheet-paper {
        background: #ffffff;
        border: 1px solid #cbd5e1;
        box-shadow: 0 2px 8px rgba(0,0,0,0.08);
        border-radius: 6px;
        margin: 0 auto;
        padding: 8px;
        position: relative;
        max-width: 220px;
        min-height: 180px;
        display: flex;
        flex-direction: column;
        justify-content: center;
    }
    .mini-sheet-grid {
        display: grid;
        gap: 3px;
        width: 100%;
        height: 100%;
        align-content: center;
    }
    .mini-sticker-cell {
        background: #eff6ff;
        border: 1px dashed #93c5fd;
        border-radius: 2px;
        height: 14px;
        transition: all 0.15s ease;
    }
    .mini-sticker-cell:hover {
        background: #3b82f6;
    }
    .zoom-sticker-card {
        background: #ffffff;
        border: 1px solid #cbd5e1;
        border-radius: 8px;
        box-shadow: 0 4px 10px rgba(0, 0, 0, 0.05);
        padding: 12px 14px;
        text-align: center;
        max-width: 260px;
        margin: 0 auto;
    }
    .barcode-lines-mock {
        display: flex;
        justify-content: center;
        align-items: flex-end;
        height: 30px;
        gap: 2px;
        margin: 6px 0 2px 0;
    }
    .barcode-line {
        background: #0f172a;
        height: 100%;
    }
    #product_table {
        border-collapse: separate;
        border-spacing: 0;
        width: 100% !important;
    }
    #product_table thead th {
        background: #f8fafc;
        color: #475569;
        font-weight: 700;
        font-size: 12px;
        text-transform: uppercase;
        letter-spacing: 0.04em;
        padding: 12px 14px;
        border-bottom: 2px solid #e2e8f0;
        vertical-align: middle !important;
    }
    #product_table tbody td {
        padding: 12px 14px;
        vertical-align: middle !important;
        border-bottom: 1px solid #f1f5f9;
        background: #ffffff;
    }
    #product_table tbody tr:hover td {
        background-color: #fafbfd;
    }
    #product_table .form-control {
        border: 1px solid #cbd5e1;
        box-shadow: none;
        transition: border-color 0.2s ease, box-shadow 0.2s ease;
    }
    #product_table .form-control:focus {
        border-color: #3b82f6;
        box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.12);
    }
</style>
@endsection

@section('content')

<!-- Content Header (Page header) -->
<section class="content-header tw-mb-4">
    <div class="tw-flex tw-flex-col sm:tw-flex-row sm:tw-items-center sm:tw-justify-between tw-gap-2">
        <div>
            <h1 class="tw-text-xl md:tw-text-3xl tw-font-bold tw-text-gray-900 tw-flex tw-items-center tw-gap-2">
                <i class="fa fa-barcode tw-text-blue-600"></i>
                @lang('barcode.print_labels') @show_tooltip(__('tooltip.print_label'))
            </h1>
            <p class="tw-text-sm tw-text-gray-500 tw-mt-1">
                @lang('lang_v1.print_labels_header_subtitle')
            </p>
        </div>
    </div>
</section>

<!-- Main content -->
<section class="content no-print">
	{!! Form::open(['url' => '#', 'method' => 'post', 'id' => 'preview_setting_form', 'onsubmit' => 'return false']) !!}

    <!-- 1. Add Products Section -->
    <div class="product-section-card">
        <div class="product-section-header">
            <div class="product-section-badge">1</div>
            <div>
                <h3 class="product-section-title">@lang('product.add_product_for_labels')</h3>
                <p class="product-section-subtitle">@lang('lang_v1.enter_product_name_to_print_labels')</p>
            </div>
        </div>

        <div class="row">
            <div class="col-md-8 col-md-offset-2 col-sm-12 tw-mb-4">
                <div class="form-group tw-mb-0">
                    <div class="input-group tw-shadow-sm" style="width: 100%;">
                        <span class="input-group-addon" style="background: #f8fafc; border-color: #cbd5e1; font-size: 16px; color: #3b82f6;">
                            <i class="fa fa-search"></i>
                        </span>
                        {!! Form::text('search_product', null, [
                            'class' => 'form-control',
                            'id' => 'search_product_for_label',
                            'placeholder' => __('lang_v1.enter_product_name_to_print_labels'),
                            'autofocus',
                            'style' => 'height: 44px; font-size: 14px; border-color: #cbd5e1;'
                        ]); !!}
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-sm-12">
                <div class="table-responsive tw-rounded-xl tw-border tw-border-slate-200">
                    <table class="table table-bordered table-striped table-hover tw-mb-0" id="product_table">
                        <thead class="tw-bg-slate-50">
                            <tr>
                                <th class="tw-text-xs tw-font-bold tw-text-slate-700 tw-uppercase tw-py-3" style="width: 30%;">@lang( 'barcode.products' )</th>
                                <th class="tw-text-xs tw-font-bold tw-text-slate-700 tw-uppercase tw-py-3" style="width: 14%; min-width: 110px;">@lang( 'barcode.no_of_labels' )</th>
                                @if(request()->session()->get('business.enable_lot_number') == 1)
                                    <th class="tw-text-xs tw-font-bold tw-text-slate-700 tw-uppercase tw-py-3" style="width: 14%; min-width: 120px;">@lang( 'lang_v1.lot_number' )</th>
                                @endif
                                @if(request()->session()->get('business.enable_product_expiry') == 1)
                                    <th class="tw-text-xs tw-font-bold tw-text-slate-700 tw-uppercase tw-py-3" style="width: 15%; min-width: 130px;">@lang( 'product.exp_date' )</th>
                                @endif
                                <th class="tw-text-xs tw-font-bold tw-text-slate-700 tw-uppercase tw-py-3" style="width: 18%; min-width: 150px;">@lang('lang_v1.packing_date')</th>
                                <th class="tw-text-xs tw-font-bold tw-text-slate-700 tw-uppercase tw-py-3" style="width: 18%; min-width: 160px;">@lang('lang_v1.selling_price_group')</th>
                                <th class="tw-text-xs tw-font-bold tw-text-slate-700 tw-uppercase tw-py-3" style="width: 50px; text-align: center;"><i class="fa fa-trash"></i></th>
                            </tr>
                        </thead>
                        <tbody>
                            @include('labels.partials.show_table_rows', ['index' => 0])
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- 2. Information in Labels Section -->
    <div class="product-section-card">
        <div class="product-section-header">
            <div class="product-section-badge">2</div>
            <div>
                <h3 class="product-section-title">@lang( 'barcode.info_in_labels' )</h3>
                <p class="product-section-subtitle">@lang('lang_v1.select_fields_to_display_font_sizes')</p>
            </div>
        </div>

        <div class="row tw-gap-y-4">
            <!-- Product Name -->
            <div class="col-md-3 col-sm-6 tw-mb-3">
                <div class="label-info-card">
                    <div class="checkbox tw-m-0 tw-mb-2">
                        <label class="tw-font-bold tw-text-gray-800 tw-text-sm tw-flex tw-items-center tw-gap-2">
                            <input type="checkbox" checked name="print[name]" value="1" class="preview-trigger">
                            <span>@lang( 'barcode.print_name' )</span>
                        </label>
                    </div>
                    <div class="input-group input-group-sm">
                        <span class="input-group-addon tw-bg-slate-100 tw-text-gray-600 tw-font-semibold tw-text-xs">@lang( 'lang_v1.size' ) (px)</span>
                        <input type="number" class="form-control preview-trigger" name="print[name_size]" value="15" min="6" max="36">
                    </div>
                </div>
            </div>

            <!-- Variations -->
            <div class="col-md-3 col-sm-6 tw-mb-3">
                <div class="label-info-card">
                    <div class="checkbox tw-m-0 tw-mb-2">
                        <label class="tw-font-bold tw-text-gray-800 tw-text-sm tw-flex tw-items-center tw-gap-2">
                            <input type="checkbox" checked name="print[variations]" value="1" class="preview-trigger">
                            <span>@lang( 'barcode.print_variations' )</span>
                        </label>
                    </div>
                    <div class="input-group input-group-sm">
                        <span class="input-group-addon tw-bg-slate-100 tw-text-gray-600 tw-font-semibold tw-text-xs">@lang( 'lang_v1.size' ) (px)</span>
                        <input type="number" class="form-control preview-trigger" name="print[variations_size]" value="17" min="6" max="36">
                    </div>
                </div>
            </div>

            <!-- Product Price & Type -->
            <div class="col-md-3 col-sm-6 tw-mb-3">
                <div class="label-info-card">
                    <div class="checkbox tw-m-0 tw-mb-2">
                        <label class="tw-font-bold tw-text-gray-800 tw-text-sm tw-flex tw-items-center tw-gap-2">
                            <input type="checkbox" checked name="print[price]" value="1" id="is_show_price" class="preview-trigger">
                            <span>@lang( 'barcode.print_price' )</span>
                        </label>
                    </div>
                    <div class="input-group input-group-sm tw-mb-2">
                        <span class="input-group-addon tw-bg-slate-100 tw-text-gray-600 tw-font-semibold tw-text-xs">@lang( 'lang_v1.size' ) (px)</span>
                        <input type="number" class="form-control preview-trigger" name="print[price_size]" value="17" min="6" max="36">
                    </div>
                    <div id="price_type_div" class="tw-mt-1">
                        <div class="input-group input-group-sm">
                            <span class="input-group-addon tw-bg-slate-100 tw-text-gray-600"><i class="fa fa-info"></i></span>
                            {!! Form::select('print[price_type]', ['inclusive' => __('product.inc_of_tax'), 'exclusive' => __('product.exc_of_tax')], 'inclusive', ['class' => 'form-control preview-trigger']); !!}
                        </div>
                    </div>
                </div>
            </div>

            <!-- Business Name -->
            <div class="col-md-3 col-sm-6 tw-mb-3">
                <div class="label-info-card">
                    <div class="checkbox tw-m-0 tw-mb-2">
                        <label class="tw-font-bold tw-text-gray-800 tw-text-sm tw-flex tw-items-center tw-gap-2">
                            <input type="checkbox" checked name="print[business_name]" value="1" class="preview-trigger">
                            <span>@lang( 'barcode.print_business_name' )</span>
                        </label>
                    </div>
                    <div class="input-group input-group-sm">
                        <span class="input-group-addon tw-bg-slate-100 tw-text-gray-600 tw-font-semibold tw-text-xs">@lang( 'lang_v1.size' ) (px)</span>
                        <input type="number" class="form-control preview-trigger" name="print[business_name_size]" value="20" min="6" max="36">
                    </div>
                </div>
            </div>

            <!-- Packing Date -->
            <div class="col-md-3 col-sm-6 tw-mb-3">
                <div class="label-info-card">
                    <div class="checkbox tw-m-0 tw-mb-2">
                        <label class="tw-font-bold tw-text-gray-800 tw-text-sm tw-flex tw-items-center tw-gap-2">
                            <input type="checkbox" checked name="print[packing_date]" value="1" class="preview-trigger">
                            <span>@lang( 'lang_v1.print_packing_date' )</span>
                        </label>
                    </div>
                    <div class="input-group input-group-sm">
                        <span class="input-group-addon tw-bg-slate-100 tw-text-gray-600 tw-font-semibold tw-text-xs">@lang( 'lang_v1.size' ) (px)</span>
                        <input type="number" class="form-control preview-trigger" name="print[packing_date_size]" value="12" min="6" max="36">
                    </div>
                </div>
            </div>

            <!-- Lot Number (if enabled) -->
            @if(request()->session()->get('business.enable_lot_number') == 1)
                <div class="col-md-3 col-sm-6 tw-mb-3">
                    <div class="label-info-card">
                        <div class="checkbox tw-m-0 tw-mb-2">
                            <label class="tw-font-bold tw-text-gray-800 tw-text-sm tw-flex tw-items-center tw-gap-2">
                                <input type="checkbox" checked name="print[lot_number]" value="1" class="preview-trigger">
                                <span>@lang( 'lang_v1.print_lot_number' )</span>
                            </label>
                        </div>
                        <div class="input-group input-group-sm">
                            <span class="input-group-addon tw-bg-slate-100 tw-text-gray-600 tw-font-semibold tw-text-xs">@lang( 'lang_v1.size' ) (px)</span>
                            <input type="number" class="form-control preview-trigger" name="print[lot_number_size]" value="12" min="6" max="36">
                        </div>
                    </div>
                </div>
            @endif

            <!-- Exp Date (if enabled) -->
            @if(request()->session()->get('business.enable_product_expiry') == 1)
                <div class="col-md-3 col-sm-6 tw-mb-3">
                    <div class="label-info-card">
                        <div class="checkbox tw-m-0 tw-mb-2">
                            <label class="tw-font-bold tw-text-gray-800 tw-text-sm tw-flex tw-items-center tw-gap-2">
                                <input type="checkbox" checked name="print[exp_date]" value="1" class="preview-trigger">
                                <span>@lang( 'lang_v1.print_exp_date' )</span>
                            </label>
                        </div>
                        <div class="input-group input-group-sm">
                            <span class="input-group-addon tw-bg-slate-100 tw-text-gray-600 tw-font-semibold tw-text-xs">@lang( 'lang_v1.size' ) (px)</span>
                            <input type="number" class="form-control preview-trigger" name="print[exp_date_size]" value="12" min="6" max="36">
                        </div>
                    </div>
                </div>
            @endif

            <!-- Custom Fields -->
            @php
                $custom_labels = json_decode(session('business.custom_labels'), true);
                $product_custom_fields = !empty($custom_labels['product']) ? $custom_labels['product'] : [];
                $product_cf_details = !empty($custom_labels['product_cf_details']) ? $custom_labels['product_cf_details'] : [];
            @endphp
            @foreach($product_custom_fields as $index => $cf)
                @if(!empty($cf))
                    @php
                        $field_name = 'product_custom_field' . $loop->iteration;
                    @endphp
                    <div class="col-md-3 col-sm-6 tw-mb-3">
                        <div class="label-info-card">
                            <div class="checkbox tw-m-0 tw-mb-2">
                                <label class="tw-font-bold tw-text-gray-800 tw-text-sm tw-flex tw-items-center tw-gap-2">
                                    <input type="checkbox" name="print[{{ $field_name }}]" value="1" class="preview-trigger">
                                    <span>{{ $cf }}</span>
                                </label>
                            </div>
                            <div class="input-group input-group-sm">
                                <span class="input-group-addon tw-bg-slate-100 tw-text-gray-600 tw-font-semibold tw-text-xs">@lang( 'lang_v1.size' ) (px)</span>
                                <input type="number" class="form-control preview-trigger" name="print[{{ $field_name }}_size]" value="12" min="6" max="36">
                            </div>
                        </div>
                    </div>
                @endif
            @endforeach
        </div>
    </div>

    <!-- 3. Barcode Setting & Instant Live Preview Section -->
    <div class="product-section-card">
        <div class="product-section-header">
            <div class="product-section-badge">3</div>
            <div>
                <h3 class="product-section-title">@lang( 'barcode.barcode_setting' ) &amp; @lang('lang_v1.instant_preview')</h3>
                <p class="product-section-subtitle">@lang('lang_v1.select_layout_view_instant_preview')</p>
            </div>
        </div>

        <div class="row">
            <!-- Left: Setting Selection & Specifications -->
            <div class="col-md-5 col-sm-12 tw-mb-4">
                <div class="form-group tw-mb-4">
                    {!! Form::label('barcode_setting', __('barcode.barcode_setting') . ':', ['class' => 'tw-font-bold tw-text-gray-800 tw-text-sm tw-mb-1.5']) !!}
                    <div class="input-group" style="width: 100%;">
                        <span class="input-group-addon" style="background: #f8fafc; border-color: #cbd5e1; color: #2563eb;">
                            <i class="fa fa-cog"></i>
                        </span>
                        {!! Form::select('barcode_setting', $barcode_settings, !empty($default) ? $default->id : null, [
                            'class' => 'form-control select2',
                            'id' => 'barcode_setting',
                            'style' => 'width: 100%; border-color: #cbd5e1;'
                        ]); !!}
                    </div>
                </div>

                <!-- Selected Setting Specification Box -->
                <div class="tw-bg-slate-50 tw-border tw-border-slate-200 tw-rounded-xl tw-p-4 tw-space-y-2.5">
                    <div class="tw-flex tw-items-center tw-justify-between tw-border-b tw-border-slate-200 tw-pb-2">
                        <span class="tw-text-xs tw-font-bold tw-text-slate-600 tw-uppercase">@lang('lang_v1.layout_specifications')</span>
                        <span id="spec_type_badge" class="tw-inline-flex tw-items-center tw-px-2 tw-py-0.5 tw-rounded-full tw-text-xs tw-font-semibold tw-bg-blue-100 tw-text-blue-800">
                            @lang('lang_v1.sheet_paper')
                        </span>
                    </div>

                    <div class="tw-grid tw-grid-cols-2 tw-gap-2 tw-text-xs">
                        <div>
                            <span class="tw-text-gray-500">@lang('lang_v1.label_size'):</span>
                            <span id="spec_label_size" class="tw-font-bold tw-text-gray-800 tw-block">-</span>
                        </div>
                        <div>
                            <span class="tw-text-gray-500">@lang('lang_v1.sheet_size'):</span>
                            <span id="spec_sheet_size" class="tw-font-bold tw-text-gray-800 tw-block">-</span>
                        </div>
                        <div>
                            <span class="tw-text-gray-500">@lang('lang_v1.stickers_per_row'):</span>
                            <span id="spec_stickers_row" class="tw-font-bold tw-text-gray-800 tw-block">-</span>
                        </div>
                        <div>
                            <span class="tw-text-gray-500">@lang('lang_v1.total_per_sheet'):</span>
                            <span id="spec_stickers_sheet" class="tw-font-bold tw-text-gray-800 tw-block">-</span>
                        </div>
                    </div>

                    <div class="tw-pt-2 tw-border-t tw-border-slate-200 tw-flex tw-items-center tw-justify-between tw-text-xs">
                        <span class="tw-text-gray-500">@lang('lang_v1.margins_top_left'):</span>
                        <span id="spec_margins" class="tw-font-medium tw-text-gray-700">-</span>
                    </div>
                </div>
            </div>

            <!-- Right: Instant Live Preview Cards -->
            <div class="col-md-7 col-sm-12">
                <div class="barcode-preview-container">
                    <div class="tw-flex tw-items-center tw-justify-between tw-mb-3">
                        <span class="tw-text-xs tw-font-bold tw-text-slate-700 tw-uppercase">
                            <i class="fa fa-eye tw-text-blue-600 tw-mr-1"></i> @lang('lang_v1.instant_live_preview')
                        </span>
                        <span id="preview_dimensions_badge" class="tw-text-xs tw-text-slate-500 tw-font-medium">
                            @lang('lang_v1.auto_rendered')
                        </span>
                    </div>

                    <div class="row">
                        <!-- Mini Sheet Layout Diagram -->
                        <div class="col-sm-5 tw-mb-3 text-center">
                            <div class="tw-text-xs tw-font-bold tw-text-gray-700 tw-mb-1.5">@lang('lang_v1.sheet_layout')</div>
                            <div class="mini-sheet-paper" id="mini_sheet_paper">
                                <div class="mini-sheet-grid" id="mini_sheet_grid">
                                    <!-- Dynamic stickers injected by JS -->
                                </div>
                            </div>
                            <div class="tw-text-[11px] tw-text-gray-500 tw-mt-1.5" id="mini_sheet_caption">
                                @lang('lang_v1.loading_layout')
                            </div>
                        </div>

                        <!-- Zoom Single Label Preview -->
                        <div class="col-sm-7 tw-mb-3">
                            <div class="tw-text-xs tw-font-bold tw-text-gray-700 tw-mb-1.5 text-center">@lang('lang_v1.sticker_preview')</div>
                            <div class="zoom-sticker-card" id="zoom_sticker_card">
                                <div id="preview_biz_name" class="tw-font-bold tw-text-gray-900 tw-text-xs tw-truncate">
                                    {{ session('business.name') ?? __('lang_v1.your_business_name') }}
                                </div>
                                <div id="preview_prod_name" class="tw-font-semibold tw-text-gray-800 tw-text-[13px] tw-mt-0.5 tw-truncate">
                                    @lang('lang_v1.sample_product_name')
                                </div>
                                <div id="preview_prod_variation" class="tw-text-gray-600 tw-text-[11px] tw-truncate">
                                    @lang('lang_v1.sample_variation_name')
                                </div>

                                <!-- Barcode Visual Mock -->
                                <div class="barcode-lines-mock">
                                    <div class="barcode-line" style="width: 2px;"></div>
                                    <div class="barcode-line" style="width: 3px;"></div>
                                    <div class="barcode-line" style="width: 1px;"></div>
                                    <div class="barcode-line" style="width: 4px;"></div>
                                    <div class="barcode-line" style="width: 2px;"></div>
                                    <div class="barcode-line" style="width: 1px;"></div>
                                    <div class="barcode-line" style="width: 3px;"></div>
                                    <div class="barcode-line" style="width: 2px;"></div>
                                    <div class="barcode-line" style="width: 4px;"></div>
                                    <div class="barcode-line" style="width: 1px;"></div>
                                    <div class="barcode-line" style="width: 3px;"></div>
                                    <div class="barcode-line" style="width: 2px;"></div>
                                    <div class="barcode-line" style="width: 1px;"></div>
                                    <div class="barcode-line" style="width: 3px;"></div>
                                    <div class="barcode-line" style="width: 2px;"></div>
                                    <div class="barcode-line" style="width: 4px;"></div>
                                </div>
                                <div class="tw-text-[10px] tw-font-mono tw-text-gray-600 tw-tracking-widest">
                                    SKU-892019
                                </div>

                                <div id="preview_price" class="tw-font-bold tw-text-blue-700 tw-text-xs tw-mt-1">
                                    @lang('sale.price'): $ 25.00 <span class="tw-text-[10px] tw-font-normal tw-text-gray-500">(@lang('product.inc_of_tax'))</span>
                                </div>

                                <div class="tw-flex tw-justify-between tw-text-[9px] tw-text-gray-500 tw-mt-1.5 tw-pt-1 tw-border-t tw-border-dashed tw-border-gray-200">
                                    <span id="preview_packing">@lang('lang_v1.pack'): {{ @format_date('today') }}</span>
                                    <span id="preview_exp">@lang('lang_v1.exp'): {{ @format_date('today') }}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Action Buttons -->
    <div class="row tw-mt-6 tw-mb-8">
        <div class="col-sm-12 text-center tw-space-x-3">
            <button type="button" id="labels_preview" class="tw-dw-btn tw-dw-btn-primary tw-dw-btn-lg tw-text-white tw-font-semibold" style="border-radius: 10px; padding: 12px 28px; font-size: 15px;">
                <i class="fa fa-eye tw-mr-1.5"></i> @lang( 'barcode.preview' )
            </button>
            <button type="button" id="labels_print_pdf" class="tw-dw-btn tw-dw-btn-success tw-dw-btn-lg tw-text-white tw-font-semibold" style="border-radius: 10px; padding: 12px 28px; font-size: 15px;">
                <i class="fa fa-file-pdf-o tw-mr-1.5"></i> @lang( 'barcode.preview' ) PDF
            </button>
        </div>
    </div>

	{!! Form::close() !!}

	<div class="col-sm-8 hide display_label_div">
		<h3 class="box-title">@lang( 'barcode.preview' )</h3>
		<button type="button" class="col-sm-offset-2 btn btn-success btn-block" id="print_label">@lang('messages.print')</button>
	</div>
	<div class="clearfix"></div>
</section>

<!-- Preview section-->
<div id="preview_box">
</div>

@stop

@section('javascript')
    @php
        $barcode_details_map = !empty($barcode_details_json) ? $barcode_details_json : \App\Barcode::where('business_id', request()->session()->get('user.business_id'))->orWhereNull('business_id')->get()->keyBy('id')->toJson();
    @endphp
    <script>
        var barcodeSettingsData = {!! $barcode_details_map !!};

        function updateBarcodeInstantPreview() {
            var selectedId = $('#barcode_setting').val();
            var setting = barcodeSettingsData[selectedId];

            if (!setting) {
                return;
            }

            // Update specifications
            var isContinuous = (setting.is_continuous == 1);
            $('#spec_type_badge').text(isContinuous ? @json(__('lang_v1.continuous_roll')) : @json(__('lang_v1.sheet_paper')))
                .toggleClass('tw-bg-amber-100 tw-text-amber-800', isContinuous)
                .toggleClass('tw-bg-blue-100 tw-text-blue-800', !isContinuous);

            $('#spec_label_size').text(setting.width + '" × ' + setting.height + '"');
            $('#spec_sheet_size').text(isContinuous ? @json(__('lang_v1.continuous')) + ' (' + (setting.paper_width || setting.width) + '")' : setting.paper_width + '" × ' + setting.paper_height + '"');
            $('#spec_stickers_row').text(setting.stickers_in_one_row || 1);
            $('#spec_stickers_sheet').text(isContinuous ? @json(__('lang_v1.continuous')) : (setting.stickers_in_one_sheet || '-'));
            $('#spec_margins').text('T: ' + (setting.top_margin || 0) + '" / L: ' + (setting.left_margin || 0) + '"');
            $('#preview_dimensions_badge').text(setting.width + '" × ' + setting.height + '" (' + (setting.stickers_in_one_row || 1) + '/row)');

            // Render mini sheet grid
            var cols = parseInt(setting.stickers_in_one_row) || 1;
            var totalStickers = isContinuous ? (cols * 4) : (parseInt(setting.stickers_in_one_sheet) || (cols * 5));
            if (totalStickers > 40) totalStickers = 40; // cap for visualization

            var grid = $('#mini_sheet_grid');
            grid.empty();
            grid.css('grid-template-columns', 'repeat(' + cols + ', 1fr)');

            for (var i = 0; i < totalStickers; i++) {
                grid.append('<div class="mini-sticker-cell" title="Sticker #' + (i + 1) + '"></div>');
            }

            $('#mini_sheet_caption').text(cols + ' ' + @json(__('lang_v1.cols')) + ' × ' + Math.ceil(totalStickers / cols) + ' ' + @json(__('lang_v1.rows')) + ' (' + totalStickers + ' ' + @json(__('lang_v1.labels_shown')) + ')');

            // Update zoom sticker card based on checkboxes & font sizes
            var showName = $('input[name="print[name]"]').is(':checked');
            var showVariation = $('input[name="print[variations]"]').is(':checked');
            var showPrice = $('input[name="print[price]"]').is(':checked');
            var showBiz = $('input[name="print[business_name]"]').is(':checked');
            var showPack = $('input[name="print[packing_date]"]').is(':checked');
            var showExp = $('input[name="print[exp_date]"]').is(':checked');

            $('#preview_prod_name').toggle(showName);
            $('#preview_prod_variation').toggle(showVariation);
            $('#preview_price').toggle(showPrice);
            $('#preview_biz_name').toggle(showBiz);
            $('#preview_packing').toggle(showPack);
            $('#preview_exp').toggle(showExp);

            var priceType = $('select[name="print[price_type]"]').val();
            var priceTypeText = (priceType === 'exclusive') ? '(' + @json(__('product.exc_of_tax')) + ')' : '(' + @json(__('product.inc_of_tax')) + ')';
            $('#preview_price').html(@json(__('sale.price')) + ': $ 25.00 <span class="tw-text-[10px] tw-font-normal tw-text-gray-500">' + priceTypeText + '</span>');
        }

        $(document).ready(function() {
            $('#barcode_setting').on('change', function() {
                updateBarcodeInstantPreview();
            });

            $('.preview-trigger').on('change input', function() {
                updateBarcodeInstantPreview();
            });

            $(document).on('click', '.remove_label_product_row', function() {
                $(this).closest('tr').fadeOut(150, function() {
                    $(this).remove();
                });
            });

            // Initial render
            setTimeout(function() {
                updateBarcodeInstantPreview();
            }, 200);
        });
    </script>
	<script src="{{ asset('js/labels.js?v=' . $asset_v) }}"></script>
@endsection
