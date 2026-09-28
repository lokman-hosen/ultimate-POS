<div class="pos-cart-top-fields">
<div class="row">
	<div class="col-md-12">
		<p><strong>@lang('sale.invoice_no'):</strong> {{$transaction->invoice_no}}</p>
	</div>
	<div class="col-xs-12 col-md-12" style="padding: 0 4px;">
		<div class="form-group" style="width: 100% !important; margin-bottom: 6px;">
			<div class="input-group">
				<span class="input-group-addon !tw-bg-slate-50 !tw-border-slate-200">
					<i class="fa fa-user tw-text-slate-500"></i>
				</span>
				<input type="hidden" id="default_customer_id" 
				value="{{ $transaction->contact->id }}" >
				<input type="hidden" id="default_customer_name" 
				value="{{ $transaction->contact->name }}" >
				<input type="hidden" id="default_customer_balance" 
				value="{{$transaction->contact->balance}}" >
				{!! Form::select('contact_id', 
					[], null, ['class' => 'form-control mousetrap !tw-border-slate-200', 'id' => 'customer_id', 'placeholder' => 'Enter Customer name / phone', 'required', 'style' => 'width: 100%;']); !!}
				<span class="input-group-btn">
					<button type="button" class="btn btn-default bg-white btn-flat !tw-border-slate-200 add_new_customer" data-name=""  @if(!auth()->user()->can('customer.create')) disabled @endif><i class="fa fa-plus-circle text-primary fa-lg"></i></button>
				</span>
			</div>
			<small class="text-danger @if(empty($customer_due)) hide @endif contact_due_text"><strong>@lang('account.customer_due'):</strong> <span>{{$customer_due ?? ''}}</span></small>
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
				'autofocus' => true,
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
					$invoice_layouts, $transaction->location->invoice_layout_id, ['class' => 'form-control select2', 'placeholder' => __('lang_v1.select_invoice_layout'), 'id' => 'invoice_layout_id']); !!}
		</div>
	</div>
	@endif
	<input type="hidden" name="pay_term_number" id="pay_term_number" value="{{$transaction->pay_term_number}}">
	<input type="hidden" name="pay_term_type" id="pay_term_type" value="{{$transaction->pay_term_type}}">
	
	@if(!empty($commission_agent))
		@php
			$is_commission_agent_required = !empty($pos_settings['is_commission_agent_required']);
		@endphp
		<div class="col-sm-4">
			<div class="form-group">
			{!! Form::select('commission_agent', 
						$commission_agent, $transaction->commission_agent, ['class' => 'form-control select2', 'placeholder' => __('lang_v1.commission_agent'), 'id' => 'commission_agent', 'required' => $is_commission_agent_required]); !!}
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
					{!! Form::text('transaction_date', @format_datetime($transaction->transaction_date), ['class' => 'form-control', 'readonly', 'required', 'id' => 'transaction_date']); !!}
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
					{!! Form::text('exchange_rate', @num_format($transaction->exchange_rate), ['class' => 'form-control input-sm input_number', 'placeholder' => __('lang_v1.currency_exchange_rate'), 'id' => 'exchange_rate']); !!}
				</div>
			</div>
		</div>
	@endif
	@if(!empty($transaction->selling_price_group_id))
		<div class="col-md-4 col-sm-6">
			<div class="form-group">
				<div class="input-group">
					<span class="input-group-addon">
						<i class="fas fa-money-bill-alt"></i>
					</span>
					{!! Form::hidden('price_group', $transaction->selling_price_group_id, ['id' => 'price_group']) !!}
					{!! Form::text('price_group_text', $transaction->price_group->name, ['class' => 'form-control', 'readonly']); !!}
					<span class="input-group-addon">
					@show_tooltip(__('lang_v1.price_group_help_text'))
				</span> 
				</div>
			</div>
		</div>
	@endif

	@if(in_array('types_of_service', $enabled_modules) && !empty($transaction->types_of_service))
		<div class="col-md-4 col-sm-6">
			<div class="form-group">
				<div class="input-group">
					<span class="input-group-addon">
						<i class="fas fa-external-link-square-alt text-primary service_modal_btn"></i>
					</span>
					{!! Form::text('types_of_service_text', $transaction->types_of_service->name, ['class' => 'form-control', 'readonly']); !!}

					{!! Form::hidden('types_of_service_id', $transaction->types_of_service_id, ['id' => 'types_of_service_id']) !!}
					<span class="input-group-addon">
						@show_tooltip(__('lang_v1.types_of_service_help'))
					</span> 
				</div>
				<small><p class="help-block @if(empty($transaction->selling_price_group_id)) hide @endif" id="price_group_text">@lang('lang_v1.price_group'): <span>@if(!empty($transaction->selling_price_group_id)){{$transaction->price_group->name}}@endif</span></p></small>
			</div>
		</div>
		<div class="modal fade types_of_service_modal" tabindex="-1" role="dialog" aria-labelledby="gridSystemModalLabel">
			@if(!empty($transaction->types_of_service))
				@include('types_of_service.pos_form_modal', ['types_of_service' => $transaction->types_of_service])
			@endif
		</div>
	@endif
	@if($transaction->status == 'draft' && !empty($pos_settings['show_invoice_scheme']))
		<div class="col-sm-3">
			<div class="form-group">
				{!! Form::select('invoice_scheme_id', $invoice_schemes, $default_invoice_schemes->id, ['class' => 'form-control', 'placeholder' => __('lang_v1.select_invoice_scheme')]); !!}
			</div>
		</div>
	@endif
	<!-- Call restaurant module if defined -->
    @if(in_array('tables' ,$enabled_modules) || in_array('service_staff' ,$enabled_modules))
    	<span id="restaurant_module_span" 
    		data-transaction_id="{{$transaction->id}}">
      		<div class="col-md-3"></div>
    	</span>
    @endif
	@if(in_array('kitchen' ,$enabled_modules))
		<div class="col-md-3">
			<div class="form-group">
				<div class="checkbox">
				<label>
						{!! Form::checkbox('is_kitchen_order', 1, $transaction->is_kitchen_order, ['class' => 'input-icheck status', 'id' => 'is_kitchen_order']); !!} {{ __('lang_v1.kitchen_order') }}
				</label>
				</div>
			</div>
		</div>
    @endif
    @if(in_array('subscription', $enabled_modules))
		<div class="col-md-4 col-sm-6">
			<label>
              {!! Form::checkbox('is_recurring', 1, $transaction->is_recurring, ['class' => 'input-icheck', 'id' => 'is_recurring']); !!} @lang('lang_v1.subscribe')?
            </label><button type="button" data-toggle="modal" data-target="#recurringInvoiceModal" class="btn btn-link"><i class="fa fa-external-link-square-alt"></i></button>@show_tooltip(__('lang_v1.recurring_invoice_help'))
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
	<div class="col-sm-12 pos_product_div">
		<input type="hidden" name="sell_price_tax" id="sell_price_tax" value="{{$business_details->sell_price_tax}}">

		<!-- Keeps count of product rows -->
		<input type="hidden" id="product_row_count" 
			value="{{count($sell_details)}}">
		@php
			$hide_tax = '';
			if( session()->get('business.enable_inline_tax') == 0){
				$hide_tax = 'hide';
			}
		@endphp
		<table class="table table-condensed" id="pos_table" style="table-layout: fixed !important; width: 100% !important; margin-bottom: 0 !important;">
			<colgroup>
				<col style="width: 46% !important;">
				<col style="width: 19% !important;">
				@if(!empty($pos_settings['inline_service_staff']))
					<col style="width: 10% !important;">
				@endif
				<col class="{{$hide_tax}}" style="width: 14% !important;">
				<col style="width: 17% !important;">
				<col style="width: 34px !important;">
			</colgroup>
			<thead>
				<tr>
					<th class="text-left pos-th-product tw-sticky tw-top-0 tw-z-10 !tw-bg-[#f8fafc] !tw-text-[#94a3b8] !tw-border-b !tw-border-[#e2e8f0] !tw-border-t-0 !tw-border-l-0 !tw-border-r-0 !tw-px-1.5 !tw-py-2 !tw-text-[10px] !tw-font-medium tw-uppercase tw-tracking-[0.4px] !tw-leading-none tw-whitespace-nowrap tw-overflow-hidden !tw-align-middle" style="width: 46% !important;">
						@lang('sale.product') @show_tooltip(__('lang_v1.tooltip_sell_product_column'))
					</th>
					<th class="text-center pos-th-qty tw-sticky tw-top-0 tw-z-10 !tw-bg-[#f8fafc] !tw-text-[#94a3b8] !tw-border-b !tw-border-[#e2e8f0] !tw-border-t-0 !tw-border-l-0 !tw-border-r-0 !tw-px-0.5 !tw-py-2 !tw-text-[10px] !tw-font-medium tw-uppercase tw-tracking-[0.4px] !tw-leading-none tw-whitespace-nowrap tw-overflow-hidden !tw-align-middle" style="width: 19% !important;">
						@lang('sale.qty')
					</th>
					@if(!empty($pos_settings['inline_service_staff']))
						<th class="text-center pos-th-staff tw-sticky tw-top-0 tw-z-10 !tw-bg-[#f8fafc] !tw-text-[#94a3b8] !tw-border-b !tw-border-[#e2e8f0] !tw-border-t-0 !tw-border-l-0 !tw-border-r-0 !tw-px-0.5 !tw-py-2 !tw-text-[10px] !tw-font-medium tw-uppercase tw-tracking-[0.4px] !tw-leading-none tw-whitespace-nowrap tw-overflow-hidden !tw-align-middle" style="width: 10% !important;">
							@lang('restaurant.service_staff')
						</th>
					@endif
					<th class="text-right pos-th-price tw-sticky tw-top-0 tw-z-10 !tw-bg-[#f8fafc] !tw-text-[#94a3b8] !tw-border-b !tw-border-[#e2e8f0] !tw-border-t-0 !tw-border-l-0 !tw-border-r-0 !tw-px-0.5 !tw-py-2 !tw-text-[10px] !tw-font-medium tw-uppercase tw-tracking-[0.4px] !tw-leading-none tw-whitespace-nowrap tw-overflow-hidden !tw-align-middle {{$hide_tax}}" style="width: 14% !important; text-overflow: ellipsis;">
						@lang('sale.price_inc_tax')
					</th>
					<th class="text-right pos-th-subtotal tw-sticky tw-top-0 tw-z-10 !tw-bg-[#f8fafc] !tw-text-[#94a3b8] !tw-border-b !tw-border-[#e2e8f0] !tw-border-t-0 !tw-border-l-0 !tw-border-r-0 !tw-px-0.5 !tw-py-2 !tw-text-[10px] !tw-font-medium tw-uppercase tw-tracking-[0.4px] !tw-leading-none tw-whitespace-nowrap tw-overflow-hidden !tw-align-middle" style="width: 17% !important;">
						@lang('sale.subtotal')
					</th>
					<th class="pos-th-action tw-sticky tw-top-0 tw-z-10 !tw-bg-[#f8fafc] !tw-border-b !tw-border-[#e2e8f0] !tw-border-t-0 !tw-border-l-0 !tw-border-r-0 !tw-py-2 !tw-px-0 !tw-text-[10px] !tw-font-medium tw-uppercase tw-tracking-[0.4px] !tw-leading-none tw-whitespace-nowrap tw-overflow-hidden !tw-align-middle !tw-text-center" style="width: 34px !important; padding: 0 8px 0 2px !important;"></th>
				</tr>
			</thead>
			<tbody>
				@foreach($sell_details as $sell_line)

				@include('sale_pos.product_row', 
					['product' => $sell_line, 
					'row_count' => $loop->index, 
					'tax_dropdown' => $taxes, 
					'sub_units' => !empty($sell_line->unit_details) ? $sell_line->unit_details : [],
					'action' => 'edit'
				])
			@endforeach
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
				width: 46% !important;
				max-width: 46% !important;
				overflow: hidden !important;
			}
			#pos_table th.pos-th-qty, #pos_table td.pos-td-qty {
				width: 19% !important;
				max-width: 19% !important;
				position: relative !important;
				overflow: visible !important;
			}
			#pos_table th.pos-th-price, #pos_table td.pos-td-price {
				width: 14% !important;
				max-width: 14% !important;
			}
			#pos_table th.pos-th-subtotal, #pos_table td.pos-td-subtotal {
				width: 17% !important;
				max-width: 17% !important;
			}
			#pos_table th.pos-th-action, #pos_table td.pos-td-action {
				width: 34px !important;
				max-width: 34px !important;
				padding: 0 8px 0 2px !important;
				text-align: center !important;
			}

			#pos_table tbody tr.product_row {
				background: #ffffff;
				transition: background-color 0.15s ease;
			}
			#pos_table tbody tr.product_row:hover {
				background: #f8fafc;
			}
			#pos_table tbody tr.product_row td {
				padding: 7px 3px !important;
				vertical-align: middle !important;
				border-top: none !important;
				border-bottom: 1px solid #f1f5f9 !important;
			}

			.pos-qty-stepper {
				display: inline-flex !important;
				align-items: center !important;
				background: #f8fafc !important;
				border: 1px solid #e2e8f0 !important;
				border-radius: 6px !important;
				padding: 1px !important;
				width: auto !important;
				max-width: 96px !important;
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
				width: 20px !important;
				height: 20px !important;
				min-width: 20px !important;
				border: none !important;
				background: #2563eb !important;
				color: #ffffff !important;
				border-radius: 4px !important;
				display: inline-flex !important;
				align-items: center !important;
				justify-content: center !important;
				padding: 0 !important;
				cursor: pointer !important;
				box-shadow: 0 1px 2px rgba(37,99,235,0.25) !important;
				transition: all 0.15s ease !important;
			}
			.pos-qty-btn svg {
				stroke: #ffffff !important;
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
				width: 38px !important;
				min-width: 34px !important;
				height: 22px !important;
				padding: 0 1px !important;
				background: transparent !important;
				border: none !important;
				box-shadow: none !important;
				text-align: center !important;
				font-size: 13px !important;
				font-weight: 700 !important;
				color: #0f172a !important;
			}
			.pos-qty-input:focus {
				background: #ffffff !important;
				border-radius: 4px !important;
				box-shadow: 0 0 0 1px #2563eb !important;
				outline: none !important;
			}
			.pos-price-input {
				width: 100% !important;
				max-width: 60px !important;
				height: 25px !important;
				padding: 1px 4px !important;
				font-size: 13px !important;
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
		</style>
	</div>
</div>