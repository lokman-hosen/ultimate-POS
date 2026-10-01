@extends('layouts.app')

@section('title', __('sale.pos_sale'))

@section('content')
<section class="content no-print">
	<input type="hidden" id="amount_rounding_method" value="{{$pos_settings['amount_rounding_method'] ?? ''}}">
	@if(!empty($pos_settings['allow_overselling']))
		<input type="hidden" id="is_overselling_allowed">
	@endif
	@if(session('business.enable_rp') == 1)
        <input type="hidden" id="reward_point_enabled">
    @endif
    @php
		$is_discount_enabled = $pos_settings['disable_discount'] != 1 ? true : false;
		$is_rp_enabled = session('business.enable_rp') == 1 ? true : false;
	@endphp
	{!! Form::open(['url' => action([\App\Http\Controllers\SellPosController::class, 'update'], [$transaction->id]), 'method' => 'post', 'id' => 'edit_pos_sell_form' ]) !!}
	{{ method_field('PUT') }}
	<div class="row" style="margin:0;">
		<div class="col-md-12" style="padding:0;">
			<div class="row tw-flex lg:tw-flex-row md:tw-flex-col sm:tw-flex-col tw-flex-col tw-items-stretch" style="gap: 6px; margin: 0; padding: 0;">
				@php
					$is_restaurant = in_array('tables', $enabled_modules) || in_array('types_of_service', $enabled_modules);
				@endphp

				@if($is_restaurant)
					<div class="pos-restaurant-section tw-w-full" style="padding:0; min-width:0;">
						@include('sale_pos.partials.restaurant_sidebar')
					</div>
				@endif

				@if(empty($pos_settings['hide_product_suggestion']))
					<div class="pos-left-product-section tw-w-full" style="padding:0; min-width:0;" id="pos_sidebar_wrap">
						@include('sale_pos.partials.pos_sidebar')
					</div>
				@endif

				<div class="pos-right-cart-section tw-w-full @if(!empty($pos_settings['hide_product_suggestion'])) pos-cart-full-width @endif" style="padding:0; min-width:0;">
					<div class="tw-shadow-[rgba(17,_17,_26,_0.08)_0px_0px_16px] tw-rounded-2xl tw-bg-white tw-border tw-border-slate-100" style="padding:0;overflow:hidden;height:100%;">
						<div class="box-body pb-0">
							{!! Form::hidden('location_id', $transaction->location_id, ['id' => 'location_id', 'data-receipt_printer_type' => !empty($location_printer_type) ? $location_printer_type : 'browser', 'data-default_payment_accounts' => $transaction->location->default_payment_accounts]); !!}
							<!-- sub_type -->
							{!! Form::hidden('sub_type', isset($sub_type) ? $sub_type : null) !!}
							<input type="hidden" id="item_addition_method" value="{{$business_details->item_addition_method}}">
								<div class="tw-relative tw-flex-1 tw-flex tw-flex-col tw-min-h-0">
									@include('sale_pos.partials.pos_form_edit')
								</div>

								@include('sale_pos.partials.pos_form_totals', ['edit' => true])

								@include('sale_pos.partials.payment_modal')

								@if(empty($pos_settings['disable_suspend']))
									@include('sale_pos.partials.suspend_note_modal')
								@endif

								@if(empty($pos_settings['disable_recurring_invoice']))
									@include('sale_pos.partials.recurring_invoice_modal')
								@endif
							</div>

						</div>
					</div>
			</div>
		</div>
	</div>
	@include('sale_pos.partials.pos_form_actions', ['edit' => true])
	{!! Form::close() !!}
</section>

<!-- This will be printed -->
<section class="invoice print_section" id="receipt_section">
</section>
<div class="modal fade contact_modal" tabindex="-1" role="dialog" aria-labelledby="gridSystemModalLabel">
	@include('contact.create', ['quick_add' => true])
</div>
@if(empty($pos_settings['hide_product_suggestion']) && isMobile())
	@include('sale_pos.partials.mobile_product_suggestions')
@endif
<!-- /.content -->
<div class="modal fade register_details_modal" tabindex="-1" role="dialog" 
	aria-labelledby="gridSystemModalLabel">
</div>
<div class="modal fade close_register_modal" tabindex="-1" role="dialog" 
	aria-labelledby="gridSystemModalLabel">
</div>
<!-- quick product modal -->
<div class="modal fade quick_add_product_modal" tabindex="-1" role="dialog" aria-labelledby="modalTitle"></div>

@include('sale_pos.partials.configure_search_modal')

@include('sale_pos.partials.recent_transactions_modal')

@include('sale_pos.partials.weighing_scale_modal')

<div class="modal fade" id="expense_modal" tabindex="-1" role="dialog" aria-labelledby="gridSystemModalLabel">
</div>
<div class="modal fade" id="pos_pay_contact_due_modal" tabindex="-1" role="dialog">
</div>

@stop

@section('javascript')
	<script src="{{ asset('js/pos.js?v=' . $asset_v) }}"></script>
	<script src="{{ asset('js/printer.js?v=' . $asset_v) }}"></script>
	<script src="{{ asset('js/product.js?v=' . $asset_v) }}"></script>
	<script src="{{ asset('js/opening_stock.js?v=' . $asset_v) }}"></script>
	@include('sale_pos.partials.keyboard_shortcuts')

	<!-- Call restaurant module if defined -->
    @if(in_array('tables' ,$enabled_modules) || in_array('modifiers' ,$enabled_modules) || in_array('service_staff' ,$enabled_modules))
    	<script src="{{ asset('js/restaurant.js?v=' . $asset_v) }}"></script>
    @endif

    <!-- include module js -->
    @if(!empty($pos_module_data))
	    @foreach($pos_module_data as $key => $value)
            @if(!empty($value['module_js_path']))
                @includeIf($value['module_js_path'], ['view_data' => $value['view_data']])
            @endif
	    @endforeach
	@endif

	@include('sale_pos.partials.pos_layout_script', ['form_id' => 'edit_pos_sell_form'])

@endsection

@section('css')
	<style type="text/css">
		@media (min-width: 992px) {
			@if(!empty($is_restaurant))
			.pos-restaurant-section {
				width: 22% !important;
				flex: 0 0 22% !important;
				max-width: 22% !important;
				min-width: 0 !important;
				height: calc(100vh - 118px) !important;
			}
			.pos-left-product-section {
				width: 48% !important;
				flex: 0 0 48% !important;
				max-width: 48% !important;
				min-width: 0 !important;
				height: calc(100vh - 118px) !important;
			}
			/* Force 3 columns for products in restaurant mode */
			.pos-left-product-section .pos-card-col-5,
			.pos-left-product-section .col-md-3 {
				width: 33.333333% !important;
			}
			@else
			.pos-left-product-section {
				width: 70% !important;
				flex: 0 0 70% !important;
				max-width: 70% !important;
				min-width: 0 !important;
				height: calc(100vh - 118px) !important;
			}
			@endif
			.pos-right-cart-section {
				width: 30% !important;
				flex: 0 0 30% !important;
				max-width: 30% !important;
				min-width: 0 !important;
				height: calc(100vh - 118px) !important;
			}
			.pos-right-cart-section.pos-cart-full-width {
				width: 100% !important;
				flex: 0 0 100% !important;
				max-width: 100% !important;
			}
			.pos-right-cart-section > div {
				height: 100% !important;
				display: flex !important;
				flex-direction: column !important;
			}
			.pos-right-cart-section .box-body {
				height: 100% !important;
				display: flex !important;
				flex-direction: column !important;
				padding: 0 !important;
				flex: 1 1 auto !important;
				min-height: 0 !important;
				overflow: hidden !important;
			}
			.pos-cart-top-fields {
				flex-shrink: 0 !important;
				padding: 6px 6px 0 6px !important;
			}
			.pos-cart-table-row {
				flex: 1 1 0 !important;
				min-height: 0 !important;
				overflow: hidden !important;
				margin: 0 !important;
				display: flex !important;
				flex-direction: column !important;
			}
			.pos_product_div {
				flex: 1 1 0 !important;
				height: 100% !important;
				max-height: 100% !important;
				min-height: 80px !important;
				overflow-y: auto !important;
				overflow-x: hidden !important;
				padding: 0 4px !important;
			}
			.pos_form_totals {
				flex-shrink: 0 !important;
				margin-top: auto !important;
				width: 100% !important;
			}
		}
		@media (max-width: 991px) {
			section.content {
				padding-bottom: 58px !important;
			}
			.pos-restaurant-section,
			.pos-left-product-section,
			.pos-right-cart-section {
				width: 100% !important;
				flex: 0 0 100% !important;
				max-width: 100% !important;
				min-width: 0 !important;
				height: auto !important;
			}
			.pos_product_div {
				max-height: 320px !important;
				overflow-y: auto !important;
			}
		}
		/*CSS to print receipts*/
		.print_section{
		    display: none;
		}
		@media print{
		    .print_section{
		        display: block !important;
		    }
		}
		@page {
		    size: 3.1in auto;/* width height */
		    height: auto !important;
		    margin-top: 0mm;
		    margin-bottom: 0mm;
		}
		.overlay {
			background: rgba(255,255,255,0) !important;
			cursor: not-allowed;
		}
	</style>
	<!-- include module css -->
    @if(!empty($pos_module_data))
        @foreach($pos_module_data as $key => $value)
            @if(!empty($value['module_css_path']))
                @includeIf($value['module_css_path'])
            @endif
        @endforeach
    @endif
@endsection