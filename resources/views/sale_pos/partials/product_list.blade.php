@forelse($products as $product)
	<div class="col-md-3 col-xs-4 no-print !tw-px-[3px]">
		<div class="product_box tw-w-full tw-mb-1 tw-text-center tw-cursor-pointer tw-font-semibold tw-bg-white tw-rounded-lg tw-p-1 tw-border tw-border-[#e5e7eb] tw-shadow-[0_1px_3px_rgba(0,0,0,0.06)] tw-transition-all tw-duration-150 hover:-tw-translate-y-px hover:tw-shadow-[0_4px_12px_rgba(0,0,0,0.1)] active:tw-scale-[0.97] @if($product->enable_stock && $product->qty_available <= 0) product_out_of_stock !tw-bg-[#f3f4f6] tw-opacity-60 @endif"
			data-variation_id="{{$product->id}}"
			title="{{$product->name}} @if($product->type == 'variable')- {{$product->variation}} @endif {{ '(' . $product->sub_sku . ')'}} @if(!empty($show_prices)) @lang('lang_v1.default') - @format_currency($product->selling_price) @foreach($product->group_prices as $group_price) @if(array_key_exists($group_price->price_group_id, $allowed_group_prices)) {{$allowed_group_prices[$group_price->price_group_id]}} - @format_currency($group_price->price_inc_tax) @endif @endforeach @endif">

			@php
				$image_url = null;
				if (count($product->media) > 0) {
					$image_url = $product->media->first()->display_url;
				} elseif (!empty($product->product_image)) {
					$image_url = asset('/uploads/img/' . rawurlencode($product->product_image));
				}
			@endphp

			@if($image_url)
				<div class="image-container tw-h-[58px] tw-mx-auto tw-w-full tw-mb-[3px]"
					style="background-image: url('{{$image_url}}'); background-repeat: no-repeat; background-position: center; background-size: contain;">
				</div>
			@else
				<div class="image-container tw-h-[58px] tw-mx-auto tw-w-full tw-mb-[3px]"
					style="background-color: #f8fafc; border-radius: 6px; display: flex; align-items: center; justify-content: center;">
					<svg xmlns="http://www.w3.org/2000/svg" width="28" height="28" viewBox="0 0 24 24" fill="none"
						stroke="#94a3b8" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
						<path
							d="M21 16V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16z">
						</path>
						<polyline points="3.27 6.96 12 12.01 20.73 6.96"></polyline>
						<line x1="12" y1="22.08" x2="12" y2="12"></line>
					</svg>
				</div>
			@endif

			<div class="text_div tw-mt-0.5">
				<small
					class="text text-muted tw-w-full tw-line-clamp-1 !tw-leading-[13px] tw-max-h-[13px] !tw-text-[11px]">{{$product->name}}
					@if($product->type == 'variable')
						- {{$product->variation}}
					@endif
				</small>

				<small class="text-muted">
					({{$product->sub_sku}})
				</small><br>
				<small class="text-muted" style="font-size: 10px;">
					@if($product->enable_stock)
						{{ @num_format($product->qty_available) }} {{$product->unit}} @lang('lang_v1.in_stock')
					@else
						--
					@endif
				</small><br>
				@if(!empty($show_prices))
					<span
						class="product_price !tw-text-[11px] tw-font-bold tw-text-[#15803d] tw-leading-[13px] tw-whitespace-nowrap tw-overflow-hidden tw-text-ellipsis">@format_currency($product->selling_price)</span>
				@endif
			</div>

		</div>
	</div>
@empty
	<input type="hidden" id="no_products_found">
	<div class="col-md-12">
		<h4 class="text-center">
			@lang('lang_v1.no_products_to_display')
		</h4>
	</div>
@endforelse