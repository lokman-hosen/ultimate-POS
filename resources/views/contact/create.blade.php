<div class="modal-dialog modal-lg contact-modal-custom" role="document">
  <div class="modal-content">
  @php
    $form_id = 'contact_add_form';
    if(isset($quick_add)){
      $form_id = 'quick_add_contact';
    }

    if(isset($store_action)) {
      $url = $store_action;
      $type = 'lead';
      $customer_groups = [];
    } else {
      $url = action([\App\Http\Controllers\ContactController::class, 'store']);
      $type = isset($selected_type) ? $selected_type : '';
      $sources = [];
      $life_stages = [];
    }
  @endphp

    <style>
      .contact-modal-custom .modal-content {
        border-radius: 16px;
        border: none;
        box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
        overflow: hidden;
        background: #ffffff;
      }
      .contact-modal-custom .modal-header {
        padding: 20px 28px 16px;
        border-bottom: 1px solid #f1f5f9;
        position: relative;
        background: #ffffff;
      }
      .contact-modal-custom .modal-header .modal-title {
        font-size: 20px;
        font-weight: 700;
        color: #0f172a;
        margin: 0;
        line-height: 1.2;
      }
      .contact-modal-custom .modal-header .close {
        position: absolute;
        right: 24px;
        top: 20px;
        font-size: 24px;
        color: #94a3b8;
        opacity: 0.8;
        transition: all 0.15s ease;
        background: transparent;
        border: none;
        line-height: 1;
      }
      .contact-modal-custom .modal-header .close:hover {
        color: #0f172a;
        opacity: 1;
      }
      .contact-modal-custom .modal-body {
        padding: 24px 28px;
        max-height: calc(85vh - 140px);
        overflow-y: auto;
      }
      .contact-section-group {
        padding-bottom: 20px;
        margin-bottom: 20px;
        border-bottom: 1px solid #f1f5f9;
      }
      .contact-section-group:last-child {
        border-bottom: none;
        margin-bottom: 0;
        padding-bottom: 0;
      }
      .contact-section-header {
        display: flex;
        align-items: center;
        gap: 12px;
        margin-bottom: 16px;
      }
      .contact-badge-number {
        width: 24px;
        height: 24px;
        border-radius: 50%;
        background: #2563eb;
        color: #ffffff;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        font-size: 12px;
        font-weight: 700;
        flex-shrink: 0;
      }
      .contact-section-title-wrap {
        flex: 1;
      }
      .contact-section-title {
        font-size: 15px;
        font-weight: 700;
        color: #0f172a;
        margin: 0;
        line-height: 1.3;
      }
      .contact-type-cards-grid {
        display: grid;
        grid-template-columns: repeat(2, minmax(0, 1fr));
        gap: 14px;
      }
      @media (max-width: 640px) {
        .contact-type-cards-grid {
          grid-template-columns: 1fr;
        }
      }
      .contact-type-card {
        border: 1.5px solid #e2e8f0;
        border-radius: 12px;
        padding: 16px 18px;
        background: #ffffff;
        cursor: pointer;
        display: flex;
        align-items: center;
        gap: 14px;
        transition: all 0.2s ease-in-out;
        user-select: none;
      }
      .contact-type-card:hover {
        border-color: #93c5fd;
        background: #f8fafc;
      }
      .contact-type-card.active {
        border-color: #3b82f6;
        background: #eff6ff;
        box-shadow: 0 0 0 1px #3b82f6;
      }
      .contact-type-card .card-radio-dot {
        width: 18px;
        height: 18px;
        border-radius: 50%;
        border: 2px solid #cbd5e1;
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
        transition: all 0.2s;
        background: #fff;
      }
      .contact-type-card.active .card-radio-dot {
        border-color: #2563eb;
      }
      .contact-type-card.active .card-radio-dot::after {
        content: '';
        width: 8px;
        height: 8px;
        border-radius: 50%;
        background: #2563eb;
      }
      .contact-type-card .card-icon {
        width: 38px;
        height: 38px;
        border-radius: 8px;
        background: transparent;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 20px;
        color: #3b82f6;
        flex-shrink: 0;
      }
      .contact-type-card .card-info {
        flex: 1;
      }
      .contact-type-card .card-title {
        font-size: 14px;
        font-weight: 700;
        color: #0f172a;
        margin: 0;
      }
      .contact-modal-custom .form-group {
        margin-bottom: 16px;
      }
      .contact-modal-custom .form-group label {
        font-size: 13px;
        font-weight: 600;
        color: #1e293b;
        margin-bottom: 6px;
        display: block;
      }
      .contact-input-icon-wrap {
        position: relative;
        display: flex;
        align-items: center;
      }
      .contact-input-icon-wrap .contact-input-icon {
        position: absolute;
        left: 14px;
        color: #94a3b8;
        font-size: 14px;
        pointer-events: none;
        z-index: 2;
      }
      .contact-input-icon-wrap .form-control,
      .contact-modal-custom .form-control {
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
      .contact-input-icon-wrap .form-control {
        padding-left: 38px;
      }
      .contact-input-icon-wrap textarea.form-control,
      .contact-modal-custom textarea.form-control {
        height: auto;
        min-height: 42px;
      }
      .contact-modal-custom .form-control:focus {
        border-color: #3b82f6;
        box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.12);
        outline: none;
      }
      .contact-modal-custom .select2-container--default .select2-selection--single,
      .contact-modal-custom .select2-container--default .select2-selection--multiple {
        border: 1px solid #e2e8f0;
        border-radius: 10px;
        min-height: 42px;
        padding: 5px 12px 5px 36px;
        background: #ffffff;
      }
      .contact-modal-custom .select2-container--default .select2-selection--single .select2-selection__rendered {
        line-height: 30px;
        color: #1e293b;
        font-size: 13.5px;
        padding-left: 0;
      }
      .contact-modal-custom .select2-container--default .select2-selection--single .select2-selection__arrow {
        height: 40px;
        right: 10px;
      }
      .contact-modal-custom .select2-container--default.select2-container--focus .select2-selection--multiple,
      .contact-modal-custom .select2-container--default.select2-container--focus .select2-selection--single {
        border-color: #3b82f6;
        box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.12);
      }
      .contact-accordion-card {
        border: 1px solid #e2e8f0;
        border-radius: 12px;
        background: #f8fafc;
        overflow: hidden;
        margin-top: 14px;
        transition: all 0.2s ease;
      }
      .contact-accordion-header {
        padding: 14px 18px;
        display: flex;
        align-items: center;
        justify-content: space-between;
        cursor: pointer;
        user-select: none;
      }
      .contact-accordion-header:hover {
        background: #f1f5f9;
      }
      .contact-accordion-header-left {
        display: flex;
        align-items: center;
        gap: 12px;
      }
      .contact-accordion-icon-badge {
        width: 34px;
        height: 34px;
        border-radius: 8px;
        background: #e0e7ff;
        color: #4338ca;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 15px;
        flex-shrink: 0;
      }
      .contact-accordion-title {
        font-size: 14px;
        font-weight: 700;
        color: #0f172a;
        margin: 0;
      }
      .contact-accordion-chevron {
        color: #64748b;
        font-size: 14px;
        transition: transform 0.2s ease;
      }
      .contact-accordion-card.open .contact-accordion-chevron {
        transform: rotate(180deg);
      }
      .contact-accordion-body {
        padding: 20px 18px;
        background: #ffffff;
        border-top: 1px solid #e2e8f0;
      }
      .contact-modal-custom .modal-footer {
        padding: 16px 28px;
        background: #ffffff;
        border-top: 1px solid #f1f5f9;
        display: flex;
        align-items: center;
        justify-content: flex-end;
        gap: 12px;
      }
      .btn-contact-cancel {
        padding: 10px 20px;
        border-radius: 10px;
        font-size: 13.5px;
        font-weight: 600;
        color: #475569;
        background: #ffffff;
        border: 1px solid #cbd5e1;
        transition: all 0.15s ease;
      }
      .btn-contact-cancel:hover {
        background: #f8fafc;
        color: #0f172a;
        border-color: #94a3b8;
      }
      .btn-contact-save {
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
      .btn-contact-save:hover {
        background: #1d4ed8;
        box-shadow: 0 6px 10px -1px rgba(37, 99, 235, 0.35);
      }
    </style>

    {!! Form::open(['url' => $url, 'method' => 'post', 'id' => $form_id ]) !!}

    <div class="modal-header">
      <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
      <h4 class="modal-title">@lang('contact.add_contact')</h4>
    </div>

    <div class="modal-body">
        
        <!-- SECTION 1: TIPO DE CONTACTO / CONTACT TYPE -->
        <div class="contact-section-group">
          <div class="contact-section-header">
            <span class="contact-badge-number">1</span>
            <div class="contact-section-title-wrap">
              <h5 class="contact-section-title">@lang('contact.contact_type')</h5>
            </div>
          </div>

          <!-- Hidden Native Radios for complete JS compatibility -->
          <div style="display: none;">
            <input type="radio" name="contact_type_radio" id="inlineRadio1" value="individual" checked>
            <input type="radio" name="contact_type_radio" id="inlineRadio2" value="business">
          </div>

          <!-- Selectable Visual Radio Cards -->
          <div class="contact-type-cards-grid">
            <div class="contact-type-card active" id="card_type_individual" onclick="selectContactTypeRadio('individual')">
              <div class="card-radio-dot"></div>
              <div class="card-icon">
                <i class="fa fa-user-o fa-user"></i>
              </div>
              <div class="card-info">
                <h6 class="card-title">@lang('lang_v1.individual')</h6>
              </div>
            </div>

            <div class="contact-type-card" id="card_type_business" onclick="selectContactTypeRadio('business')">
              <div class="card-radio-dot"></div>
              <div class="card-icon">
                <i class="fa fa-building-o fa-building"></i>
              </div>
              <div class="card-info">
                <h6 class="card-title">@lang('business.business')</h6>
              </div>
            </div>
          </div>

          <!-- Contact Relation Type (Supplier/Customer/Both/Lead) & Contact ID & Customer Group -->
          <div class="row" style="margin-top: 16px;">
            <div class="col-md-4 contact_type_div">
                <div class="form-group">
                    {!! Form::label('type', __('contact.contact_type') . ':*' ) !!}
                    <div class="contact-input-icon-wrap">
                        <i class="fa fa-users contact-input-icon"></i>
                        {!! Form::select('type', $types, $type , ['class' => 'form-control', 'id' => 'contact_type','placeholder' => __('messages.please_select'), 'required']); !!}
                    </div>
                </div>
            </div>

            <div class="col-md-4">
                <div class="form-group">
                    {!! Form::label('contact_id', __('lang_v1.contact_id') . ':') !!}
                    <div class="contact-input-icon-wrap">
                        <i class="fa fa-id-badge contact-input-icon"></i>
                        {!! Form::text('contact_id', null, ['class' => 'form-control','placeholder' => __('lang_v1.contact_id')]); !!}
                    </div>
                    <p class="help-block">
                        @lang('lang_v1.leave_empty_to_autogenerate')
                    </p>
                </div>
            </div>

            <div class="col-md-4 customer_fields">
                <div class="form-group">
                  {!! Form::label('customer_group_id', __('lang_v1.customer_group') . ':') !!}
                  <div class="contact-input-icon-wrap">
                      <i class="fa fa-tags contact-input-icon"></i>
                      {!! Form::select('customer_group_id', $customer_groups, '', ['class' => 'form-control']); !!}
                  </div>
                </div>
            </div>
          </div>
        </div>

        <!-- SECTION 2: INFORMACIÓN PRINCIPAL / PRIMARY INFORMATION -->
        <div class="contact-section-group">
          <div class="contact-section-header">
            <span class="contact-badge-number">2</span>
            <div class="contact-section-title-wrap">
              <h5 class="contact-section-title">@lang('contact.contact')</h5>
            </div>
          </div>

          <div class="row">
            <!-- Business Name (when business selected) -->
            <div class="col-md-6 business" style="display: none;">
                <div class="form-group">
                    {!! Form::label('supplier_business_name', __('business.business_name') . ':*') !!}
                    <div class="contact-input-icon-wrap">
                        <i class="fa fa-briefcase contact-input-icon"></i>
                        {!! Form::text('supplier_business_name', null, ['class' => 'form-control', 'placeholder' => __('business.business_name')]); !!}
                    </div>
                </div>
            </div>

            <!-- Individual Name Fields (when individual selected) -->
            <div class="col-md-6 individual">
                <div class="row" style="margin-left: -5px; margin-right: -5px;">
                    <div class="col-xs-3" style="padding-left: 5px; padding-right: 5px;">
                        <div class="form-group">
                            {!! Form::label('prefix', __( 'business.prefix' ) . ':') !!}
                            {!! Form::text('prefix', null, ['class' => 'form-control', 'placeholder' => __( 'business.prefix_placeholder' ) ]); !!}
                        </div>
                    </div>
                    <div class="col-xs-5" style="padding-left: 5px; padding-right: 5px;">
                        <div class="form-group">
                            {!! Form::label('first_name', __( 'business.first_name' ) . ':*') !!}
                            <div class="contact-input-icon-wrap">
                                <i class="fa fa-user contact-input-icon"></i>
                                {!! Form::text('first_name', null, ['class' => 'form-control', 'required', 'placeholder' => __( 'business.first_name' ) ]); !!}
                            </div>
                        </div>
                    </div>
                    <div class="col-xs-4" style="padding-left: 5px; padding-right: 5px;">
                        <div class="form-group">
                            {!! Form::label('last_name', __( 'business.last_name' ) . ':') !!}
                            {!! Form::text('last_name', null, ['class' => 'form-control', 'placeholder' => __( 'business.last_name' ) ]); !!}
                        </div>
                    </div>
                </div>
            </div>

            <!-- Tax Number (Tax No) -->
            <div class="col-md-6">
                <div class="form-group">
                  {!! Form::label('tax_number', __('contact.tax_no') . ':') !!}
                  <div class="contact-input-icon-wrap">
                      <i class="fa fa-file-text-o fa-id-card-o contact-input-icon"></i>
                      {!! Form::text('tax_number', null, ['class' => 'form-control', 'placeholder' => __('contact.tax_no')]); !!}
                  </div>
                </div>
            </div>

            <div class="clearfix"></div>

            <!-- Phone / Mobile -->
            <div class="col-md-6">
                <div class="form-group">
                    {!! Form::label('mobile', __('contact.mobile') . ':*') !!}
                    <div class="contact-input-icon-wrap">
                        <i class="fa fa-phone contact-input-icon"></i>
                        {!! Form::text('mobile', null, ['class' => 'form-control', 'required', 'placeholder' => __('contact.mobile')]); !!}
                    </div>
                </div>
            </div>

            <!-- Email -->
            <div class="col-md-6">
                <div class="form-group">
                    {!! Form::label('email', __('business.email') . ':') !!}
                    <div class="contact-input-icon-wrap">
                        <i class="fa fa-envelope-o fa-envelope contact-input-icon"></i>
                        {!! Form::email('email', null, ['class' => 'form-control','placeholder' => __('business.email')]); !!}
                    </div>
                </div>
            </div>
          </div>
        </div>

        <!-- SECTION 3: DIRECCIÓN / ADDRESS -->
        <div class="contact-section-group">
          <div class="contact-section-header">
            <span class="contact-badge-number">3</span>
            <div class="contact-section-title-wrap">
              <h5 class="contact-section-title">@lang('business.address')</h5>
            </div>
          </div>

          <div class="row">
            <div class="col-md-12">
                <div class="form-group">
                    <div class="contact-input-icon-wrap">
                        <i class="fa fa-map-marker contact-input-icon"></i>
                        {!! Form::text('address_line_1', null, ['class' => 'form-control', 'placeholder' => __('lang_v1.address_line_1')]); !!}
                    </div>
                </div>
            </div>
          </div>
        </div>

        <!-- SECTION 4: DATOS DE COMPRA / PAGO -->
        <div class="contact-section-group">
          <div class="contact-section-header">
            <span class="contact-badge-number">4</span>
            <div class="contact-section-title-wrap">
              <h5 class="contact-section-title">@lang('contact.pay_term')</h5>
            </div>
          </div>

          <div class="row">
            <!-- Pay Term -->
            <div class="col-md-6 pay_term">
                <div class="form-group">
                    {!! Form::label('pay_term_type', __('contact.pay_term') . ':') !!}
                    <div class="row" style="margin-left: -5px; margin-right: -5px;">
                      <div class="col-xs-6" style="padding-left: 5px; padding-right: 5px;">
                        <div class="contact-input-icon-wrap">
                          <i class="fa fa-credit-card contact-input-icon"></i>
                          {!! Form::select('pay_term_type', ['months' => __('lang_v1.months'), 'days' => __('lang_v1.days')], '', ['class' => 'form-control','placeholder' => __('messages.please_select')]); !!}
                        </div>
                      </div>
                      <div class="col-xs-6" style="padding-left: 5px; padding-right: 5px;">
                        <div class="contact-input-icon-wrap">
                          <i class="fa fa-calendar contact-input-icon"></i>
                          {!! Form::number('pay_term_number', null, ['class' => 'form-control', 'placeholder' => __('contact.pay_term')]); !!}
                        </div>
                      </div>
                    </div>
                </div>
            </div>

            <!-- Opening Balance / Credit Limit -->
            <div class="col-md-6">
                <div class="row" style="margin-left: -5px; margin-right: -5px;">
                  <div class="col-xs-6 opening_balance" style="padding-left: 5px; padding-right: 5px;">
                    <div class="form-group">
                        {!! Form::label('opening_balance', __('lang_v1.opening_balance') . ':') !!}
                        <div class="contact-input-icon-wrap">
                            <i class="fa fa-money contact-input-icon"></i>
                            {!! Form::text('opening_balance', 0, ['class' => 'form-control input_number']); !!}
                        </div>
                    </div>
                  </div>

                  @php
                    $common_settings = session()->get('business.common_settings');
                    $default_credit_limit = !empty($common_settings['default_credit_limit']) ? $common_settings['default_credit_limit'] : null;
                  @endphp
                  <div class="col-xs-6 customer_fields" style="padding-left: 5px; padding-right: 5px;">
                    <div class="form-group">
                        {!! Form::label('credit_limit', __('lang_v1.credit_limit') . ':') !!}
                        <div class="contact-input-icon-wrap">
                            <i class="fa fa-line-chart contact-input-icon"></i>
                            {!! Form::text('credit_limit', $default_credit_limit ?? null, ['class' => 'form-control input_number', 'placeholder' => __('lang_v1.credit_limit')]); !!}
                        </div>
                        <p class="help-block">@lang('lang_v1.credit_limit_help')</p>
                    </div>
                  </div>
                </div>
            </div>
          </div>
        </div>

        <!-- SECTION 5: ASIGNACIÓN / ASSIGNMENT -->
        <div class="contact-section-group">
          <div class="contact-section-header">
            <span class="contact-badge-number">5</span>
            <div class="contact-section-title-wrap">
              <h5 class="contact-section-title">@lang('lang_v1.assigned_to')</h5>
            </div>
          </div>

          <div class="row">
            <!-- User in create customer & supplier -->
            @if(config('constants.enable_contact_assign') && $type !== 'lead')
                <div class="col-md-12">
                    <div class="form-group">
                        <div class="contact-input-icon-wrap">
                            <i class="fa fa-user contact-input-icon"></i>
                            {!! Form::select('assigned_to_users[]', $users ?? [], null , ['class' => 'form-control select2', 'id' => 'assigned_to_users', 'multiple', 'placeholder' => __('messages.please_select'), 'style' => 'width: 100%;']); !!}
                        </div>
                    </div>
                </div>
            @endif

            <!-- User in create leads -->
            <div class="col-md-12 lead_additional_div" style="display: none;">
                <div class="form-group">
                    {!! Form::label('user_id', __('lang_v1.assigned_to') . ':*' ) !!}
                    <div class="contact-input-icon-wrap">
                        <i class="fa fa-user contact-input-icon"></i>
                        {!! Form::select('user_id[]', $users ?? [], null , ['class' => 'form-control select2', 'id' => 'user_id', 'multiple', 'style' => 'width: 100%;']); !!}
                    </div>
                </div>
            </div>
          </div>
        </div>

        <!-- SECTION 6: INFORMACIÓN ADICIONAL / ADDITIONAL INFORMATION (ACCORDION) -->
        <div class="contact-accordion-card" id="contact_accordion_wrap">
            <div class="contact-accordion-header more_btn" data-target="#more_div" onclick="toggleContactAccordion()">
              <div class="contact-accordion-header-left">
                <i class="fa fa-chevron-down contact-accordion-chevron" id="accordion_chevron_icon"></i>
                <div class="contact-accordion-icon-badge">
                  <i class="fa fa-file-text-o"></i>
                </div>
                <div>
                  <h6 class="contact-accordion-title">@lang('lang_v1.more_info')</h6>
                </div>
              </div>
            </div>

            <div id="more_div" class="contact-accordion-body hide">
                {!! Form::hidden('position', null, ['id' => 'position']); !!}

                <!-- Lead Specific Fields -->
                <div class="row lead_additional_div" style="display: none;">
                  <div class="col-md-6">
                    <div class="form-group">
                        {!! Form::label('crm_source', __('lang_v1.source') . ':' ) !!}
                        <div class="contact-input-icon-wrap">
                            <i class="fa fa-search contact-input-icon"></i>
                            {!! Form::select('crm_source', $sources, null , ['class' => 'form-control', 'id' => 'crm_source','placeholder' => __('messages.please_select')]); !!}
                        </div>
                    </div>
                  </div>
                  
                  <div class="col-md-6">
                    <div class="form-group">
                        {!! Form::label('crm_life_stage', __('lang_v1.life_stage') . ':' ) !!}
                        <div class="contact-input-icon-wrap">
                            <i class="fa fa-life-ring contact-input-icon"></i>
                            {!! Form::select('crm_life_stage', $life_stages, null , ['class' => 'form-control', 'id' => 'crm_life_stage','placeholder' => __('messages.please_select')]); !!}
                        </div>
                    </div>
                  </div>
                </div>

                <!-- Secondary Contacts & Personal Info -->
                <div class="row">
                  <div class="col-md-4">
                      <div class="form-group">
                          {!! Form::label('alternate_number', __('contact.alternate_contact_number') . ':') !!}
                          <div class="contact-input-icon-wrap">
                              <i class="fa fa-phone contact-input-icon"></i>
                              {!! Form::text('alternate_number', null, ['class' => 'form-control', 'placeholder' => __('contact.alternate_contact_number')]); !!}
                          </div>
                      </div>
                  </div>
                  <div class="col-md-4">
                      <div class="form-group">
                          {!! Form::label('landline', __('contact.landline') . ':') !!}
                          <div class="contact-input-icon-wrap">
                              <i class="fa fa-phone contact-input-icon"></i>
                              {!! Form::text('landline', null, ['class' => 'form-control', 'placeholder' => __('contact.landline')]); !!}
                          </div>
                      </div>
                  </div>
                  <div class="col-md-4 individual">
                      <div class="form-group">
                          {!! Form::label('dob', __('lang_v1.dob') . ':') !!}
                          <div class="contact-input-icon-wrap">
                              <i class="fa fa-calendar contact-input-icon"></i>
                              {!! Form::text('dob', null, ['class' => 'form-control dob-date-picker','placeholder' => __('lang_v1.dob'), 'readonly']); !!}
                          </div>
                      </div>
                  </div>
                  <div class="col-md-4 individual">
                      <div class="form-group">
                          {!! Form::label('middle_name', __( 'lang_v1.middle_name' ) . ':') !!}
                          {!! Form::text('middle_name', null, ['class' => 'form-control', 'placeholder' => __( 'lang_v1.middle_name' ) ]); !!}
                      </div>
                  </div>
                </div>

                <div class="row"><div class="col-md-12"><hr style="border-top: 1px solid #f1f5f9; margin: 10px 0 18px;"/></div></div>

                <!-- Extended Address Info -->
                <div class="row">
                  <div class="col-md-6">
                      <div class="form-group">
                          {!! Form::label('address_line_2', __('lang_v1.address_line_2') . ':') !!}
                          {!! Form::text('address_line_2', null, ['class' => 'form-control', 'placeholder' => __('lang_v1.address_line_2')]); !!}
                      </div>
                  </div>
                  <div class="col-md-3">
                    <div class="form-group">
                        {!! Form::label('city', __('business.city') . ':') !!}
                        <div class="contact-input-icon-wrap">
                            <i class="fa fa-map-marker contact-input-icon"></i>
                            {!! Form::text('city', null, ['class' => 'form-control', 'placeholder' => __('business.city')]); !!}
                        </div>
                    </div>
                  </div>
                  <div class="col-md-3">
                    <div class="form-group">
                        {!! Form::label('state', __('business.state') . ':') !!}
                        <div class="contact-input-icon-wrap">
                            <i class="fa fa-map-marker contact-input-icon"></i>
                            {!! Form::text('state', null, ['class' => 'form-control', 'placeholder' => __('business.state')]); !!}
                        </div>
                    </div>
                  </div>
                  <div class="col-md-3">
                    <div class="form-group">
                        {!! Form::label('country', __('business.country') . ':') !!}
                        <div class="contact-input-icon-wrap">
                            <i class="fa fa-globe contact-input-icon"></i>
                            {!! Form::text('country', null, ['class' => 'form-control', 'placeholder' => __('business.country')]); !!}
                        </div>
                    </div>
                  </div>
                  <div class="col-md-3">
                    <div class="form-group">
                        {!! Form::label('zip_code', __('business.zip_code') . ':') !!}
                        <div class="contact-input-icon-wrap">
                            <i class="fa fa-map-marker contact-input-icon"></i>
                            {!! Form::text('zip_code', null, ['class' => 'form-control', 'placeholder' => __('business.zip_code_placeholder')]); !!}
                        </div>
                    </div>
                  </div>
                  <div class="col-md-3">
                    <div class="form-group">
                        {!! Form::label('land_mark', __('business.land_mark') . ':') !!}
                        <div class="contact-input-icon-wrap">
                            <i class="fa fa-map-marker contact-input-icon"></i>
                            {!! Form::text('land_mark', null, ['class' => 'form-control', 'placeholder' => __('business.land_mark')]); !!}
                        </div>
                    </div>
                  </div>
                  <div class="col-md-3">
                    <div class="form-group">
                        {!! Form::label('street_name', __('business.street_name') . ':') !!}
                        <div class="contact-input-icon-wrap">
                            <i class="fa fa-map-marker contact-input-icon"></i>
                            {!! Form::text('street_name', null, ['class' => 'form-control', 'placeholder' => __('business.street_name')]); !!}
                        </div>
                    </div>
                  </div>
                  <div class="col-md-3">
                    <div class="form-group">
                        {!! Form::label('building_number', __('business.building_number') . ':') !!}
                        <div class="contact-input-icon-wrap">
                            <i class="fa fa-map-marker contact-input-icon"></i>
                            {!! Form::text('building_number', null, ['class' => 'form-control', 'placeholder' => __('business.building_number')]); !!}
                        </div>
                    </div>
                  </div>
                  <div class="col-md-3">
                    <div class="form-group">
                        {!! Form::label('additional_number', __('business.additional_number_secondary') . ':') !!}
                        <div class="contact-input-icon-wrap">
                            <i class="fa fa-map-marker contact-input-icon"></i>
                            {!! Form::text('additional_number', null, ['class' => 'form-control', 'placeholder' => __('business.additional_number')]); !!}
                        </div>
                    </div>
                  </div>
                </div>

                <!-- Custom Fields 1 to 10 -->
                <div class="row"><div class="col-md-12"><hr style="border-top: 1px solid #f1f5f9; margin: 10px 0 18px;"/></div></div>
                @php
                  $custom_labels = json_decode(session('business.custom_labels'), true);
                  $contact_custom_field1 = !empty($custom_labels['contact']['custom_field_1']) ? $custom_labels['contact']['custom_field_1'] : __('lang_v1.contact_custom_field1');
                  $contact_custom_field2 = !empty($custom_labels['contact']['custom_field_2']) ? $custom_labels['contact']['custom_field_2'] : __('lang_v1.contact_custom_field2');
                  $contact_custom_field3 = !empty($custom_labels['contact']['custom_field_3']) ? $custom_labels['contact']['custom_field_3'] : __('lang_v1.contact_custom_field3');
                  $contact_custom_field4 = !empty($custom_labels['contact']['custom_field_4']) ? $custom_labels['contact']['custom_field_4'] : __('lang_v1.contact_custom_field4');
                  $contact_custom_field5 = !empty($custom_labels['contact']['custom_field_5']) ? $custom_labels['contact']['custom_field_5'] : __('lang_v1.custom_field', ['number' => 5]);
                  $contact_custom_field6 = !empty($custom_labels['contact']['custom_field_6']) ? $custom_labels['contact']['custom_field_6'] : __('lang_v1.custom_field', ['number' => 6]);
                  $contact_custom_field7 = !empty($custom_labels['contact']['custom_field_7']) ? $custom_labels['contact']['custom_field_7'] : __('lang_v1.custom_field', ['number' => 7]);
                  $contact_custom_field8 = !empty($custom_labels['contact']['custom_field_8']) ? $custom_labels['contact']['custom_field_8'] : __('lang_v1.custom_field', ['number' => 8]);
                  $contact_custom_field9 = !empty($custom_labels['contact']['custom_field_9']) ? $custom_labels['contact']['custom_field_9'] : __('lang_v1.custom_field', ['number' => 9]);
                  $contact_custom_field10 = !empty($custom_labels['contact']['custom_field_10']) ? $custom_labels['contact']['custom_field_10'] : __('lang_v1.custom_field', ['number' => 10]);
                @endphp
                <div class="row">
                  <div class="col-md-3">
                    <div class="form-group">
                        {!! Form::label('custom_field1', $contact_custom_field1 . ':') !!}
                        {!! Form::text('custom_field1', null, ['class' => 'form-control', 'placeholder' => $contact_custom_field1]); !!}
                    </div>
                  </div>
                  <div class="col-md-3">
                    <div class="form-group">
                        {!! Form::label('custom_field2', $contact_custom_field2 . ':') !!}
                        {!! Form::text('custom_field2', null, ['class' => 'form-control', 'placeholder' => $contact_custom_field2]); !!}
                    </div>
                  </div>
                  <div class="col-md-3">
                    <div class="form-group">
                        {!! Form::label('custom_field3', $contact_custom_field3 . ':') !!}
                        {!! Form::text('custom_field3', null, ['class' => 'form-control', 'placeholder' => $contact_custom_field3]); !!}
                    </div>
                  </div>
                  <div class="col-md-3">
                    <div class="form-group">
                        {!! Form::label('custom_field4', $contact_custom_field4 . ':') !!}
                        {!! Form::text('custom_field4', null, ['class' => 'form-control', 'placeholder' => $contact_custom_field4]); !!}
                    </div>
                  </div>
                  <div class="col-md-3">
                    <div class="form-group">
                        {!! Form::label('custom_field5', $contact_custom_field5 . ':') !!}
                        {!! Form::text('custom_field5', null, ['class' => 'form-control', 'placeholder' => $contact_custom_field5]); !!}
                    </div>
                  </div>
                  <div class="col-md-3">
                    <div class="form-group">
                        {!! Form::label('custom_field6', $contact_custom_field6 . ':') !!}
                        {!! Form::text('custom_field6', null, ['class' => 'form-control', 'placeholder' => $contact_custom_field6]); !!}
                    </div>
                  </div>
                  <div class="col-md-3">
                    <div class="form-group">
                        {!! Form::label('custom_field7', $contact_custom_field7 . ':') !!}
                        {!! Form::text('custom_field7', null, ['class' => 'form-control', 'placeholder' => $contact_custom_field7]); !!}
                    </div>
                  </div>
                  <div class="col-md-3">
                    <div class="form-group">
                        {!! Form::label('custom_field8', $contact_custom_field8 . ':') !!}
                        {!! Form::text('custom_field8', null, ['class' => 'form-control', 'placeholder' => $contact_custom_field8]); !!}
                    </div>
                  </div>
                  <div class="col-md-3">
                    <div class="form-group">
                        {!! Form::label('custom_field9', $contact_custom_field9 . ':') !!}
                        {!! Form::text('custom_field9', null, ['class' => 'form-control', 'placeholder' => $contact_custom_field9]); !!}
                    </div>
                  </div>
                  <div class="col-md-3">
                    <div class="form-group">
                        {!! Form::label('custom_field10', $contact_custom_field10 . ':') !!}
                        {!! Form::text('custom_field10', null, ['class' => 'form-control', 'placeholder' => $contact_custom_field10]); !!}
                    </div>
                  </div>
                </div>

                <!-- Shipping Address & Custom Fields -->
                <div class="row shipping_addr_div"><div class="col-md-12"><hr style="border-top: 1px solid #f1f5f9; margin: 10px 0 18px;"/></div></div>
                <div class="row shipping_addr_div mb-10">
                  <div class="col-md-12">
                      <div class="form-group">
                        {!! Form::label('shipping_address', __('lang_v1.shipping_address') . ':') !!}
                        <div class="contact-input-icon-wrap">
                          <i class="fa fa-truck contact-input-icon"></i>
                          {!! Form::text('shipping_address', null, ['class' => 'form-control', 'placeholder' => __('lang_v1.search_address'), 'id' => 'shipping_address']); !!}
                        </div>
                      </div>
                      <div class="mb-10" id="map"></div>
                  </div>
                </div>

                @php
                  $shipping_custom_label_1 = !empty($custom_labels['shipping']['custom_field_1']) ? $custom_labels['shipping']['custom_field_1'] : '';
                  $shipping_custom_label_2 = !empty($custom_labels['shipping']['custom_field_2']) ? $custom_labels['shipping']['custom_field_2'] : '';
                  $shipping_custom_label_3 = !empty($custom_labels['shipping']['custom_field_3']) ? $custom_labels['shipping']['custom_field_3'] : '';
                  $shipping_custom_label_4 = !empty($custom_labels['shipping']['custom_field_4']) ? $custom_labels['shipping']['custom_field_4'] : '';
                  $shipping_custom_label_5 = !empty($custom_labels['shipping']['custom_field_5']) ? $custom_labels['shipping']['custom_field_5'] : '';
                @endphp

                <div class="row">
                  @if(!empty($custom_labels['shipping']['is_custom_field_1_contact_default']) && !empty($shipping_custom_label_1))
                      <div class="col-md-4">
                          <div class="form-group">
                              {!! Form::label('shipping_custom_field_1', $shipping_custom_label_1 . ':' ) !!}
                              {!! Form::text('shipping_custom_field_details[shipping_custom_field_1]', null, ['class' => 'form-control','placeholder' => $shipping_custom_label_1]); !!}
                          </div>
                      </div>
                  @endif
                  @if(!empty($custom_labels['shipping']['is_custom_field_2_contact_default']) && !empty($shipping_custom_label_2))
                      <div class="col-md-4">
                          <div class="form-group">
                              {!! Form::label('shipping_custom_field_2', $shipping_custom_label_2 . ':' ) !!}
                              {!! Form::text('shipping_custom_field_details[shipping_custom_field_2]', null, ['class' => 'form-control','placeholder' => $shipping_custom_label_2]); !!}
                          </div>
                      </div>
                  @endif
                  @if(!empty($custom_labels['shipping']['is_custom_field_3_contact_default']) && !empty($shipping_custom_label_3))
                      <div class="col-md-4">
                          <div class="form-group">
                              {!! Form::label('shipping_custom_field_3', $shipping_custom_label_3 . ':' ) !!}
                              {!! Form::text('shipping_custom_field_details[shipping_custom_field_3]', null, ['class' => 'form-control','placeholder' => $shipping_custom_label_3]); !!}
                          </div>
                      </div>
                  @endif
                  @if(!empty($custom_labels['shipping']['is_custom_field_4_contact_default']) && !empty($shipping_custom_label_4))
                      <div class="col-md-4">
                          <div class="form-group">
                              {!! Form::label('shipping_custom_field_4', $shipping_custom_label_4 . ':' ) !!}
                              {!! Form::text('shipping_custom_field_details[shipping_custom_field_4]', null, ['class' => 'form-control','placeholder' => $shipping_custom_label_4]); !!}
                          </div>
                      </div>
                  @endif
                  @if(!empty($custom_labels['shipping']['is_custom_field_5_contact_default']) && !empty($shipping_custom_label_5))
                      <div class="col-md-4">
                          <div class="form-group">
                              {!! Form::label('shipping_custom_field_5', $shipping_custom_label_5 . ':' ) !!}
                              {!! Form::text('shipping_custom_field_details[shipping_custom_field_5]', null, ['class' => 'form-control','placeholder' => $shipping_custom_label_5]); !!}
                          </div>
                      </div>
                  @endif
                </div>

                <!-- Export Fields -->
                @if(!empty($common_settings['is_enabled_export']))
                    <div class="row">
                      <div class="col-md-12 mb-12">
                          <div class="form-check">
                              <input type="checkbox" name="is_export" class="form-check-input" id="is_customer_export">
                              <label class="form-check-label" for="is_customer_export">@lang('lang_v1.is_export')</label>
                          </div>
                      </div>
                      @php
                          $i = 1;
                      @endphp
                      @for($i; $i <= 6 ; $i++)
                          <div class="col-md-4 export_div" style="display: none;">
                              <div class="form-group">
                                  {!! Form::label('export_custom_field_'.$i, __('lang_v1.export_custom_field'.$i).':' ) !!}
                                  {!! Form::text('export_custom_field_'.$i, null, ['class' => 'form-control','placeholder' => __('lang_v1.export_custom_field'.$i)]); !!}
                              </div>
                          </div>
                      @endfor
                    </div>
                @endif
            </div>
        </div>

        @include('layouts.partials.module_form_part')
    </div>
    
    <div class="modal-footer">
      <button type="button" class="btn-contact-cancel" data-dismiss="modal">@lang('messages.close')</button>
      <button type="submit" class="btn-contact-save">
        <i class="fa fa-floppy-o fa-save"></i>
        @lang('messages.save')
      </button>
    </div>

    {!! Form::close() !!}
  
  </div><!-- /.modal-content -->
</div><!-- /.modal-dialog -->

<script>
  function selectContactTypeRadio(val) {
    if (val === 'individual') {
      $('#inlineRadio1').prop('checked', true).trigger('change');
      $('#card_type_individual').addClass('active');
      $('#card_type_business').removeClass('active');
    } else if (val === 'business') {
      $('#inlineRadio2').prop('checked', true).trigger('change');
      $('#card_type_business').addClass('active');
      $('#card_type_individual').removeClass('active');
    }
  }

  function toggleContactAccordion() {
    var card = $('#contact_accordion_wrap');
    card.toggleClass('open');
  }

  $(document).ready(function() {
    $('input[type=radio][name="contact_type_radio"]').on('change', function() {
      if (this.value == 'individual') {
        $('#card_type_individual').addClass('active');
        $('#card_type_business').removeClass('active');
      } else if (this.value == 'business') {
        $('#card_type_business').addClass('active');
        $('#card_type_individual').removeClass('active');
      }
    });
  });
</script>