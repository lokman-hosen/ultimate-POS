@if(empty($is_admin))
    <h3>@lang('business.business')</h3>
@endif
{!! Form::hidden('language', request()->lang); !!}

{{-- Wizard steps --}}
<div class="pos-registration-wizard">

    {{-- Progress bar --}}
    <ul class="nav nav-pills nav-justified step-indicator">
        <li class="active" data-step="1"><a href="#">1. @lang('business.business_type')</a></li>
        <li data-step="2"><a href="#">2. @lang('business.business_details')</a></li>
        <li data-step="3"><a href="#">3. @lang('business.owner_settings')</a></li>
    </ul>

    <div class="step-content">

        {{-- STEP 1: Business Type --}}
        <div class="step-pane active" data-step="1">
            <fieldset>
                <legend>@lang('business.select_business_type')</legend>
                <div class="form-group">
                    {!! Form::label('business_type', __('business.business_type') . ':*') !!}
                    <div class="radio">
                        <label>
                            {!! Form::radio('business_type', 'self_employed', old('business_type') == 'self_employed', ['class' => 'business-type-radio', 'required']) !!}
                            @lang('business.self_employed') (Autónomo)
                        </label>
                    </div>
                    <div class="radio">
                        <label>
                            {!! Form::radio('business_type', 'company', old('business_type') == 'company', ['class' => 'business-type-radio', 'required']) !!}
                            @lang('business.company_legal_entity') (Empresa)
                        </label>
                    </div>
                    @if ($errors->has('business_type'))
                        <span class="text-danger">{{ $errors->first('business_type') }}</span>
                    @endif
                </div>
            </fieldset>
            <div class="text-right">
                <button type="button" class="btn btn-primary next-step">@lang('business.next')</button>
            </div>
        </div>

        {{-- STEP 2: Business Details (conditional) --}}
        <div class="step-pane" data-step="2">
            <fieldset>
                <legend>@lang('business.business_details')</legend>

                {{-- Common fields for both types --}}
                <div class="row">
                    <div class="col-md-12 col-lg-6">
                        <div class="form-group">
                            {!! Form::label('name', __('business.trading_name') . ':*' ) !!}
                            {!! Form::text('name', null, ['class' => 'form-control', 'placeholder' => __('business.trading_name_placeholder'), 'required']) !!}
                        </div>
                    </div>
                    <div class="col-md-12 col-lg-6 company-only">
                        <div class="form-group">
                            {!! Form::label('legal_name', __('business.legal_company_name') . ':*') !!}
                            {!! Form::text('legal_name', null, ['class' => 'form-control', 'placeholder' => __('business.legal_name_placeholder')]) !!}
                        </div>
                    </div>
                </div>

                {{-- Tax number (NIF/CIF) --}}
                <div class="row">
                    <div class="col-md-12 col-lg-6">
                        <div class="form-group">
                            {!! Form::label('tax_number_1', __('business.nif_cif') . ':*') !!}
                            {!! Form::text('tax_number_1', null, ['class' => 'form-control', 'placeholder' => __('business.nif_cif_placeholder'), 'required']) !!}
                            <small class="help-block">@lang('business.nif_cif_help')</small>
                        </div>
                    </div>
                    <div class="col-md-12 col-lg-6 company-only">
                        <div class="form-group">
                            {!! Form::label('rep_dni', __('business.representative_dni_nie') . ':*') !!}
                            {!! Form::text('rep_dni', null, ['class' => 'form-control', 'placeholder' => __('business.representative_dni_placeholder')]) !!}
                        </div>
                    </div>
                </div>

                {{-- Activity & start date --}}
                <div class="row">
                    <div class="col-md-12 col-lg-6">
                        <div class="form-group">
                            {!! Form::label('business_activity', __('business.main_activity') . ':*') !!}
                            {!! Form::text('business_activity', null, ['class' => 'form-control', 'placeholder' => __('business.activity_placeholder'), 'required']) !!}
                        </div>
                    </div>
                    <div class="col-md-12 col-lg-6">
                        <div class="form-group">
                            {!! Form::label('start_date', __('business.activity_start_date') . ':*') !!}
                            {!! Form::text('start_date', null, ['class' => 'form-control start-date-picker', 'placeholder' => __('business.start_date'), 'readonly', 'required']) !!}
                        </div>
                    </div>
                </div>

                {{-- Contact information --}}
                <div class="row">
                    <div class="col-md-12 col-lg-6">
                        <div class="form-group">
                            {!! Form::label('contact_person', __('business.contact_person_name') . ':*') !!}
                            {!! Form::text('contact_person', null, ['class' => 'form-control', 'placeholder' => __('business.contact_person_placeholder'), 'required']) !!}
                        </div>
                    </div>
                    <div class="col-md-12 col-lg-6 company-only">
                        <div class="checkbox">
                            <label>
                                {!! Form::checkbox('same_as_rep', 1, false, ['id' => 'same_as_rep']) !!}
                                @lang('business.same_as_legal_representative')
                            </label>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-12 col-lg-6">
                        <div class="form-group">
                            {!! Form::label('email', __('business.business_email') . ':*') !!}
                            {!! Form::email('email', null, ['class' => 'form-control', 'placeholder' => __('business.email'), 'required']) !!}
                        </div>
                    </div>
                    <div class="col-md-12 col-lg-6">
                        <div class="form-group">
                            {!! Form::label('mobile', __('business.business_phone') . ':*') !!}
                            {!! Form::text('mobile', null, ['class' => 'form-control', 'placeholder' => __('business.phone_placeholder'), 'required']) !!}
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-12 col-lg-6">
                        <div class="form-group">
                            {!! Form::label('whatsapp_number', __('business.whatsapp_number') . ':') !!}
                            {!! Form::text('whatsapp_number', null, ['class' => 'form-control', 'placeholder' => __('business.whatsapp_placeholder')]) !!}
                        </div>
                    </div>
                    <div class="col-md-12 col-lg-6">
                        <div class="form-group">
                            {!! Form::label('alternate_number', __('business.alternate_phone') . ':') !!}
                            {!! Form::text('alternate_number', null, ['class' => 'form-control', 'placeholder' => __('business.alternate_number')]) !!}
                        </div>
                    </div>
                </div>

                {{-- Address --}}
                <div class="row">
                    <div class="col-md-12">
                        <div class="form-group">
                            {!! Form::label('address_line_1', __('business.fiscal_address') . ':*') !!}
                            {!! Form::text('address_line_1', null, ['class' => 'form-control', 'placeholder' => __('business.address_line1_placeholder'), 'required']) !!}
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <div class="form-group">
                            {!! Form::label('address_line_2', __('business.address_line_2') . ':') !!}
                            {!! Form::text('address_line_2', null, ['class' => 'form-control', 'placeholder' => __('business.address_line2_placeholder')]) !!}
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-12 col-lg-4">
                        <div class="form-group">
                            {!! Form::label('zip_code', __('business.postal_code') . ':*') !!}
                            {!! Form::text('zip_code', null, ['class' => 'form-control', 'placeholder' => __('business.zip_code_placeholder'), 'required']) !!}
                        </div>
                    </div>
                    <div class="col-md-12 col-lg-4">
                        <div class="form-group">
                            {!! Form::label('city', __('business.city') . ':*') !!}
                            {!! Form::text('city', null, ['class' => 'form-control', 'placeholder' => __('business.city'), 'required']) !!}
                        </div>
                    </div>
                    <div class="col-md-12 col-lg-4">
                        <div class="form-group">
                            {!! Form::label('state', __('business.province') . ':*') !!}
                            {!! Form::text('state', null, ['class' => 'form-control', 'placeholder' => __('business.province'), 'required']) !!}
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-12 col-lg-6">
                        <div class="form-group">
                            {!! Form::label('country', __('business.country') . ':*') !!}
                            {!! Form::text('country', 'Spain', ['class' => 'form-control', 'placeholder' => __('business.country'), 'required', 'readonly']) !!}
                        </div>
                    </div>
                    <div class="col-md-12 col-lg-6">
                        <div class="form-group">
                            {!! Form::label('landmark', __('business.landmark') . ':') !!}
                            {!! Form::text('landmark', null, ['class' => 'form-control', 'placeholder' => __('business.landmark')]) !!}
                        </div>
                    </div>
                </div>

                {{-- Website & Logo --}}
                <div class="row">
                    <div class="col-md-12 col-lg-6">
                        <div class="form-group">
                            {!! Form::label('website', __('lang_v1.website') . ':') !!}
                            {!! Form::url('website', null, ['class' => 'form-control', 'placeholder' => __('lang_v1.website')]) !!}
                        </div>
                    </div>
                    <div class="col-md-12 col-lg-6">
                        <div class="form-group">
                            {!! Form::label('business_logo', __('business.upload_logo') . ':') !!}
                            {!! Form::file('business_logo', ['accept' => 'image/*']) !!}
                        </div>
                    </div>
                </div>

                {{-- Currency & Timezone --}}
                <div class="row">
                    <div class="col-md-12 col-lg-6">
                        <div class="form-group">
                            {!! Form::label('currency_id', __('business.currency') . ':*') !!}
                            {!! Form::select('currency_id', $currencies, '', ['class' => 'form-control select2_register', 'placeholder' => __('business.currency_placeholder'), 'required']) !!}
                        </div>
                    </div>
                    <div class="col-md-12 col-lg-6">
                        <div class="form-group">
                            {!! Form::label('time_zone', __('business.time_zone') . ':*') !!}
                            {!! Form::select('time_zone', $timezone_list, config('app.timezone'), ['class' => 'form-control select2_register', 'placeholder' => __('business.time_zone'), 'required']) !!}
                        </div>
                    </div>
                </div>

            </fieldset>

            <div class="text-left">
                <button type="button" class="btn btn-default prev-step">@lang('business.previous')</button>
            </div>
            <div class="text-right">
                <button type="button" class="btn btn-primary next-step">@lang('business.next')</button>
            </div>
        </div>

        {{-- STEP 3: Owner / Representative & Settings --}}
        <div class="step-pane" data-step="3">
            <fieldset>
                <legend>@lang('business.owner_representative_info')</legend>

                {{-- Owner/Rep name --}}
                <div class="row">
                    <div class="col-md-12 col-lg-4">
                        <div class="form-group">
                            {!! Form::label('surname', __('business.prefix') . ':') !!}
                            {!! Form::text('surname', null, ['class' => 'form-control', 'placeholder' => __('business.prefix_placeholder')]) !!}
                        </div>
                    </div>
                    <div class="col-md-12 col-lg-4">
                        <div class="form-group">
                            {!! Form::label('first_name', __('business.first_name') . ':*') !!}
                            {!! Form::text('first_name', null, ['class' => 'form-control', 'placeholder' => __('business.first_name'), 'required']) !!}
                        </div>
                    </div>
                    <div class="col-md-12 col-lg-4">
                        <div class="form-group">
                            {!! Form::label('last_name', __('business.last_name') . ':*') !!}
                            {!! Form::text('last_name', null, ['class' => 'form-control', 'placeholder' => __('business.last_name'), 'required']) !!}
                        </div>
                    </div>
                </div>

                {{-- Username & password --}}
                <div class="row">
                    <div class="col-md-12 col-lg-6">
                        <div class="form-group">
                            {!! Form::label('username', __('business.username') . ':*') !!}
                            {!! Form::text('username', null, ['class' => 'form-control', 'placeholder' => __('business.username'), 'required']) !!}
                        </div>
                    </div>
                    <div class="col-md-12 col-lg-6">
                        <div class="form-group">
                            {!! Form::label('email', __('business.email') . ':*') !!}
                            {!! Form::email('email', null, ['class' => 'form-control', 'placeholder' => __('business.email'), 'required']) !!}
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-12 col-lg-6">
                        <div class="form-group">
                            {!! Form::label('password', __('business.password') . ':*') !!}
                            {!! Form::password('password', ['class' => 'form-control', 'placeholder' => __('business.password'), 'required']) !!}
                        </div>
                    </div>
                    <div class="col-md-12 col-lg-6">
                        <div class="form-group">
                            {!! Form::label('confirm_password', __('business.confirm_password') . ':*') !!}
                            {!! Form::password('confirm_password', ['class' => 'form-control', 'placeholder' => __('business.confirm_password'), 'required']) !!}
                        </div>
                    </div>
                </div>
            </fieldset>

            <fieldset>
                <legend>@lang('business.billing_invoice_settings')</legend>
                <div class="row">
                    <div class="col-md-12 col-lg-6">
                        <div class="form-group">
                            {!! Form::label('default_tax_rate', __('business.default_iva_rate') . ':*') !!}
                            {!! Form::select('default_tax_rate', [
                                '21' => '21%',
                                '10' => '10%',
                                '4'  => '4%',
                                '0'  => '0%',
                            ], '21', ['class' => 'form-control select2_register', 'required', 'style' => 'width:100%;']) !!}
                        </div>
                    </div>
                    <div class="col-md-12 col-lg-6">
                        <div class="form-group">
                            {!! Form::label('invoice_language', __('business.invoice_language') . ':*') !!}
                            {!! Form::select('invoice_language', [
                                'es' => __('business.spanish'),
                                'en' => __('business.english'),
                                'ca' => __('business.catalan'),
                            ], 'es', ['class' => 'form-control select2_register', 'required', 'style' => 'width:100%;']) !!}
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12 col-lg-6">
                        <div class="form-group">
                            {!! Form::label('business_sector', __('business.business_sector') . ':*') !!}
                            {!! Form::select('business_sector', [
                                'restaurant'   => __('business.restaurant'),
                                'cafe'         => __('business.cafe'),
                                'fast_food'    => __('business.fast_food'),
                                'bakery'       => __('business.bakery'),
                                'supermarket'  => __('business.supermarket'),
                                'grocery'      => __('business.grocery_store'),
                                'butcher'      => __('business.butcher_shop'),
                                'clothing'     => __('business.clothing_store'),
                                'hairdresser'  => __('business.hairdresser_beauty'),
                                'retail'       => __('business.retail_store'),
                                'hotel'        => __('business.hotel'),
                                'pharmacy'     => __('business.pharmacy'),
                                'other'        => __('business.other'),
                            ], null, ['class' => 'form-control select2_register', 'required', 'style' => 'width:100%;', 'placeholder' => __('business.select_sector')]) !!}
                        </div>
                    </div>
                    <div class="col-md-12 col-lg-6">
                        <div class="form-group">
                            {!! Form::label('pos_terminals', __('business.number_of_pos_terminals') . ':*') !!}
                            {!! Form::number('pos_terminals', 1, ['class' => 'form-control', 'placeholder' => __('business.pos_terminals_placeholder'), 'required', 'min' => 1]) !!}
                        </div>
                    </div>
                </div>
            </fieldset>

            <fieldset>
                <legend>@lang('business.additional')</legend>
                <div class="row">
                    <div class="col-md-12">
                        @if(!empty($system_settings['superadmin_enable_register_tc']) && !empty($is_register))
                            <div class="form-group">
                                <label>
                                    {!! Form::checkbox('accept_tc', 0, false, ['required', 'class' => 'input-check-box']) !!}
                                    <a class="terms_condition cursor-pointer" data-toggle="modal" data-target="#tc_modal">
                                        @lang('lang_v1.accept_terms_and_conditions') <i></i>
                                    </a>
                                </label>
                            </div>
                            @include('business.partials.terms_conditions')
                        @endif
                        <div class="form-group">
                            <label>
                                {!! Form::checkbox('accept_marketing', 1, false) !!}
                                @lang('business.accept_marketing_communications')
                            </label>
                        </div>
                    </div>
                </div>
                @if(config('constants.enable_recaptcha') && !empty($is_register))
                    <div class="row">
                        <div class="col-md-12 col-lg-6">
                            <div class="form-group">
                                <div id="recaptcha-container"></div>
                                @if ($errors->has('g-recaptcha-response'))
                                    <span class="text-danger">{{ $errors->first('g-recaptcha-response') }}</span>
                                @endif
                            </div>
                        </div>
                    </div>
                @endif
            </fieldset>

            {{-- Hidden fields for system --}}
            {!! Form::hidden('verifactu_status', 'pending') !!}  {{-- System managed --}}

            <div class="text-left">
                <button type="button" class="btn btn-default prev-step">@lang('business.previous')</button>
            </div>
            <div class="text-right">
                <button type="submit" class="btn btn-success">@lang('business.register')</button>
            </div>
        </div>

    </div>{{-- /step-content --}}
</div>{{-- /pos-registration-wizard --}}

@if(config('constants.enable_recaptcha') && !empty($is_register))
    <script>
        window.RECAPTCHA_SITE_KEY = "{{ config('constants.google_recaptcha_key') }}";
    </script>
@endif

{{-- Step navigation & conditional logic --}}
@section('javascript')
    @parent
    <script>
        $(document).ready(function() {
            // ---------- Step wizard ----------
            var currentStep = 1;
            var totalSteps = 3;

            function showStep(step) {
                $('.step-pane').removeClass('active').hide();
                $('.step-pane[data-step="' + step + '"]').addClass('active').show();
                $('.step-indicator li').removeClass('active');
                $('.step-indicator li[data-step="' + step + '"]').addClass('active');
                currentStep = step;
            }

            $('.next-step').click(function() {
                var next = currentStep + 1;
                if (next <= totalSteps) {
                    // Validate current step fields? (optional)
                    showStep(next);
                }
            });

            $('.prev-step').click(function() {
                var prev = currentStep - 1;
                if (prev >= 1) {
                    showStep(prev);
                }
            });

            // Initially show step 1
            showStep(1);

            // ---------- Business type toggle ----------
            function toggleBusinessType() {
                var type = $('input[name="business_type"]:checked').val();
                if (type === 'company') {
                    $('.company-only').show();
                    // Make company-only fields required
                    $('#legal_name').prop('required', true);
                    $('#rep_dni').prop('required', true);
                } else {
                    $('.company-only').hide();
                    $('#legal_name').prop('required', false);
                    $('#rep_dni').prop('required', false);
                }
            }

            // Run on load and on change
            toggleBusinessType();
            $('input[name="business_type"]').change(toggleBusinessType);

            // ---------- Same as representative auto-fill ----------
            $('#same_as_rep').change(function() {
                if ($(this).is(':checked')) {
                    var firstName = $('input[name="first_name"]').val();
                    var lastName = $('input[name="last_name"]').val();
                    var fullName = (firstName ? firstName : '') + ' ' + (lastName ? lastName : '');
                    if (fullName.trim() !== '') {
                        $('input[name="contact_person"]').val(fullName.trim());
                    } else {
                        // If empty, show placeholder or clear
                        $('input[name="contact_person"]').val('');
                    }
                }
            });

            // Also auto-fill contact_person when owner name changes (if checkbox checked)
            $('input[name="first_name"], input[name="last_name"]').on('input', function() {
                if ($('#same_as_rep').is(':checked')) {
                    var firstName = $('input[name="first_name"]').val();
                    var lastName = $('input[name="last_name"]').val();
                    var fullName = (firstName ? firstName : '') + ' ' + (lastName ? lastName : '');
                    $('input[name="contact_person"]').val(fullName.trim());
                }
            });

            // ---------- Date picker ----------
            $('.start-date-picker').datepicker({
                format: 'dd-mm-yyyy',
                autoclose: true,
                todayHighlight: true
            });

            // ---------- Select2 ----------
            $('.select2_register').select2();

        });
    </script>
@endsection