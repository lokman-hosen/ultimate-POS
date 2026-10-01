@php
	$dropdown_col_class = 'col-xs-6 col-sm-6 col-md-6';
@endphp
@if($tables_enabled)
<div class="{{ $dropdown_col_class }}" id="table_dropdown_group" style="padding-left: 4px; padding-right: 4px;">
	<div class="form-group" style="margin-bottom: 8px;">
		<div class="input-group" style="width: 100%;">
			<span class="input-group-addon" style="padding: 4px 8px; font-size: 12px;">
				<i class="fa fa-table"></i>
			</span>
			{!! Form::select('res_table_id', $tables, $view_data['res_table_id'], ['class' => 'form-control input-sm', 'style' => 'width: 100%; min-width: 0; font-size: 12px; height: 32px; padding: 4px 6px;', 'placeholder' => __('restaurant.select_table')]); !!}
		</div>
	</div>
</div>
@endif
@if($waiters_enabled)
<div class="{{ $dropdown_col_class }}" id="waiter_dropdown_group" style="padding-left: 4px; padding-right: 4px;">
	<div class="form-group" style="margin-bottom: 8px;">
		<div class="input-group" style="width: 100%;">
			<span class="input-group-addon" style="padding: 4px 8px; font-size: 12px;">
				<i class="fa fa-user-secret"></i>
			</span>
			<select class="form-control input-sm" name="res_waiter_id" id="res_waiter_id" style="width: 100%; min-width: 0; font-size: 12px; height: 32px; padding: 4px 6px; text-overflow: ellipsis;" @if ($is_service_staff_required) 
			required
			@endif>
				<option selected value="">{{ __('restaurant.select_service_staff') }}</option>
				 @foreach ($waiters as $waiter)
					<option {{ $waiter->id == $view_data['res_waiter_id'] ? 'selected' : ''; }} value="{{ $waiter->id }}" data-is_enable="{{ $waiter->is_enable_service_staff_pin }}">{{ $waiter->first_name . ' ' . $waiter->last_name}}</option>
				 @endforeach
			</select>
			@if(!empty($pos_settings['inline_service_staff']))
			<div class="input-group-btn">
                <button type="button" class="btn btn-default bg-white btn-flat" id="select_all_service_staff" data-toggle="tooltip" title="@lang('lang_v1.select_same_for_all_rows')" style="height: 32px; padding: 4px 8px;"><i class="fa fa-check"></i></button>
            </div>
            @endif
		</div>
	</div>
</div>
@endif