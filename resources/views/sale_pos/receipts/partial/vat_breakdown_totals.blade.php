{{-- Totals with tax base and VAT, used by receipt designs when the invoice layout has show_vat_breakdown enabled --}}
@php
	$vat_breakdown = $receipt_details->vat_breakdown;
@endphp
<!-- Order discount: taken off before the tax base and VAT below -->
@if(!empty($receipt_details->discount))
	<tr>
		<th style="width:70%">
			@if($vat_breakdown['is_order_tax'])
				@lang('lang_v1.subtotal_excl_vat'):
			@else
				@lang('lang_v1.subtotal_incl_vat'):
			@endif
		</th>
		<td class="text-right">{{$vat_breakdown['lines_total']}}</td>
	</tr>
	<tr>
		<th>{!! $receipt_details->discount_label !!}</th>
		<td class="text-right">(-) {{$receipt_details->discount}}</td>
	</tr>
@endif
<!-- Base imponible -->
<tr>
	<th style="width:70%">@lang('lang_v1.tax_base'):</th>
	<td class="text-right">{{$vat_breakdown['base_total']}}</td>
</tr>
<!-- Total VAT -->
<tr>
	<th style="width:70%">@lang('lang_v1.total_vat'):</th>
	<td class="text-right">{{$vat_breakdown['vat_total']}}</td>
</tr>
<!-- Order tax on top of line VAT -->
@if(!$vat_breakdown['is_order_tax'] && !empty($receipt_details->tax))
	<tr>
		<th>{!! $receipt_details->tax_label !!}</th>
		<td class="text-right">(+) {{$receipt_details->tax}}</td>
	</tr>
@endif
<!-- Charges after VAT -->
@if(!empty($receipt_details->shipping_charges))
	<tr>
		<th>
			{!! $receipt_details->shipping_charges_label !!}
			<small>(@lang('lang_v1.not_subject_to_vat'))</small>
		</th>
		<td class="text-right">(+) {{$receipt_details->shipping_charges}}</td>
	</tr>
@endif
@if(!empty($receipt_details->packing_charge))
	<tr>
		<th>
			{!! $receipt_details->packing_charge_label !!}
			<small>(@lang('lang_v1.not_subject_to_vat'))</small>
		</th>
		<td class="text-right">(+) {{$receipt_details->packing_charge}}</td>
	</tr>
@endif
@if(!empty($receipt_details->additional_expenses))
	@foreach($receipt_details->additional_expenses as $key => $val)
		<tr>
			<td>{{$key}} <small>(@lang('lang_v1.not_subject_to_vat'))</small>:</td>
			<td class="text-right">(+) {{$val}}</td>
		</tr>
	@endforeach
@endif
@if(!empty($receipt_details->reward_point_label))
	<tr>
		<th>{!! $receipt_details->reward_point_label !!}</th>
		<td class="text-right">(-) {{$receipt_details->reward_point_amount}}</td>
	</tr>
@endif
@if($receipt_details->round_off_amount != 0)
	<tr>
		<th>{!! $receipt_details->round_off_label !!}</th>
		<td class="text-right">{{$receipt_details->round_off}}</td>
	</tr>
@endif
<!-- Total -->
<tr>
	<th>@lang('lang_v1.total_incl_vat'):</th>
	<td class="text-right">
		{{$receipt_details->total}}
		@if(!empty($receipt_details->total_in_words))
			<br>
			<small>({{$receipt_details->total_in_words}})</small>
		@endif
	</td>
</tr>
