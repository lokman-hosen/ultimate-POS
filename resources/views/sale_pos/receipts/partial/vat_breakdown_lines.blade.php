{{-- Invoice lines with VAT per line, used by receipt designs when the invoice layout has show_vat_breakdown enabled --}}
<div class="row" style="color: #000000 !important;">
	<div class="col-xs-12">
		<br/>
		<table class="table table-responsive table-slim">
			<thead>
				<tr>
					<th width="34%">{{$receipt_details->table_product_label}}</th>
					<th class="text-right" width="10%">{{$receipt_details->table_qty_label}}</th>
					<th class="text-right" width="14%">@lang('lang_v1.price_excl_vat')</th>
					<th class="text-right" width="10%">@lang('lang_v1.vat_percent')</th>
					<th class="text-right" width="14%">@lang('lang_v1.vat_amount')</th>
					<th class="text-right" width="18%">@lang('lang_v1.total_incl_vat')</th>
				</tr>
			</thead>
			<tbody>
				@forelse($receipt_details->lines as $line)
					<tr>
						<td>
							@if(!empty($line['image']))
								<img src="{{$line['image']}}" alt="Image" width="50" style="float: left; margin-right: 8px;">
							@endif
                            {{$line['name']}} {{$line['product_variation']}} {{$line['variation']}} 
                            @if(!empty($line['sub_sku'])), {{$line['sub_sku']}} @endif @if(!empty($line['brand'])), {{$line['brand']}} @endif @if(!empty($line['cat_code'])), {{$line['cat_code']}}@endif
                            @if(!empty($line['product_custom_fields'])), {{$line['product_custom_fields']}} @endif
                            @if(!empty($line['product_description']))
                            	<small>
                            		{!!$line['product_description']!!}
                            	</small>
                            @endif 
                            @if(!empty($line['sell_line_note']))
                            <br>
                            <small>
                            	{!!$line['sell_line_note']!!}
                            </small>
                            @endif 
                            @if(!empty($line['lot_number']))<br> {{$line['lot_number_label']}}:  {{$line['lot_number']}} @endif 
                            @if(!empty($line['product_expiry'])), {{$line['product_expiry_label']}}:  {{$line['product_expiry']}} @endif

                            @if(!empty($line['warranty_name'])) <br><small>{{$line['warranty_name']}} </small>@endif @if(!empty($line['warranty_exp_date'])) <small>- {{@format_date($line['warranty_exp_date'])}} </small>@endif
                            @if(!empty($line['warranty_description'])) <small> {{$line['warranty_description'] ?? ''}}</small>@endif

                            @if($receipt_details->show_base_unit_details && $line['quantity'] && $line['base_unit_multiplier'] !== 1)
                            <br><small>
                            	1 {{$line['units']}} = {{$line['base_unit_multiplier']}} {{$line['base_unit_name']}} <br>
                            	{{$line['base_unit_price']}} x {{$line['orig_quantity']}} = {{$line['line_total']}}
                            </small>
                            @endif
                            @if($line['vat_has_line_discount'])
                            <br><small>{{$line['unit_price_before_discount']}} - @lang('lang_v1.line_discount_short') {{$line['line_discount']}}</small>
                            @endif
                        </td>
						<td class="text-right">
							{{$line['quantity']}} {{$line['units']}}

							@if($receipt_details->show_base_unit_details && $line['quantity'] && $line['base_unit_multiplier'] !== 1)
                            <br><small>
                            	{{$line['quantity']}} x {{$line['base_unit_multiplier']}} = {{$line['orig_quantity']}} {{$line['base_unit_name']}}
                            </small>
                            @endif
						</td>
						<td class="text-right">{{$line['unit_price_exc_tax']}}</td>
						<td class="text-right">{{$line['vat_percent_label']}}</td>
						<td class="text-right">{{$line['vat_line_amount']}}</td>
						<td class="text-right">{{$line['vat_line_total']}}</td>
					</tr>
					@if(!empty($line['modifiers']))
						@foreach($line['modifiers'] as $modifier)
							<tr>
								<td>
		                            {{$modifier['name']}} {{$modifier['variation']}}
		                            @if(!empty($modifier['sub_sku'])), {{$modifier['sub_sku']}} @endif @if(!empty($modifier['cat_code'])), {{$modifier['cat_code']}}@endif
		                            @if(!empty($modifier['sell_line_note']))({!!$modifier['sell_line_note']!!}) @endif
		                        </td>
								<td class="text-right">{{$modifier['quantity']}} {{$modifier['units']}}</td>
								<td class="text-right">{{$modifier['unit_price_exc_tax']}}</td>
								<td class="text-right">{{$modifier['vat_percent_label']}}</td>
								<td class="text-right">{{$modifier['vat_line_amount']}}</td>
								<td class="text-right">{{$modifier['vat_line_total']}}</td>
							</tr>
						@endforeach
					@endif
				@empty
					<tr>
						<td colspan="6">&nbsp;</td>
					</tr>
				@endforelse
			</tbody>
		</table>
	</div>
</div>
