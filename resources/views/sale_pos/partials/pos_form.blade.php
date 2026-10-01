<div class="pos-cart-top-fields">
<div class="row" style="margin: 0 -4px;">
	<div class="col-xs-12 col-md-12" style="padding: 0 4px;">
		<div class="form-group" style="margin-bottom: 6px;">
			<div class="input-group">
				<span class="input-group-addon !tw-bg-slate-50 !tw-border-slate-200">
					<i class="fa fa-user tw-text-slate-500"></i>
				</span>
				<input type="hidden" id="default_customer_id" 
				value="{{ $walk_in_customer['id'] ?? ''}}" >
				<input type="hidden" id="default_customer_name" 
				value="{{ $walk_in_customer['name'] ?? ''}}" >
				<input type="hidden" id="default_customer_balance" 
				value="{{ $walk_in_customer['balance'] ?? ''}}" >
				<input type="hidden" id="default_customer_address" 
				value="{{ $walk_in_customer['shipping_address'] ?? ''}}" >
				@if(!empty($walk_in_customer['price_calculation_type']) && $walk_in_customer['price_calculation_type'] == 'selling_price_group')
					<input type="hidden" id="default_selling_price_group" 
				value="{{ $walk_in_customer['selling_price_group_id'] ?? ''}}" >
				@endif
				{!! Form::select('contact_id', 
					[], null, ['class' => 'form-control mousetrap !tw-border-slate-200', 'id' => 'customer_id', 'placeholder' => __('lang_v1.enter_customer_name_phone'), 'required']); !!}
				<span class="input-group-btn">
					<button type="button" class="btn btn-default bg-white btn-flat !tw-border-slate-200 add_new_customer" data-name=""  @if(!auth()->user()->can('customer.create')) disabled @endif><i class="fa fa-plus-circle text-primary fa-lg"></i></button>
					@can('sell.payments')
					<button type="button" id="pos-receive-customer-payment" class="btn btn-default bg-white btn-flat !tw-border-slate-200" title="@lang('lang_v1.receive_payment')"><i class="fas fa-hand-holding-usd text-primary fa-lg"></i></button>
					@endcan
				</span>
			</div>
			<small class="text-danger hide contact_due_text"><strong>@lang('account.customer_due'):</strong> <span></span></small>
		</div>
	</div>
	<div class="col-xs-12 col-md-12" style="padding: 0 4px;">
		<div class="form-group" style="margin-bottom: 6px;">
			<div class="input-group">
				<div class="input-group-btn">
					<button type="button" class="btn btn-default bg-white btn-flat !tw-border-slate-200" data-toggle="modal" data-target="#configure_search_modal" title="{{__('lang_v1.configure_product_search')}}"><i class="fas fa-search-plus tw-text-slate-500"></i></button>
				</div>
                {{-- Removed mousetrap class as it was causing issue with barcode scanning --}}
				{!! Form::text('search_product', null, ['class' => 'form-control !tw-border-slate-200', 'id' => 'search_product', 'placeholder' => __('lang_v1.search_product_placeholder'),
				'disabled' => is_null($default_location)? true : false,
				'autofocus' => is_null($default_location)? false : true,
				]); !!}
				<span class="input-group-btn">

					<!-- Show button for weighing scale modal -->
					@if(isset($pos_settings['enable_weighing_scale']) && $pos_settings['enable_weighing_scale'] == 1)
						<button type="button" class="btn btn-default bg-white btn-flat !tw-border-slate-200" id="weighing_scale_btn" data-toggle="modal" data-target="#weighing_scale_modal" 
						title="@lang('lang_v1.weighing_scale')"><i class="fa fa-digital-tachograph text-primary fa-lg"></i></button>
					@endif
					

					<button type="button" class="btn btn-default bg-white btn-flat pos_add_quick_product !tw-border-slate-200" data-href="{{action([\App\Http\Controllers\ProductController::class, 'quickAdd'])}}" data-container=".quick_add_product_modal"><i class="fa fa-plus-circle text-primary fa-lg"></i></button>
				</span>
			</div>
		</div>
	</div>
</div>
<div class="row">
	@if(!empty($pos_settings['show_invoice_layout']))
	<div class="col-md-4">
		<div class="form-group">
		{!! Form::select('invoice_layout_id', 
					$invoice_layouts, $default_location->invoice_layout_id, ['class' => 'form-control select2', 'placeholder' => __('lang_v1.select_invoice_layout'), 'id' => 'invoice_layout_id']); !!}
		</div>
	</div>
	@endif
	<input type="hidden" name="pay_term_number" id="pay_term_number" value="{{$walk_in_customer['pay_term_number'] ?? ''}}">
	<input type="hidden" name="pay_term_type" id="pay_term_type" value="{{$walk_in_customer['pay_term_type'] ?? ''}}">
	
	@if(!empty($commission_agent))
		@php
			$is_commission_agent_required = !empty($pos_settings['is_commission_agent_required']);
		@endphp
		<div class="col-md-4">
			<div class="form-group">
			{!! Form::select('commission_agent', 
						$commission_agent, null, ['class' => 'form-control select2', 'placeholder' => __('lang_v1.commission_agent'), 'id' => 'commission_agent', 'required' => $is_commission_agent_required]); !!}
			</div>
		</div>
	@endif
	@if(!empty($pos_settings['enable_transaction_date']))
		<div class="col-md-4 col-sm-6">
			<div class="form-group">
				<div class="input-group">
					<span class="input-group-addon">
						<i class="fa fa-calendar"></i>
					</span>
					{!! Form::text('transaction_date', $default_datetime, ['class' => 'form-control', 'readonly', 'required', 'id' => 'transaction_date']); !!}
				</div>
			</div>
		</div>
	@endif
	@if(config('constants.enable_sell_in_diff_currency') == true)
		<div class="col-md-4 col-sm-6">
			<div class="form-group">
				<div class="input-group">
					<span class="input-group-addon">
						<i class="fas fa-exchange-alt"></i>
					</span>
					{!! Form::text('exchange_rate', config('constants.currency_exchange_rate'), ['class' => 'form-control input-sm input_number', 'placeholder' => __('lang_v1.currency_exchange_rate'), 'id' => 'exchange_rate']); !!}
				</div>
			</div>
		</div>
	@endif
	@if(!empty($price_groups) && count($price_groups) > 1)
		<div class="col-md-4 col-sm-6">
			<div class="form-group">
				<div class="input-group">
					<span class="input-group-addon">
						<i class="fas fa-money-bill-alt"></i>
					</span>
					@php
						reset($price_groups);
						$selected_price_group = !empty($default_price_group_id) && array_key_exists($default_price_group_id, $price_groups) ? $default_price_group_id : null;
					@endphp
					{!! Form::hidden('hidden_price_group', key($price_groups), ['id' => 'hidden_price_group']) !!}
					{!! Form::select('price_group', $price_groups, $selected_price_group, ['class' => 'form-control select2', 'id' => 'price_group']); !!}
					<span class="input-group-addon">
						@show_tooltip(__('lang_v1.price_group_help_text'))
					</span> 
				</div>
			</div>
		</div>
	@else
		@php
			reset($price_groups);
		@endphp
		{!! Form::hidden('price_group', key($price_groups), ['id' => 'price_group']) !!}
	@endif
	@if(!empty($default_price_group_id))
		{!! Form::hidden('default_price_group', $default_price_group_id, ['id' => 'default_price_group']) !!}
	@endif

	@if(in_array('types_of_service', $enabled_modules) && !empty($types_of_service))
		<div class="col-md-4 col-sm-6">
			<div class="form-group">
				<div class="input-group">
					<span class="input-group-addon">
						<i class="fa fa-external-link-square-alt text-primary service_modal_btn"></i>
					</span>
					{!! Form::select('types_of_service_id', $types_of_service, null, ['class' => 'form-control', 'id' => 'types_of_service_id', 'style' => 'width: 100%;', 'placeholder' => __('lang_v1.select_types_of_service')]); !!}

					{!! Form::hidden('types_of_service_price_group', null, ['id' => 'types_of_service_price_group']) !!}

					<span class="input-group-addon">
						@show_tooltip(__('lang_v1.types_of_service_help'))
					</span> 
				</div>
				<small><p class="help-block hide" id="price_group_text">@lang('lang_v1.price_group'): <span></span></p></small>
			</div>
		</div>
		<div class="modal fade types_of_service_modal" tabindex="-1" role="dialog" aria-labelledby="gridSystemModalLabel"></div>
	@endif

	@if(!empty($pos_settings['show_invoice_scheme']))
		@php
			$invoice_scheme_id = $default_invoice_schemes->id;
			if(!empty($default_location->invoice_scheme_id)) {
				$invoice_scheme_id = $default_location->invoice_scheme_id;
			}
		@endphp
		<div class="col-md-4 col-sm-6">
			<div class="form-group">
				{!! Form::select('invoice_scheme_id', $invoice_schemes, $invoice_scheme_id, 
					['class' => 'form-control', 'placeholder' => __('lang_v1.select_invoice_scheme'), 
					'id' => 'invoice_scheme_id']); !!}
			</div>
		</div>
	@endif
	@if(in_array('subscription', $enabled_modules))
		<div class="col-md-4 col-sm-6">
			<label>
              {!! Form::checkbox('is_recurring', 1, false, ['class' => 'input-icheck', 'id' => 'is_recurring']); !!} @lang('lang_v1.subscribe')?
            </label><button type="button" data-toggle="modal" data-target="#recurringInvoiceModal" class="btn btn-link"><i class="fa fa-external-link-square-alt"></i></button>@show_tooltip(__('lang_v1.recurring_invoice_help'))
		</div>
	@endif
	
	<!-- Call restaurant module if defined -->
    @if(in_array('tables' ,$enabled_modules) || in_array('service_staff' ,$enabled_modules))
    	<span id="restaurant_module_span" style="display: contents;">
      		<div class="col-xs-6 col-sm-6 col-md-6" style="padding-left: 4px; padding-right: 4px;"></div>
    	</span>
    @endif

	@if(in_array('kitchen' ,$enabled_modules))
		<div class="col-xs-6 col-sm-6 col-md-6 tw-flex tw-items-center" style="min-height: 32px; margin-bottom: 8px; padding-left: 4px; padding-right: 4px;">
			<label class="tw-inline-flex tw-items-center tw-cursor-pointer tw-font-medium tw-text-xs md:tw-text-sm tw-text-slate-700 tw-mb-0" style="margin-bottom: 0; font-weight: 500; cursor: pointer; display: inline-flex; align-items: center; white-space: nowrap; max-width: 100%; overflow: hidden;">
				{!! Form::checkbox('is_kitchen_order', 1, false, ['class' => 'input-icheck status', 'id' => 'is_kitchen_order']); !!}
				<span style="margin-left: 5px; font-size: 12px; white-space: nowrap;">{{ __('lang_v1.kitchen_order') }}</span>
				<span style="margin-left: 3px; font-size: 11px;">@show_tooltip(__('lang_v1.kitchen_order_tooltip'))</span>
			</label>
		</div>
    @endif
</div>
<!-- include module fields -->
@if(!empty($pos_module_data))
    @foreach($pos_module_data as $key => $value)
        @if(!empty($value['view_path']))
            @includeIf($value['view_path'], ['view_data' => $value['view_data']])
        @endif
    @endforeach
@endif
</div>
<div class="row pos-cart-table-row" style="margin:0;">
	<div class="col-sm-12 pos_product_div" style="padding:4px 0 0 0;">
		<input type="hidden" name="sell_price_tax" id="sell_price_tax" value="{{$business_details->sell_price_tax}}">

		<!-- Keeps count of product rows -->
		<input type="hidden" id="product_row_count" 
			value="0">
		@php
			$hide_tax = '';
			if( session()->get('business.enable_inline_tax') == 0){
				$hide_tax = 'hide';
			}
			$has_tax = empty($hide_tax);
			$has_inline_staff = !empty($pos_settings['inline_service_staff']);
		@endphp
		<table class="table table-condensed" id="pos_table" style="table-layout: fixed !important; width: 100% !important; margin-bottom: 0 !important;">
			<colgroup>
				@if(!$has_tax && !$has_inline_staff)
					<col style="width: 44% !important;">
					<col style="width: 30% !important;">
					<col style="width: 26% !important;">
					<col style="width: 26px !important;">
				@elseif($has_inline_staff && !$has_tax)
					<col style="width: 38% !important;">
					<col style="width: 26% !important;">
					<col style="width: 15% !important;">
					<col style="width: 21% !important;">
					<col style="width: 26px !important;">
				@elseif(!$has_inline_staff && $has_tax)
					<col style="width: 36% !important;">
					<col style="width: 24% !important;">
					<col style="width: 18% !important;">
					<col style="width: 22% !important;">
					<col style="width: 26px !important;">
				@else
					<col style="width: 32% !important;">
					<col style="width: 22% !important;">
					<col style="width: 14% !important;">
					<col style="width: 14% !important;">
					<col style="width: 18% !important;">
					<col style="width: 26px !important;">
				@endif
			</colgroup>
			<thead>
				<tr>
					<th class="text-left pos-th-product tw-sticky tw-top-0 tw-z-10 !tw-bg-[#f8fafc] !tw-text-[#94a3b8] !tw-border-b !tw-border-[#e2e8f0] !tw-border-t-0 !tw-border-l-0 !tw-border-r-0 !tw-px-1.5 !tw-py-2 !tw-text-[10px] !tw-font-medium tw-uppercase tw-tracking-[0.4px] !tw-leading-none tw-whitespace-nowrap tw-overflow-hidden !tw-align-middle">
						@lang('sale.product') @show_tooltip(__('lang_v1.tooltip_sell_product_column'))
					</th>
					<th class="text-center pos-th-qty tw-sticky tw-top-0 tw-z-10 !tw-bg-[#f8fafc] !tw-text-[#94a3b8] !tw-border-b !tw-border-[#e2e8f0] !tw-border-t-0 !tw-border-l-0 !tw-border-r-0 !tw-px-0.5 !tw-py-2 !tw-text-[10px] !tw-font-medium tw-uppercase tw-tracking-[0.4px] !tw-leading-none tw-whitespace-nowrap tw-overflow-hidden !tw-align-middle">
						@lang('sale.qty')
					</th>
					@if(!empty($pos_settings['inline_service_staff']))
						<th class="text-center pos-th-staff tw-sticky tw-top-0 tw-z-10 !tw-bg-[#f8fafc] !tw-text-[#94a3b8] !tw-border-b !tw-border-[#e2e8f0] !tw-border-t-0 !tw-border-l-0 !tw-border-r-0 !tw-px-0.5 !tw-py-2 !tw-text-[10px] !tw-font-medium tw-uppercase tw-tracking-[0.4px] !tw-leading-none tw-whitespace-nowrap tw-overflow-hidden !tw-align-middle">
							@lang('restaurant.service_staff')
						</th>
					@endif
					<th class="text-right pos-th-price tw-sticky tw-top-0 tw-z-10 !tw-bg-[#f8fafc] !tw-text-[#94a3b8] !tw-border-b !tw-border-[#e2e8f0] !tw-border-t-0 !tw-border-l-0 !tw-border-r-0 !tw-px-0.5 !tw-py-2 !tw-text-[10px] !tw-font-medium tw-uppercase tw-tracking-[0.4px] !tw-leading-none tw-whitespace-nowrap tw-overflow-hidden !tw-align-middle {{$hide_tax}}" style="text-overflow: ellipsis;">
						@lang('sale.price_inc_tax')
					</th>
					<th class="text-right pos-th-subtotal tw-sticky tw-top-0 tw-z-10 !tw-bg-[#f8fafc] !tw-text-[#94a3b8] !tw-border-b !tw-border-[#e2e8f0] !tw-border-t-0 !tw-border-l-0 !tw-border-r-0 !tw-px-0.5 !tw-py-2 !tw-text-[10px] !tw-font-medium tw-uppercase tw-tracking-[0.4px] !tw-leading-none tw-whitespace-nowrap tw-overflow-hidden !tw-align-middle">
						@lang('sale.subtotal')
					</th>
					<th class="pos-th-action tw-sticky tw-top-0 tw-z-10 !tw-bg-[#f8fafc] !tw-border-b !tw-border-[#e2e8f0] !tw-border-t-0 !tw-border-l-0 !tw-border-r-0 !tw-py-2 !tw-px-0 !tw-text-[10px] !tw-font-medium tw-uppercase tw-tracking-[0.4px] !tw-leading-none tw-whitespace-nowrap tw-overflow-hidden !tw-align-middle !tw-text-center" style="width: 26px !important; padding: 0 2px !important;"></th>
				</tr>
			</thead>
			<tbody>
				<tr class="pos-empty-state-row">
					<td colspan="100" class="!tw-border-0 !tw-p-0">
						<div class="tw-flex tw-flex-col tw-items-center tw-justify-center tw-text-center tw-py-10 md:tw-py-14 tw-px-6 tw-gap-3">
							<div class="tw-w-16 tw-h-16 tw-rounded-full tw-bg-slate-100 tw-flex tw-items-center tw-justify-center tw-text-slate-400">
								<svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M6 19m-2 0a2 2 0 1 0 4 0a2 2 0 1 0 -4 0"/><path d="M17 19m-2 0a2 2 0 1 0 4 0a2 2 0 1 0 -4 0"/><path d="M17 17h-11v-14h-2"/><path d="M6 5l14 1l-1 7h-13"/></svg>
							</div>
							<div class="tw-text-[15px] tw-font-semibold tw-text-slate-600">@lang('lang_v1.cart_is_empty')</div>
							<div class="tw-text-[13px] tw-text-slate-400 tw-leading-relaxed tw-max-w-sm">@lang('lang_v1.cart_is_empty_hint')</div>
						</div>
					</td>
				</tr>
			</tbody>
		</table>
		<style>
			.pos_product_div {
				overflow-y: auto !important;
				overflow-x: hidden !important;
				max-height: calc(100vh - 280px) !important;
				min-height: 140px;
			}
			#pos_table {
				table-layout: fixed !important;
				width: 100% !important;
				border-collapse: collapse !important;
			}
			#pos_table th, #pos_table td {
				box-sizing: border-box !important;
			}
			#pos_table th {
				font-size: 10px !important;
			}
			#pos_table th.pos-th-product, #pos_table td.pos-td-product {
				overflow: hidden !important;
			}
			#pos_table th.pos-th-qty, #pos_table td.pos-td-qty {
				position: relative !important;
				text-align: center !important;
				padding-left: 1px !important;
				padding-right: 1px !important;
			}
			#pos_table th.pos-th-price, #pos_table td.pos-td-price {
				text-align: right !important;
				padding-left: 2px !important;
				padding-right: 2px !important;
			}
			#pos_table th.pos-th-subtotal, #pos_table td.pos-td-subtotal {
				text-align: right !important;
				padding-left: 2px !important;
				padding-right: 4px !important;
			}
			#pos_table th.pos-th-action, #pos_table td.pos-td-action {
				width: 26px !important;
				max-width: 26px !important;
				padding: 0 2px !important;
				text-align: center !important;
			}

			#pos_table:not(.pos-has-rows) thead { display: none !important; }
			#pos_table.pos-has-rows .pos-empty-state-row { display: none !important; }
			#add_pos_sell_form:not(.pos-has-rows) .pos_form_totals,
			#edit_pos_sell_form:not(.pos-has-rows) .pos_form_totals { display: none !important; }

			#pos_table tbody tr.product_row {
				background: #ffffff;
				transition: background-color 0.15s ease;
			}
			#pos_table tbody tr.product_row:hover {
				background: #f8fafc;
			}
			#pos_table tbody tr.product_row td {
				padding: 5px 2px !important;
				vertical-align: middle !important;
				border-top: none !important;
				border-bottom: 1px solid #f1f5f9 !important;
			}

			.pos-qty-stepper {
				display: inline-flex !important;
				align-items: center !important;
				justify-content: center !important;
				background: #f1f5f9 !important;
				border: 1px solid #e2e8f0 !important;
				border-radius: 5px !important;
				padding: 1px !important;
				width: auto !important;
				max-width: 100% !important;
				box-sizing: border-box !important;
				position: relative !important;
				overflow: visible !important;
			}

			.pos-row-qty-error-target {
				min-height: 0;
			}
			.pos-row-qty-error-target label.error,
			#pos_table label.error {
				display: inline-flex !important;
				align-items: center !important;
				gap: 4px !important;
				background: #fef2f2 !important;
				color: #dc2626 !important;
				border: 1px solid #fecaca !important;
				border-radius: 4px !important;
				padding: 2px 7px !important;
				font-size: 11px !important;
				font-weight: 600 !important;
				line-height: 1.3 !important;
				margin-top: 4px !important;
				margin-bottom: 0 !important;
				white-space: normal !important;
				word-break: break-word !important;
				box-shadow: none !important;
				position: static !important;
				transform: none !important;
				width: auto !important;
				max-width: 100% !important;
				animation: posTooltipFade 0.15s ease-out !important;
			}
			.pos-row-qty-error-target label.error::before,
			#pos_table label.error::before {
				content: '⚠' !important;
				font-size: 11px !important;
				color: #ef4444 !important;
				line-height: 1 !important;
			}
			.pos-row-qty-error-target label.error:empty,
			.pos-row-qty-error-target label.error.valid,
			.pos-row-qty-error-target label.error[style*="display: none"],
			.pos-row-qty-error-target label.error[style*="display:none"],
			#pos_table label.error:empty,
			#pos_table label.error.valid,
			#pos_table label.error[style*="display: none"],
			#pos_table label.error[style*="display:none"] {
				display: none !important;
				visibility: hidden !important;
				padding: 0 !important;
				margin: 0 !important;
				border: none !important;
			}
			.pos-row-qty-error-target label.error:empty::before,
			.pos-row-qty-error-target label.error.valid::before,
			.pos-row-qty-error-target label.error[style*="display: none"]::before,
			.pos-row-qty-error-target label.error[style*="display:none"]::before,
			#pos_table label.error:empty::before,
			#pos_table label.error.valid::before,
			#pos_table label.error[style*="display: none"]::before,
			#pos_table label.error[style*="display:none"]::before {
				content: '' !important;
				display: none !important;
			}
			.pos-row-qty-error-target label.error::after,
			#pos_table label.error::after {
				display: none !important;
			}
			@keyframes posTooltipFade {
				from {
					opacity: 0;
					transform: translateY(-2px);
				}
				to {
					opacity: 1;
					transform: translateY(0);
				}
			}
			.pos-qty-btn {
				width: 18px !important;
				height: 18px !important;
				min-width: 18px !important;
				border: none !important;
				background: #2563eb !important;
				color: #ffffff !important;
				border-radius: 3px !important;
				display: inline-flex !important;
				align-items: center !important;
				justify-content: center !important;
				padding: 0 !important;
				cursor: pointer !important;
				box-shadow: 0 1px 2px rgba(37,99,235,0.2) !important;
				transition: all 0.15s ease !important;
				flex-shrink: 0 !important;
			}
			.pos-qty-btn svg {
				stroke: #ffffff !important;
				width: 10px !important;
				height: 10px !important;
			}
			.pos-qty-btn:hover {
				background: #1d4ed8 !important;
				color: #ffffff !important;
			}
			.pos-qty-btn:hover svg {
				stroke: #ffffff !important;
			}
			.pos-qty-btn.quantity-down:hover {
				background: #1d4ed8 !important;
			}
			.pos-qty-btn.quantity-up:hover {
				background: #1d4ed8 !important;
			}
			.pos-qty-input {
				width: 26px !important;
				min-width: 18px !important;
				max-width: 34px !important;
				height: 18px !important;
				padding: 0 !important;
				background: transparent !important;
				border: none !important;
				box-shadow: none !important;
				text-align: center !important;
				font-size: 11.5px !important;
				font-weight: 700 !important;
				color: #0f172a !important;
				line-height: 18px !important;
			}
			.pos-qty-input:focus {
				background: #ffffff !important;
				border-radius: 3px !important;
				box-shadow: 0 0 0 1px #2563eb !important;
				outline: none !important;
			}
			.pos-price-input {
				width: 100% !important;
				max-width: 54px !important;
				height: 22px !important;
				padding: 1px 3px !important;
				font-size: 11.5px !important;
				font-weight: 600 !important;
				color: #334155 !important;
				text-align: right !important;
				background: #f8fafc !important;
				border: 1px solid #e2e8f0 !important;
				border-radius: 4px !important;
				transition: all 0.15s ease !important;
			}
			.pos-price-input:hover {
				background: #ffffff !important;
				border-color: #cbd5e1 !important;
			}
			.pos-price-input:focus {
				background: #ffffff !important;
				border-color: #2563eb !important;
				box-shadow: 0 0 0 2px rgba(37, 99, 235, 0.15) !important;
				outline: none !important;
			}
			.pos-price-input[readonly] {
				background: transparent !important;
				border-color: transparent !important;
				box-shadow: none !important;
				cursor: default !important;
				color: #475569 !important;
				font-weight: 600 !important;
			}
			.pos_line_total_text {
				font-size: 12px !important;
				font-weight: 700 !important;
				white-space: nowrap !important;
				display: inline-block !important;
				color: #0f172a !important;
			}
		</style>
	</div>
</div>