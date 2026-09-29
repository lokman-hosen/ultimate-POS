@extends('layouts.app')
@section('title', __('lang_v1.update_product_price'))

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
        margin-bottom: 18px;
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
    .instruction-step-card {
        background: #f8fafc;
        border: 1px solid #e2e8f0;
        border-radius: 12px;
        padding: 16px;
        height: 100%;
        transition: all 0.2s ease;
        position: relative;
    }
    .instruction-step-card:hover {
        background: #ffffff;
        border-color: #cbd5e1;
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05);
    }
    .instruction-step-card.warning-card {
        background: #fffbeb;
        border-color: #fde68a;
    }
    .instruction-step-card.warning-card:hover {
        background: #fef3c7;
        border-color: #fcd34d;
    }
    .format-table {
        width: 100%;
        border-collapse: separate;
        border-spacing: 0;
        border-radius: 10px;
        overflow: hidden;
        border: 1px solid #e2e8f0;
    }
    .format-table th {
        background: #f8fafc;
        color: #334155;
        font-weight: 600;
        font-size: 13px;
        padding: 12px 16px;
        border-bottom: 2px solid #e2e8f0;
        text-align: left;
    }
    .format-table td {
        padding: 12px 16px;
        font-size: 13px;
        border-bottom: 1px solid #f1f5f9;
        color: #1e293b;
        vertical-align: middle;
    }
    .format-table tr:last-child td {
        border-bottom: none;
    }
    .format-table tr:nth-child(even) {
        background-color: #fafbfd;
    }
    .preview-sheet-table {
        width: 100%;
        font-family: ui-monospace, SFMono-Regular, Menlo, Monaco, Consolas, monospace;
        font-size: 12px;
        border-collapse: collapse;
        background: #ffffff;
        border: 1px solid #cbd5e1;
        border-radius: 8px;
        overflow: hidden;
    }
    .preview-sheet-table th {
        background: #1e293b;
        color: #f8fafc;
        padding: 8px 12px;
        font-weight: 600;
        border: 1px solid #334155;
        text-align: left;
    }
    .preview-sheet-table td {
        padding: 8px 12px;
        border: 1px solid #e2e8f0;
        color: #334155;
    }
    .preview-sheet-table tr:nth-child(even) td {
        background: #f8fafc;
    }
</style>
@endsection

@section('content')

<!-- Content Header (Page header) -->
<section class="content-header tw-mb-4">
    <div class="tw-flex tw-flex-col sm:tw-flex-row sm:tw-items-center sm:tw-justify-between tw-gap-2">
        <div>
            <h1 class="tw-text-xl md:tw-text-3xl tw-font-bold tw-text-gray-900 tw-flex tw-items-center tw-gap-2">
                <i class="fa fa-tags tw-text-blue-600"></i>
                @lang('lang_v1.update_product_price')
            </h1>
            <p class="tw-text-sm tw-text-gray-500 tw-mt-1">
                @lang('lang_v1.import_export_product_price')
            </p>
        </div>
    </div>
</section>

<!-- Main content -->
<section class="content">
    @if (session('notification') || !empty($notification))
        <div class="row">
            <div class="col-sm-12">
                <div class="alert alert-danger alert-dismissible tw-rounded-xl tw-border-0 tw-shadow-sm">
                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                    @if(!empty($notification['msg']))
                        {{$notification['msg']}}
                    @elseif(session('notification.msg'))
                        {{ session('notification.msg') }}
                    @endif
                </div>
            </div>  
        </div>     
    @endif

    <!-- Workflow: Step 1 & Step 2 -->
    <div class="row">
        <!-- Step 1: Export Card -->
        <div class="col-md-6 col-sm-12">
            <div class="product-section-card tw-h-full tw-flex tw-flex-col tw-justify-between">
                <div>
                    <div class="product-section-header">
                        <div class="product-section-badge">1</div>
                        <div>
                            <h3 class="product-section-title">@lang('lang_v1.export_product_prices')</h3>
                            <p class="product-section-subtitle">@lang('lang_v1.price_import_instruction_1')</p>
                        </div>
                    </div>

                    <div class="tw-py-2 tw-space-y-3">
                        <div class="tw-flex tw-items-start tw-gap-3 tw-p-3 tw-bg-blue-50/60 tw-border tw-border-blue-100 tw-rounded-xl">
                            <i class="fa fa-info-circle tw-text-blue-600 tw-text-lg tw-mt-0.5 tw-flex-shrink-0"></i>
                            <div class="tw-text-xs tw-text-blue-900 tw-leading-relaxed">
                                {!! __('lang_v1.export_file_includes_info') !!}
                            </div>
                        </div>

                        <ul class="tw-text-xs tw-text-gray-600 tw-space-y-1.5 tw-pl-1">
                            <li class="tw-flex tw-items-center tw-gap-2">
                                <i class="fa fa-check-circle tw-text-emerald-500"></i> @lang('lang_v1.export_bullet_1')
                            </li>
                            <li class="tw-flex tw-items-center tw-gap-2">
                                <i class="fa fa-check-circle tw-text-emerald-500"></i> @lang('lang_v1.export_bullet_2')
                            </li>
                            <li class="tw-flex tw-items-center tw-gap-2">
                                <i class="fa fa-check-circle tw-text-emerald-500"></i> @lang('lang_v1.export_bullet_3')
                            </li>
                        </ul>
                    </div>
                </div>

                <div class="tw-mt-6 tw-pt-4 tw-border-t tw-border-gray-100">
                    <a href="{{action([\App\Http\Controllers\SellingPriceGroupController::class, 'export'])}}" class="tw-dw-btn tw-dw-btn-primary tw-text-white tw-w-full tw-font-semibold tw-flex tw-items-center tw-justify-center tw-gap-2" style="border-radius: 10px; padding: 12px 20px; font-size: 14px;">
                        <i class="fa fa-file-excel-o"></i>
                        @lang('lang_v1.export_product_prices') (.xlsx)
                    </a>
                </div>
            </div>
        </div>

        <!-- Step 2: Import Card -->
        <div class="col-md-6 col-sm-12">
            <div class="product-section-card tw-h-full tw-flex tw-flex-col tw-justify-between">
                {!! Form::open(['url' => action([\App\Http\Controllers\SellingPriceGroupController::class, 'import']), 'method' => 'post', 'enctype' => 'multipart/form-data', 'class' => 'tw-h-full tw-flex tw-flex-col tw-justify-between' ]) !!}
                    <div>
                        <div class="product-section-header">
                            <div class="product-section-badge">2</div>
                            <div>
                                <h3 class="product-section-title">@lang('product.file_to_import')</h3>
                                <p class="product-section-subtitle">@lang('lang_v1.price_import_instruction_4')</p>
                            </div>
                        </div>

                        <div class="form-group tw-mb-3">
                            <label for="product_group_prices" class="tw-text-xs tw-font-bold tw-text-gray-700 tw-mb-2 tw-block">
                                @lang('product.file_to_import') <span class="tw-text-rose-500">*</span>
                            </label>
                            <div class="tw-border-2 tw-border-dashed tw-border-slate-200 hover:tw-border-blue-400 tw-rounded-xl tw-p-4 tw-text-center tw-bg-slate-50/70 hover:tw-bg-blue-50/30 tw-transition-all">
                                <i class="fa fa-cloud-upload tw-text-3xl tw-text-blue-500 tw-mb-1"></i>
                                <p class="tw-text-xs tw-font-medium tw-text-gray-700 tw-mb-1">@lang('lang_v1.select_updated_spreadsheet')</p>
                                <p class="tw-text-xs tw-text-gray-400 tw-mb-3">{!! __('lang_v1.allowed_file_formats') !!}</p>
                                <div class="tw-flex tw-justify-center">
                                    {!! Form::file('product_group_prices', ['required' => 'required', 'accept' => '.xls, .xlsx, .csv', 'class' => 'tw-text-xs tw-text-gray-600', 'style' => 'max-width: 250px;']); !!}
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="tw-mt-4 tw-pt-4 tw-border-t tw-border-gray-100">
                        <button type="submit" class="tw-dw-btn tw-dw-btn-success tw-text-white tw-w-full tw-font-semibold tw-flex tw-items-center tw-justify-center tw-gap-2" style="border-radius: 10px; padding: 12px 20px; font-size: 14px;">
                            <i class="fa fa-upload"></i>
                            @lang('messages.submit') &amp; @lang('lang_v1.update_product_price')
                        </button>
                    </div>
                {!! Form::close() !!}
            </div>
        </div>
    </div>

    <!-- Step 3: Instructions Section -->
    <div class="row">
        <div class="col-sm-12">
            <div class="product-section-card">
                <div class="product-section-header">
                    <div class="product-section-badge">3</div>
                    <div>
                        <h3 class="product-section-title">@lang('lang_v1.instructions')</h3>
                        <p class="product-section-subtitle">@lang('lang_v1.read_instructions_carefully')</p>
                    </div>
                </div>

                <div class="row tw-gap-y-4">
                    <!-- Instruction 1 -->
                    <div class="col-md-3 col-sm-6 tw-mb-3">
                        <div class="instruction-step-card">
                            <div class="tw-flex tw-items-center tw-gap-2.5 tw-mb-2">
                                <span class="tw-w-7 tw-h-7 tw-rounded-full tw-bg-blue-100 tw-text-blue-700 tw-font-bold tw-text-xs tw-flex tw-items-center tw-justify-center">1</span>
                                <h4 class="tw-font-bold tw-text-gray-800 tw-text-sm tw-m-0">@lang('lang_v1.export_first')</h4>
                            </div>
                            <p class="tw-text-xs tw-text-gray-600 tw-leading-relaxed tw-m-0">
                                @lang('lang_v1.price_import_instruction_1')
                            </p>
                        </div>
                    </div>

                    <!-- Instruction 2 -->
                    <div class="col-md-3 col-sm-6 tw-mb-3">
                        <div class="instruction-step-card">
                            <div class="tw-flex tw-items-center tw-gap-2.5 tw-mb-2">
                                <span class="tw-w-7 tw-h-7 tw-rounded-full tw-bg-blue-100 tw-text-blue-700 tw-font-bold tw-text-xs tw-flex tw-items-center tw-justify-center">2</span>
                                <h4 class="tw-font-bold tw-text-gray-800 tw-text-sm tw-m-0">@lang('lang_v1.edit_prices')</h4>
                            </div>
                            <p class="tw-text-xs tw-text-gray-600 tw-leading-relaxed tw-m-0">
                                @lang('lang_v1.price_import_instruction_2')
                            </p>
                        </div>
                    </div>

                    <!-- Instruction 3 (Warning Highlighted) -->
                    <div class="col-md-3 col-sm-6 tw-mb-3">
                        <div class="instruction-step-card warning-card">
                            <div class="tw-flex tw-items-center tw-gap-2.5 tw-mb-2">
                                <span class="tw-w-7 tw-h-7 tw-rounded-full tw-bg-amber-200 tw-text-amber-800 tw-font-bold tw-text-xs tw-flex tw-items-center tw-justify-center">
                                    <i class="fa fa-exclamation"></i>
                                </span>
                                <h4 class="tw-font-bold tw-text-amber-900 tw-text-sm tw-m-0">@lang('lang_v1.keep_headers_and_sku')</h4>
                            </div>
                            <p class="tw-text-xs tw-text-amber-950 tw-font-medium tw-leading-relaxed tw-m-0">
                                @lang('lang_v1.price_import_instruction_3')
                            </p>
                        </div>
                    </div>

                    <!-- Instruction 4 -->
                    <div class="col-md-3 col-sm-6 tw-mb-3">
                        <div class="instruction-step-card">
                            <div class="tw-flex tw-items-center tw-gap-2.5 tw-mb-2">
                                <span class="tw-w-7 tw-h-7 tw-rounded-full tw-bg-emerald-100 tw-text-emerald-700 tw-font-bold tw-text-xs tw-flex tw-items-center tw-justify-center">4</span>
                                <h4 class="tw-font-bold tw-text-gray-800 tw-text-sm tw-m-0">@lang('lang_v1.import_file')</h4>
                            </div>
                            <p class="tw-text-xs tw-text-gray-600 tw-leading-relaxed tw-m-0">
                                @lang('lang_v1.price_import_instruction_4')
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Step 4: Import Data Format Guide -->
    <div class="row">
        <div class="col-sm-12">
            <div class="product-section-card">
                <div class="product-section-header">
                    <div class="product-section-badge">4</div>
                    <div>
                        <h3 class="product-section-title">@lang('lang_v1.format_for_import_data')</h3>
                        <p class="product-section-subtitle">@lang('lang_v1.format_for_import_data_subtitle')</p>
                    </div>
                </div>

                <!-- Format Columns Explanation Table -->
                <div class="tw-overflow-x-auto tw-mb-6">
                    <table class="format-table">
                        <thead>
                            <tr>
                                <th style="width: 80px;">@lang('lang_v1.col_no')</th>
                                <th style="width: 220px;">@lang('lang_v1.col_name')</th>
                                <th style="width: 140px;">@lang('lang_v1.requirement')</th>
                                <th>@lang('lang_v1.instruction')</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td><span class="tw-font-bold tw-text-slate-700">1</span></td>
                                <td>
                                    <span class="tw-font-bold tw-text-gray-900">product</span>
                                    <div class="tw-text-xs tw-text-gray-500">(@lang('sale.product'))</div>
                                </td>
                                <td>
                                    <span class="tw-inline-flex tw-items-center tw-px-2.5 tw-py-0.5 tw-rounded-full tw-text-xs tw-font-semibold tw-bg-gray-100 tw-text-gray-700">
                                        @lang('lang_v1.optional')
                                    </span>
                                </td>
                                <td>
                                    <div class="tw-text-xs tw-text-gray-700">{!! __('lang_v1.col_product_desc') !!}</div>
                                </td>
                            </tr>
                            <tr>
                                <td><span class="tw-font-bold tw-text-slate-700">2</span></td>
                                <td>
                                    <span class="tw-font-bold tw-text-gray-900">sku</span>
                                    <div class="tw-text-xs tw-text-gray-500">(@lang('product.sku'))</div>
                                </td>
                                <td>
                                    <span class="tw-inline-flex tw-items-center tw-px-2.5 tw-py-0.5 tw-rounded-full tw-text-xs tw-font-semibold tw-bg-rose-100 tw-text-rose-800">
                                        @lang('lang_v1.required')
                                    </span>
                                </td>
                                <td>
                                    <div class="tw-text-xs tw-text-gray-700">{!! __('lang_v1.col_sku_desc') !!}</div>
                                </td>
                            </tr>
                            <tr>
                                <td><span class="tw-font-bold tw-text-slate-700">3</span></td>
                                <td>
                                    <span class="tw-font-bold tw-text-gray-900">@lang('lang_v1.selling_price_inc_tax_default')</span>
                                    <div class="tw-text-xs tw-text-gray-500">@lang('lang_v1.default_price_label')</div>
                                </td>
                                <td>
                                    <span class="tw-inline-flex tw-items-center tw-px-2.5 tw-py-0.5 tw-rounded-full tw-text-xs tw-font-semibold tw-bg-blue-100 tw-text-blue-800">
                                        @lang('lang_v1.editable')
                                    </span>
                                </td>
                                <td>
                                    <div class="tw-text-xs tw-text-gray-700">@lang('lang_v1.col_selling_price_desc')</div>
                                </td>
                            </tr>
                            <tr>
                                <td><span class="tw-font-bold tw-text-slate-700">4+</span></td>
                                <td>
                                    <span class="tw-font-bold tw-text-gray-900">@lang('lang_v1.price_group_name_col')</span>
                                    <div class="tw-text-xs tw-text-gray-500">@lang('lang_v1.price_group_example')</div>
                                </td>
                                <td>
                                    <span class="tw-inline-flex tw-items-center tw-px-2.5 tw-py-0.5 tw-rounded-full tw-text-xs tw-font-semibold tw-bg-emerald-100 tw-text-emerald-800">
                                        @lang('lang_v1.optional')
                                    </span>
                                </td>
                                <td>
                                    <div class="tw-text-xs tw-text-gray-700">@lang('lang_v1.col_price_group_desc')</div>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <!-- Visual Spreadsheet Example -->
                <div class="tw-bg-slate-50 tw-p-4 tw-rounded-xl tw-border tw-border-slate-200">
                    <div class="tw-flex tw-items-center tw-justify-between tw-mb-2">
                        <span class="tw-text-xs tw-font-bold tw-text-slate-700 tw-uppercase tw-tracking-wider">
                            <i class="fa fa-table tw-text-blue-600 tw-mr-1"></i> @lang('lang_v1.sample_spreadsheet_preview')
                        </span>
                        <span class="tw-text-xs tw-text-slate-500">product_prices.xlsx</span>
                    </div>

                    <div class="tw-overflow-x-auto">
                        <table class="preview-sheet-table">
                            <thead>
                                <tr>
                                    <th>product</th>
                                    <th>sku</th>
                                    <th>Selling Price Including Tax</th>
                                    <th>Retail Price</th>
                                    <th>Wholesale Price</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>Men Polo Shirt - Black - L</td>
                                    <td>POLO-BLK-L</td>
                                    <td>25.00</td>
                                    <td>24.00</td>
                                    <td>19.50</td>
                                </tr>
                                <tr>
                                    <td>Men Polo Shirt - Black - XL</td>
                                    <td>POLO-BLK-XL</td>
                                    <td>27.00</td>
                                    <td>26.00</td>
                                    <td>21.00</td>
                                </tr>
                                <tr>
                                    <td>Wireless Headphones</td>
                                    <td>HDPH-WL-01</td>
                                    <td>85.00</td>
                                    <td>80.00</td>
                                    <td>65.00</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>

            </div>
        </div>
    </div>

</section>
<!-- /.content -->
@stop
