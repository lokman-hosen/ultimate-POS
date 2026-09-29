@forelse ($products as $product)
    @php
        $row_index = $loop->index + $index;
    @endphp
    <tr>
        <td style="vertical-align: middle;">
            <div class="tw-font-bold tw-text-gray-900 tw-text-sm">{{$product->product_name}}</div>
            @if($product->variation_name != "DUMMY")
                <span class="tw-inline-flex tw-items-center tw-px-2 tw-py-0.5 tw-rounded tw-text-xs tw-font-semibold tw-bg-slate-100 tw-text-slate-700 tw-mt-1">
                    {{$product->variation_name}}
                </span>
            @endif
            <input type="hidden" name="products[{{$row_index}}][product_id]" value="{{$product->product_id}}">
            <input type="hidden" name="products[{{$row_index}}][variation_id]" value="{{$product->variation_id}}">
        </td>
        <td style="vertical-align: middle;">
            <div class="input-group input-group-sm" style="width: 100%;">
                <span class="input-group-addon" style="background: #f8fafc; border-color: #cbd5e1; color: #64748b;">
                    <i class="fa fa-tag"></i>
                </span>
                <input type="number" class="form-control text-center" min="1"
                    name="products[{{$row_index}}][quantity]" 
                    value="@if(isset($product->quantity)){{$product->quantity}}@else{{1}}@endif"
                    style="height: 38px; border-color: #cbd5e1; border-radius: 0 8px 8px 0; font-weight: 600;">
            </div>
        </td>
        @if(request()->session()->get('business.enable_lot_number') == 1)
            <td style="vertical-align: middle;">
                <input type="text" class="form-control input-sm"
                    name="products[{{$row_index}}][lot_number]" 
                    value="@if(isset($product->lot_number)){{$product->lot_number}}@endif"
                    placeholder="@lang('lang_v1.lot_number')"
                    style="height: 38px; border-radius: 8px; border-color: #cbd5e1;">
            </td>
        @endif
        @if(request()->session()->get('business.enable_product_expiry') == 1)
            <td style="vertical-align: middle;">
                <div class="input-group input-group-sm" style="width: 100%;">
                    <span class="input-group-addon" style="background: #f8fafc; border-color: #cbd5e1; color: #64748b;">
                        <i class="fa fa-calendar"></i>
                    </span>
                    <input type="text" class="form-control label-date-picker input-sm"
                        name="products[{{$row_index}}][exp_date]" 
                        value="@if(isset($product->exp_date)){{@format_date($product->exp_date)}}@endif"
                        placeholder="@lang('product.exp_date')"
                        style="height: 38px; border-radius: 0 8px 8px 0; border-color: #cbd5e1;">
                </div>
            </td>
        @endif
        <td style="vertical-align: middle;">
            <div class="input-group input-group-sm" style="width: 100%;">
                <span class="input-group-addon" style="background: #f8fafc; border-color: #cbd5e1; color: #64748b;">
                    <i class="fa fa-calendar"></i>
                </span>
                <input type="text" class="form-control label-date-picker input-sm"
                    name="products[{{$row_index}}][packing_date]" 
                    value=""
                    placeholder="YYYY-MM-DD"
                    style="height: 38px; border-radius: 0 8px 8px 0; border-color: #cbd5e1;">
            </div>
        </td>
        <td style="vertical-align: middle;">
            {!! Form::select('products[' . $row_index . '][price_group_id]', $price_groups, null, [
                'class' => 'form-control input-sm', 
                'placeholder' => __('lang_v1.none'),
                'style' => 'height: 38px; border-radius: 8px; border-color: #cbd5e1; width: 100%;'
            ]); !!}
        </td>
        <td style="vertical-align: middle; text-align: center;">
            <button type="button" class="tw-dw-btn tw-dw-btn-ghost tw-dw-btn-xs tw-text-rose-500 hover:tw-bg-rose-50 remove_label_product_row" title="{{ __('messages.delete') }}" style="height: 34px; width: 34px; padding: 0; border-radius: 6px;">
                <i class="fa fa-trash tw-text-base"></i>
            </button>
        </td>
    </tr>
@empty

@endforelse