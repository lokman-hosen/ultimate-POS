@extends('layouts.app')
@section('title', __('product.edit_product'))

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
    /* Fixed compact preview constraints for image upload */
    .file-input {
        width: 100% !important;
        max-width: 100% !important;
    }
    .file-preview {
        max-width: 100% !important;
        width: 100% !important;
        border-radius: 8px !important;
        border: 1px dashed #cbd5e1 !important;
        background: #f8fafc !important;
        padding: 4px 6px !important;
        margin-bottom: 8px !important;
        box-sizing: border-box !important;
        overflow: hidden !important;
    }
    .file-drop-zone {
        border: none !important;
        margin: 0 !important;
        padding: 2px !important;
        min-height: auto !important;
        max-height: 115px !important;
        overflow: hidden !important;
        display: flex !important;
        flex-direction: column !important;
        align-items: center !important;
        justify-content: center !important;
    }
    .file-drop-zone-title {
        padding: 10px 8px !important;
        font-size: 12px !important;
        color: #64748b !important;
    }
    .file-preview-thumbnails {
        display: flex !important;
        justify-content: center !important;
        align-items: center !important;
        width: 100% !important;
        overflow: hidden !important;
    }
    .krajee-default.file-preview-frame,
    .file-preview-frame {
        margin: 2px auto !important;
        float: none !important;
        display: inline-block !important;
        border: 1px solid #e2e8f0 !important;
        box-shadow: 0 1px 2px rgba(0, 0, 0, 0.04) !important;
        border-radius: 6px !important;
        background: #ffffff !important;
        max-width: 100% !important;
        width: auto !important;
        height: auto !important;
        padding: 3px !important;
        box-sizing: border-box !important;
        overflow: hidden !important;
    }
    .krajee-default.file-preview-frame .kv-file-content,
    .file-preview-frame .kv-file-content {
        width: 100% !important;
        height: 75px !important;
        max-height: 75px !important;
        display: flex !important;
        align-items: center !important;
        justify-content: center !important;
        overflow: hidden !important;
    }
    .file-preview-image,
    .kv-preview-data.file-preview-image,
    .file-preview-frame img,
    .krajee-default.file-preview-frame img {
        max-width: 100% !important;
        max-height: 75px !important;
        width: auto !important;
        height: 75px !important;
        object-fit: contain !important;
        border-radius: 4px !important;
        display: block !important;
        margin: 0 auto !important;
    }
    .krajee-default.file-preview-frame .file-thumbnail-footer {
        height: auto !important;
        padding: 2px 0 0 0 !important;
    }
    .krajee-default.file-preview-frame .file-footer-caption {
        width: 100% !important;
        max-width: 120px !important;
        font-size: 10px !important;
        margin: 1px auto !important;
        white-space: nowrap !important;
        overflow: hidden !important;
        text-overflow: ellipsis !important;
    }
    .krajee-default.file-preview-frame .file-footer-buttons {
        float: none !important;
        text-align: center !important;
    }
    .krajee-default.file-preview-frame .file-footer-buttons .btn {
        padding: 1px 4px !important;
        font-size: 10px !important;
        line-height: 1 !important;
    }
    .krajee-default.file-preview-frame .file-actions {
        margin-top: 2px !important;
    }
    .file-caption-main {
        width: 100% !important;
    }
    .file-caption {
        border-radius: 8px 0 0 8px !important;
        height: 36px !important;
        line-height: 34px !important;
        font-size: 12px !important;
    }
    .btn-file {
        border-radius: 0 8px 8px 0 !important;
        height: 36px !important;
        line-height: 22px !important;
        font-size: 12px !important;
    }
    .fileinput-remove-button {
        height: 36px !important;
        font-size: 12px !important;
    }
    .form-group label {
        font-weight: 600;
        color: #334155;
        font-size: 13px;
        margin-bottom: 6px;
    }
    .product-section-card .form-control {
        border-radius: 8px;
        border: 1px solid #cbd5e1;
        box-shadow: none;
        height: 40px;
    }
    .product-section-card .form-control:focus {
        border-color: #3b82f6;
        box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.15);
    }
    .product-section-card textarea.form-control {
        height: auto;
    }
    .product-section-card .select2-container--default .select2-selection--single {
        border-radius: 8px;
        border: 1px solid #cbd5e1;
        height: 40px;
    }
    .product-section-card .select2-container--default .select2-selection--single .select2-selection__rendered {
        line-height: 38px;
    }
    .product-section-card .select2-container--default .select2-selection--single .select2-selection__arrow {
        height: 38px;
    }
    .product-section-card .input-group-btn .btn {
        height: 40px;
        border-radius: 0 8px 8px 0;
        border: 1px solid #cbd5e1;
        border-left: none;
    }
    .product-footer-actions {
        background: #ffffff;
        border-radius: 14px;
        border: 1px solid #e2e8f0;
        padding: 16px 24px;
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05);
        margin-top: 10px;
        margin-bottom: 40px;
    }
</style>
@endsection

@section('content')

@php
  $is_image_required = !empty($common_settings['is_product_image_required']) && empty($product->image);
@endphp

<!-- Content Header (Page header) -->
<section class="content-header">
    <h1 class="tw-text-xl md:tw-text-3xl tw-font-bold tw-text-black">@lang('product.edit_product')</h1>
</section>

<!-- Main content -->
<section class="content">
{!! Form::open(['url' => action([\App\Http\Controllers\ProductController::class, 'update'] , [$product->id] ), 'method' => 'PUT', 'id' => 'product_add_form',
        'class' => 'product_form', 'files' => true ]) !!}
    <input type="hidden" id="product_id" value="{{ $product->id }}">

    <!-- Top Row: Section 1 (Basic Info) & Section 2 (Product Image) -->
    <div class="row">
        <!-- Section 1: Basic Information -->
        <div class="col-lg-8 col-md-7 col-sm-12">
            <div class="product-section-card">
                <div class="product-section-header">
                    <div class="product-section-badge">1</div>
                    <div>
                        <h3 class="product-section-title">@lang('sale.product')</h3>
                        <p class="product-section-subtitle">@lang('product.product_name'), @lang('product.sku'), @lang('product.category'), @lang('product.unit')</p>
                    </div>
                </div>

                <div class="row">
                    <div class="col-sm-6 col-md-4">
                        <div class="form-group">
                            {!! Form::label('name', __('product.product_name') . ':*') !!}
                            {!! Form::text('name', $product->name, ['class' => 'form-control', 'required',
                            'placeholder' => __('product.product_name')]); !!}
                        </div>
                    </div>

                    <div class="col-sm-6 col-md-4">
                        <div class="form-group">
                            {!! Form::label('sku', __('product.sku')  . ':*') !!} @show_tooltip(__('tooltip.sku'))
                            {!! Form::text('sku', $product->sku, ['class' => 'form-control',
                            'placeholder' => __('product.sku'), 'required']); !!}
                        </div>
                    </div>

                    <div class="col-sm-6 col-md-4">
                        <div class="form-group">
                            {!! Form::label('barcode_type', __('product.barcode_type') . ':*') !!}
                            {!! Form::select('barcode_type', $barcode_types, $product->barcode_type, ['placeholder' => __('messages.please_select'), 'class' => 'form-control select2', 'required']); !!}
                        </div>
                    </div>

                    <div class="col-sm-6 col-md-4 @if(!session('business.enable_category')) hide @endif">
                        <div class="form-group">
                            {!! Form::label('category_id', __('product.category') . ':') !!}
                            {!! Form::select('category_id', $categories, $product->category_id, ['placeholder' => __('messages.please_select'), 'class' => 'form-control select2']); !!}
                        </div>
                    </div>

                    <div class="col-sm-6 col-md-4 @if(!(session('business.enable_category') && session('business.enable_sub_category'))) hide @endif">
                        <div class="form-group">
                            {!! Form::label('sub_category_id', __('product.sub_category')  . ':') !!}
                            {!! Form::select('sub_category_id', $sub_categories, $product->sub_category_id, ['placeholder' => __('messages.please_select'), 'class' => 'form-control select2']); !!}
                        </div>
                    </div>

                    <div class="col-sm-6 col-md-4 @if(!session('business.enable_brand')) hide @endif">
                        <div class="form-group">
                            {!! Form::label('brand_id', __('product.brand') . ':') !!}
                            <div class="input-group">
                                {!! Form::select('brand_id', $brands, $product->brand_id, ['placeholder' => __('messages.please_select'), 'class' => 'form-control select2']); !!}
                                <span class="input-group-btn">
                                    <button type="button" @if(!auth()->user()->can('brand.create')) disabled @endif class="btn btn-default bg-white btn-flat btn-modal" data-href="{{action([\App\Http\Controllers\BrandController::class, 'create'], ['quick_add' => true])}}" title="@lang('brand.add_brand')" data-container=".view_modal"><i class="fa fa-plus-circle text-primary fa-lg"></i></button>
                                </span>
                            </div>
                        </div>
                    </div>

                    <div class="col-sm-6 col-md-4">
                        <div class="form-group">
                            {!! Form::label('unit_id', __('product.unit') . ':*') !!}
                            <div class="input-group">
                                {!! Form::select('unit_id', $units, $product->unit_id, ['placeholder' => __('messages.please_select'), 'class' => 'form-control select2', 'required']); !!}
                                <span class="input-group-btn">
                                    <button type="button" @if(!auth()->user()->can('unit.create')) disabled @endif class="btn btn-default bg-white btn-flat quick_add_unit btn-modal" data-href="{{action([\App\Http\Controllers\UnitController::class, 'create'], ['quick_add' => true])}}" title="@lang('unit.add_unit')" data-container=".view_modal"><i class="fa fa-plus-circle text-primary fa-lg"></i></button>
                                </span>
                            </div>
                        </div>
                    </div>

                    <div class="col-sm-6 col-md-4 @if(!session('business.enable_sub_units')) hide @endif">
                        <div class="form-group">
                            {!! Form::label('sub_unit_ids', __('lang_v1.related_sub_units') . ':') !!} @show_tooltip(__('lang_v1.sub_units_tooltip'))
                            <select name="sub_unit_ids[]" class="form-control select2" multiple id="sub_unit_ids">
                                @foreach($sub_units as $sub_unit_id => $sub_unit_value)
                                    <option value="{{$sub_unit_id}}" 
                                        @if(is_array($product->sub_unit_ids) && in_array($sub_unit_id, $product->sub_unit_ids)) selected @endif>{{$sub_unit_value['name']}}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    @if(!empty($common_settings['enable_secondary_unit']))
                    <div class="col-sm-6 col-md-4">
                        <div class="form-group">
                            {!! Form::label('secondary_unit_id', __('lang_v1.secondary_unit') . ':') !!} @show_tooltip(__('lang_v1.secondary_unit_help'))
                            {!! Form::select('secondary_unit_id', $units, $product->secondary_unit_id, ['class' => 'form-control select2']); !!}
                        </div>
                    </div>
                    @endif

                    @if(!empty($common_settings['enable_product_warranty']))
                    <div class="col-sm-6 col-md-4">
                        <div class="form-group">
                            {!! Form::label('warranty_id', __('lang_v1.warranty') . ':') !!}
                            {!! Form::select('warranty_id', $warranties, $product->warranty_id, ['class' => 'form-control select2', 'placeholder' => __('messages.please_select')]); !!}
                        </div>
                    </div>
                    @endif

                    <div class="col-sm-6 col-md-4">
                        <div class="form-group">
                            {!! Form::label('weight', __('lang_v1.weight') . ':') !!}
                            {!! Form::text('weight', $product->weight, ['class' => 'form-control', 'placeholder' => __('lang_v1.weight')]); !!}
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Section 2: Product Image & Media -->
        <div class="col-lg-4 col-md-5 col-sm-12">
            <div class="product-section-card">
                <div class="product-section-header">
                    <div class="product-section-badge">2</div>
                    <div>
                        <h3 class="product-section-title">@lang('lang_v1.product_image')</h3>
                        <p class="product-section-subtitle">@lang('lang_v1.product_image') & @lang('lang_v1.product_brochure')</p>
                    </div>
                </div>

                <div class="form-group">
                    {!! Form::label('image', __('lang_v1.product_image') . ':') !!}
                    {!! Form::file('image', ['id' => 'upload_image', 'accept' => 'image/*', 'required' => $is_image_required, 'class' => 'upload-element']); !!}
                    <small class="text-muted" style="display: block; margin-top: 6px;">
                        @lang('purchase.max_file_size', ['size' => (config('constants.document_size_limit') / 1000000)]) &bull; @lang('lang_v1.aspect_ratio_should_be_1_1')
                        @if(!empty($product->image))
                            <br>@lang('lang_v1.previous_image_will_be_replaced')
                        @endif
                    </small>
                </div>

                <div class="form-group" style="margin-top: 15px;">
                    {!! Form::label('product_brochure', __('lang_v1.product_brochure') . ':') !!}
                    {!! Form::file('product_brochure', ['id' => 'product_brochure', 'class' => 'form-control', 'accept' => implode(',', array_keys(config('constants.document_upload_mimes_types')))]); !!}
                    <small class="text-muted">
                        @lang('lang_v1.previous_file_will_be_replaced')<br>
                        @lang('purchase.max_file_size', ['size' => (config('constants.document_size_limit') / 1000000)])
                        @includeIf('components.document_help_text')
                    </small>
                </div>
            </div>
        </div>
    </div>

    <!-- Middle Row: Section 3 (Pricing & Cost on left) & Sections 4 & 6 (Stock & Locations stacked on right) -->
    <div class="row">
        <!-- Section 3: Pricing & Tax (Left Column) -->
        <div class="col-lg-6 col-md-6 col-sm-12">
            <div class="product-section-card">
                <div class="product-section-header">
                    <div class="product-section-badge">3</div>
                    <div>
                        <h3 class="product-section-title">@lang('lang_v1.pricing')</h3>
                        <p class="product-section-subtitle">@lang('product.applicable_tax'), @lang('product.product_type') & @lang('product.default_purchase_price')</p>
                    </div>
                </div>

                <div class="row">
                    <div class="col-sm-6 @if(!session('business.enable_price_tax')) hide @endif">
                        <div class="form-group">
                            {!! Form::label('tax', __('product.applicable_tax') . ':') !!}
                            {!! Form::select('tax', $taxes, $product->tax, ['placeholder' => __('messages.please_select'), 'class' => 'form-control select2'], $tax_attributes); !!}
                        </div>
                    </div>

                    <div class="col-sm-6 @if(!session('business.enable_price_tax')) hide @endif">
                        <div class="form-group">
                            {!! Form::label('tax_type', __('product.selling_price_tax_type') . ':*') !!}
                            {!! Form::select('tax_type',['inclusive' => __('product.inclusive'), 'exclusive' => __('product.exclusive')], $product->tax_type,
                            ['class' => 'form-control select2', 'required']); !!}
                        </div>
                    </div>

                    <div class="col-sm-12">
                        <div class="form-group">
                            {!! Form::label('type', __('product.product_type') . ':*') !!} @show_tooltip(__('tooltip.product_type'))
                            {!! Form::select('type', $product_types, $product->type, ['class' => 'form-control select2',
                            'required', 'disabled', 'data-action' => 'edit', 'data-product_id' => $product->id ]); !!}
                        </div>
                    </div>

                    <div class="form-group col-sm-12" id="product_form_part"></div>
                    <input type="hidden" id="variation_counter" value="0">
                    <input type="hidden" id="default_profit_percent" value="{{ $default_profit_percent }}">
                </div>
            </div>
        </div>

        <!-- Right Column: Section 4 (Stock & Inventory) + Section 6 (Locations & Storage) -->
        <div class="col-lg-6 col-md-6 col-sm-12">
            <!-- Section 4: Stock & Inventory -->
            <div class="product-section-card">
                <div class="product-section-header">
                    <div class="product-section-badge">4</div>
                    <div>
                        <h3 class="product-section-title">@lang('product.manage_stock')</h3>
                        <p class="product-section-subtitle">@lang('product.alert_quantity'), @lang('lang_v1.enable_imei_or_sr_no') & @lang('product.expires_in')</p>
                    </div>
                </div>

                <div class="row">
                    <div class="col-sm-6">
                        <div class="form-group">
                            <label>
                                {!! Form::checkbox('enable_stock', 1, $product->enable_stock, ['class' => 'input-icheck', 'id' => 'enable_stock']); !!} <strong>@lang('product.manage_stock')</strong>
                            </label>@show_tooltip(__('tooltip.enable_stock'))
                            <p class="help-block"><small>@lang('product.enable_stock_help')</small></p>
                        </div>
                    </div>

                    <div class="col-sm-6" id="alert_quantity_div" @if(!$product->enable_stock) style="display:none" @endif>
                        <div class="form-group">
                            {!! Form::label('alert_quantity', __('product.alert_quantity') . ':') !!} @show_tooltip(__('tooltip.alert_quantity'))
                            {!! Form::text('alert_quantity', $alert_quantity, ['class' => 'form-control input_number',
                            'placeholder' => __('product.alert_quantity') , 'min' => '0']); !!}
                        </div>
                    </div>

                    <div class="clearfix"></div>

                    <div class="col-sm-6">
                        <div class="form-group">
                            <label>
                                {!! Form::checkbox('enable_sr_no', 1, $product->enable_sr_no, ['class' => 'input-icheck']); !!} <strong>@lang('lang_v1.enable_imei_or_sr_no')</strong>
                            </label> @show_tooltip(__('lang_v1.tooltip_sr_no'))
                        </div>
                    </div>

                    <div class="col-sm-6">
                        <div class="form-group">
                            <label>
                                {!! Form::checkbox('not_for_selling', 1, $product->not_for_selling, ['class' => 'input-icheck']); !!} <strong>@lang('lang_v1.not_for_selling')</strong>
                            </label> @show_tooltip(__('lang_v1.tooltip_not_for_selling'))
                        </div>
                    </div>

                    @if(session('business.enable_product_expiry'))
                    @php
                      $expiry_period = 12;
                      $hide = true;
                      if(session('business.expiry_type') == 'add_expiry'){
                        $expiry_period = 12;
                        $hide = true;
                      } else {
                        $expiry_period = null;
                        $hide = false;
                      }
                      $disabled = false;
                      $disabled_period = false;
                      if( empty($product->expiry_period_type) || empty($product->enable_stock) ){
                        $disabled = true;
                      }
                      if( empty($product->enable_stock) ){
                        $disabled_period = true;
                      }
                    @endphp
                    <div class="col-sm-12 @if($hide) hide @endif">
                        <div class="form-group">
                            <div class="multi-input">
                                {!! Form::label('expiry_period', __('product.expires_in') . ':') !!}<br>
                                {!! Form::text('expiry_period', @num_format($product->expiry_period), ['class' => 'form-control pull-left input_number',
                                  'placeholder' => __('product.expiry_period'), 'style' => 'width:60%;', 'disabled' => $disabled]); !!}
                                {!! Form::select('expiry_period_type', ['months'=>__('product.months'), 'days'=>__('product.days'), '' =>__('product.not_applicable') ], $product->expiry_period_type, ['class' => 'form-control select2 pull-left', 'style' => 'width:40%;', 'id' => 'expiry_period_type', 'disabled' => $disabled_period]); !!}
                            </div>
                        </div>
                    </div>
                    @endif
                </div>
            </div>

            <!-- Section 6: Business Locations & Storage Racks (Under Section 4) -->
            <div class="product-section-card">
                <div class="product-section-header">
                    <div class="product-section-badge">6</div>
                    <div>
                        <h3 class="product-section-title">@lang('business.business_locations')</h3>
                        <p class="product-section-subtitle">@lang('business.business_locations') & @lang('lang_v1.rack_details')</p>
                    </div>
                </div>

                <div class="form-group">
                    {!! Form::label('product_locations', __('business.business_locations') . ':') !!} @show_tooltip(__('lang_v1.product_location_help'))
                    {!! Form::select('product_locations[]', $business_locations, $product->product_locations->pluck('id'), ['class' => 'form-control select2', 'multiple', 'id' => 'product_locations']); !!}
                </div>

                @if(session('business.enable_racks') || session('business.enable_row') || session('business.enable_position'))
                <div style="margin-top: 15px;">
                    <h4 style="font-size: 14px; font-weight: 600; color: #334155; margin-bottom: 12px;">
                        @lang('lang_v1.rack_details'): @show_tooltip(__('lang_v1.tooltip_rack_details'))
                    </h4>
                    <div class="row">
                        @foreach($business_locations as $id => $location)
                        <div class="col-sm-6">
                            <div class="form-group">
                                {!! Form::label('rack_' . $id, $location . ':') !!}
                                @if(!empty($rack_details[$id]))
                                    @if(session('business.enable_racks'))
                                      {!! Form::text('product_racks_update[' . $id . '][rack]', $rack_details[$id]['rack'], ['class' => 'form-control', 'id' => 'rack_' . $id]); !!}
                                    @endif

                                    @if(session('business.enable_row'))
                                      {!! Form::text('product_racks_update[' . $id . '][row]', $rack_details[$id]['row'], ['class' => 'form-control', 'style' => 'margin-top: 4px;']); !!}
                                    @endif

                                    @if(session('business.enable_position'))
                                      {!! Form::text('product_racks_update[' . $id . '][position]', $rack_details[$id]['position'], ['class' => 'form-control', 'style' => 'margin-top: 4px;']); !!}
                                    @endif
                                @else
                                    {!! Form::text('product_racks[' . $id . '][rack]', null, ['class' => 'form-control', 'id' => 'rack_' . $id, 'placeholder' => __('lang_v1.rack')]); !!}

                                    {!! Form::text('product_racks[' . $id . '][row]', null, ['class' => 'form-control', 'placeholder' => __('lang_v1.row'), 'style' => 'margin-top: 4px;']); !!}

                                    {!! Form::text('product_racks[' . $id . '][position]', null, ['class' => 'form-control', 'placeholder' => __('lang_v1.position'), 'style' => 'margin-top: 4px;']); !!}
                                @endif
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Row 3: Section 5 (Product Description - Full Width) -->
    <div class="row">
        <div class="col-sm-12">
            <div class="product-section-card">
                <div class="product-section-header">
                    <div class="product-section-badge">5</div>
                    <div>
                        <h3 class="product-section-title">@lang('lang_v1.product_description')</h3>
                        <p class="product-section-subtitle">@lang('lang_v1.product_description') & @lang('lang_v1.preparation_time_in_minutes')</p>
                    </div>
                </div>

                <div class="form-group">
                    {!! Form::label('product_description', __('lang_v1.product_description') . ':') !!}
                    {!! Form::textarea('product_description', $product->product_description, ['class' => 'form-control', 'rows' => '4']); !!}
                </div>

                <div class="form-group" style="margin-top: 15px;">
                    {!! Form::label('preparation_time_in_minutes', __('lang_v1.preparation_time_in_minutes') . ':') !!}
                    {!! Form::number('preparation_time_in_minutes', $product->preparation_time_in_minutes, ['class' => 'form-control', 'placeholder' => __('lang_v1.preparation_time_in_minutes')]); !!}
                </div>
            </div>
        </div>
    </div>

    <!-- Row 4: Section 7 (Additional & Custom Fields) -->
    @php
        $custom_labels = json_decode(session('business.custom_labels'), true);
        $product_custom_fields = !empty($custom_labels['product']) ? $custom_labels['product'] : [];
        $product_cf_details = !empty($custom_labels['product_cf_details']) ? $custom_labels['product_cf_details'] : [];
        $has_custom_fields = false;
        foreach($product_custom_fields as $cf) {
            if(!empty($cf)) { $has_custom_fields = true; break; }
        }
    @endphp

    @if($has_custom_fields || !empty($pos_module_data))
    <div class="row">
        <div class="col-sm-12">
            <div class="product-section-card">
                <div class="product-section-header">
                    <div class="product-section-badge">7</div>
                    <div>
                        <h3 class="product-section-title">@lang('lang_v1.custom_fields')</h3>
                        <p class="product-section-subtitle">@lang('lang_v1.more_info')</p>
                    </div>
                </div>

                <div class="row">
                    @foreach($product_custom_fields as $index => $cf)
                        @if(!empty($cf))
                            @php
                                $db_field_name = 'product_custom_field' . $loop->iteration;
                                $cf_type = !empty($product_cf_details[$loop->iteration]['type']) ? $product_cf_details[$loop->iteration]['type'] : 'text';
                                $dropdown = !empty($product_cf_details[$loop->iteration]['dropdown_options']) ? explode(PHP_EOL, $product_cf_details[$loop->iteration]['dropdown_options']) : [];
                            @endphp

                            <div class="col-sm-3">
                                <div class="form-group">
                                    {!! Form::label($db_field_name, $cf . ':') !!}
                                    @if(in_array($cf_type, ['text', 'date']))
                                        <input type="{{$cf_type}}" name="{{$db_field_name}}" id="{{$db_field_name}}" 
                                        value="{{$product->$db_field_name}}" class="form-control" placeholder="{{$cf}}">
                                    @elseif($cf_type == 'dropdown')
                                         <select name="{{$db_field_name}}" id="{{$db_field_name}}" class="form-control select2">
                                            @foreach($dropdown as $option)
                                                <option value="{{$option}}" @if($option == $product->$db_field_name) selected @endif>{{$option}}</option>
                                            @endforeach
                                         </select>
                                    @endif
                                </div>
                            </div>
                        @endif
                    @endforeach

                    <!-- include module fields -->
                    @if(!empty($pos_module_data))
                        @foreach($pos_module_data as $key => $value)
                            @if(!empty($value['view_path']))
                                @includeIf($value['view_path'], ['view_data' => $value['view_data']])
                            @endif
                        @endforeach
                    @endif

                    @include('layouts.partials.module_form_part')
                </div>
            </div>
        </div>
    </div>
    @else
        @include('layouts.partials.module_form_part')
    @endif

    <!-- Form Submit Actions Footer -->
    <div class="row">
        <div class="col-sm-12">
            <div class="product-footer-actions text-center">
                <input type="hidden" name="submit_type" id="submit_type">
                <div class="btn-group">
                    @if($selling_price_group_count)
                        <button type="submit" value="submit_n_add_selling_prices" class="tw-dw-btn tw-dw-btn-warning tw-text-white tw-dw-btn-lg submit_product_form">@lang('lang_v1.save_n_add_selling_price_group_prices')</button>
                    @endif

                    @can('product.opening_stock')
                        <button type="submit" @if(empty($product->enable_stock)) disabled="true" @endif id="opening_stock_button" value="update_n_edit_opening_stock" class="tw-dw-btn tw-text-white tw-dw-btn-lg bg-purple submit_product_form">@lang('lang_v1.update_n_edit_opening_stock')</button>
                    @endif

                    <button type="submit" value="save_n_add_another" class="tw-dw-btn tw-text-white tw-dw-btn-lg bg-maroon submit_product_form">@lang('lang_v1.update_n_add_another')</button>

                    <button type="submit" value="submit" class="tw-dw-btn tw-dw-btn-primary tw-text-white tw-dw-btn-lg submit_product_form">@lang('messages.update')</button>
                </div>
            </div>
        </div>
    </div>

{!! Form::close() !!}
</section>
<!-- /.content -->

@endsection

@section('javascript')
  <script src="{{ asset('js/product.js?v=' . $asset_v) }}"></script>
  <script type="text/javascript">
    $(document).ready( function(){
      __page_leave_confirmation('#product_add_form');
    });
  </script>
@endsection