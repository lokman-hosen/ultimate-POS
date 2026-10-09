{{-- VAT summary grouped by rate, used by receipt designs when the invoice layout has show_vat_breakdown enabled --}}
@if(empty($receipt_details->hide_price) && !empty($receipt_details->vat_breakdown['rates']))
	<table class="table table-slim table-bordered">
		<thead>
			<tr>
				<th colspan="4" class="text-center">@lang('lang_v1.vat_breakdown')</th>
			</tr>
			<tr>
				<th>@lang('lang_v1.vat_rate')</th>
				<th class="text-right">@lang('lang_v1.tax_base')</th>
				<th class="text-right">@lang('lang_v1.vat_amount')</th>
				<th class="text-right">@lang('lang_v1.total_incl_vat')</th>
			</tr>
		</thead>
		<tbody>
			@foreach($receipt_details->vat_breakdown['rates'] as $rate)
				<tr>
					<td>{{$rate['label']}}</td>
					<td class="text-right">{{$rate['base']}}</td>
					<td class="text-right">{{$rate['vat']}}</td>
					<td class="text-right">{{$rate['gross']}}</td>
				</tr>
			@endforeach
		</tbody>
		<tfoot>
			<tr>
				<th>@lang('sale.total')</th>
				<th class="text-right">{{$receipt_details->vat_breakdown['base_total']}}</th>
				<th class="text-right">{{$receipt_details->vat_breakdown['vat_total']}}</th>
				<th class="text-right">{{$receipt_details->vat_breakdown['gross_total']}}</th>
			</tr>
		</tfoot>
	</table>
@endif
