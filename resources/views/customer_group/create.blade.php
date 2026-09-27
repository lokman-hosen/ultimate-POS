<div class="modal-dialog customer-group-modal-custom" role="document">
  <div class="modal-content">

    {!! Form::open(['url' => action([\App\Http\Controllers\CustomerGroupController::class, 'store']), 'method' => 'post', 'id' => 'customer_group_add_form' ]) !!}

    <style>
      .customer-group-modal-custom .modal-content {
        border-radius: 16px;
        border: none;
        box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
        overflow: hidden;
        background: #ffffff;
      }
      .customer-group-modal-custom .modal-header {
        padding: 22px 28px 16px;
        border-bottom: 1px solid #f1f5f9;
        position: relative;
        background: #ffffff;
      }
      .customer-group-modal-custom .modal-header .modal-title {
        font-size: 19px;
        font-weight: 700;
        color: #0f172a;
        margin: 0;
        line-height: 1.2;
      }
      .customer-group-modal-custom .modal-header .close {
        position: absolute;
        right: 24px;
        top: 22px;
        font-size: 24px;
        color: #94a3b8;
        opacity: 0.8;
        transition: all 0.15s ease;
        background: transparent;
        border: none;
        line-height: 1;
      }
      .customer-group-modal-custom .modal-header .close:hover {
        color: #0f172a;
        opacity: 1;
      }
      .customer-group-modal-custom .modal-body {
        padding: 24px 28px;
      }
      .customer-group-modal-custom .form-group {
        margin-bottom: 18px;
      }
      .customer-group-modal-custom .form-group label {
        font-size: 13px;
        font-weight: 600;
        color: #1e293b;
        margin-bottom: 6px;
        display: block;
      }
      .cg-input-icon-wrap {
        position: relative;
        display: flex;
        align-items: center;
      }
      .cg-input-icon-wrap .cg-input-icon {
        position: absolute;
        left: 14px;
        color: #94a3b8;
        font-size: 14px;
        pointer-events: none;
        z-index: 2;
      }
      .cg-input-icon-wrap .form-control,
      .customer-group-modal-custom .form-control {
        border: 1px solid #e2e8f0;
        border-radius: 10px;
        padding: 9px 14px;
        height: 42px;
        font-size: 13.5px;
        color: #1e293b;
        background-color: #ffffff;
        box-shadow: none;
        transition: border-color 0.15s ease, box-shadow 0.15s ease;
      }
      .cg-input-icon-wrap .form-control {
        padding-left: 38px;
      }
      .customer-group-modal-custom .form-control:focus {
        border-color: #3b82f6;
        box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.12);
        outline: none;
      }
      .cg-info-callout {
        background: #eff6ff;
        border: 1px solid #dbeafe;
        border-radius: 12px;
        padding: 14px 16px;
        display: flex;
        align-items: flex-start;
        gap: 12px;
        margin-top: 18px;
      }
      .cg-info-callout-icon {
        color: #2563eb;
        font-size: 18px;
        margin-top: 2px;
        flex-shrink: 0;
      }
      .cg-info-callout-text {
        color: #1e40af;
        font-size: 12.5px;
        line-height: 1.5;
        margin: 0;
      }
      .customer-group-modal-custom .modal-footer {
        padding: 16px 28px;
        background: #ffffff;
        border-top: 1px solid #f1f5f9;
        display: flex;
        align-items: center;
        justify-content: flex-end;
        gap: 12px;
      }
      .btn-cg-cancel {
        padding: 10px 20px;
        border-radius: 10px;
        font-size: 13.5px;
        font-weight: 600;
        color: #475569;
        background: #ffffff;
        border: 1px solid #cbd5e1;
        transition: all 0.15s ease;
      }
      .btn-cg-cancel:hover {
        background: #f8fafc;
        color: #0f172a;
        border-color: #94a3b8;
      }
      .btn-cg-save {
        padding: 10px 24px;
        border-radius: 10px;
        font-size: 13.5px;
        font-weight: 600;
        color: #ffffff;
        background: #2563eb;
        border: none;
        display: inline-flex;
        align-items: center;
        gap: 8px;
        box-shadow: 0 4px 6px -1px rgba(37, 99, 235, 0.25), 0 2px 4px -2px rgba(37, 99, 235, 0.25);
        transition: all 0.15s ease;
      }
      .btn-cg-save:hover {
        background: #1d4ed8;
        box-shadow: 0 6px 10px -1px rgba(37, 99, 235, 0.35);
      }
    </style>

    <div class="modal-header">
      <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
      <h4 class="modal-title">@lang( 'lang_v1.add_customer_group' )</h4>
    </div>

    <div class="modal-body">
      <div class="form-group">
        {!! Form::label('name', __( 'lang_v1.customer_group_name' ) . ':*') !!}
        <div class="cg-input-icon-wrap">
          <i class="fa fa-users cg-input-icon"></i>
          {!! Form::text('name', null, ['class' => 'form-control', 'required', 'placeholder' => __( 'lang_v1.customer_group_name' ) ]); !!}
        </div>
      </div>

      <div class="form-group">
        {!! Form::label('price_calculation_type', __( 'lang_v1.price_calculation_type' ) . ':') !!}
        <div class="cg-input-icon-wrap">
          <i class="fa fa-calculator cg-input-icon"></i>
          {!! Form::select('price_calculation_type',['percentage' => __('lang_v1.percentage'), 'selling_price_group' => __('lang_v1.selling_price_group')], 'percentage', ['class' => 'form-control', 'id' => 'price_calculation_type']); !!}
        </div>
      </div>

      <div class="form-group percentage-field">
        {!! Form::label('amount', __( 'lang_v1.calculation_percentage' ) . ':') !!}
        <div class="cg-input-icon-wrap">
          <i class="fa fa-percent cg-input-icon"></i>
          {!! Form::text('amount', null, ['class' => 'form-control input_number','placeholder' => __( 'lang_v1.calculation_percentage')]); !!}
        </div>
      </div>

      <div class="form-group selling_price_group-field hide">
        {!! Form::label('selling_price_group_id', __( 'lang_v1.selling_price_group' ) . ':') !!}
        <div class="cg-input-icon-wrap">
          <i class="fa fa-tags cg-input-icon"></i>
          {!! Form::select('selling_price_group_id', $price_groups, null, ['class' => 'form-control']); !!}
        </div>
      </div>

      <div class="cg-info-callout percentage-field">
        <i class="fa fa-info-circle cg-info-callout-icon"></i>
        <p class="cg-info-callout-text">
          @lang('lang_v1.tooltip_calculation_percentage')
        </p>
      </div>

    </div>

    <div class="modal-footer">
      <button type="button" class="btn-cg-cancel" data-dismiss="modal">@lang( 'messages.close' )</button>
      <button type="submit" class="btn-cg-save">
        <i class="fa fa-floppy-o fa-save"></i>
        @lang( 'messages.save' )
      </button>
    </div>

    {!! Form::close() !!}

  </div><!-- /.modal-content -->
</div><!-- /.modal-dialog -->