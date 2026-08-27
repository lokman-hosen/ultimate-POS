@if(empty($is_admin))
    <h3>@lang('business.business')</h3>
@endif

{!! Form::hidden('language', request()->lang); !!}

<fieldset>
    <div>
        ALl ERRORS:
        @if ($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
    </div>
    <div>
        <legend>@lang('business.select_business_type')</legend>
        <div class="form-group">
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
    </div>

    <legend>@lang('business.business_details'):</legend>
    <div class="col-md-12 col-lg-6 col-xl-4">
        <div class="form-group">
            {!! Form::label('name', __('business.trading_name') . ':*' ) !!}
            {!! Form::text('name', null, ['class' => 'form-control','placeholder' => __('business.trading_name'), 'required']); !!}
        </div>
    </div>

    <!--individual -->
    <div class="col-md-12 col-lg-6 col-xl-4 company-only">
        <div class="form-group">
            {!! Form::label('legal_name', __('business.legal_company_name')) !!}
            {!! Form::text('legal_name', null, ['class' => 'form-control','placeholder' => __('business.legal_company_name')]); !!}
        </div>
    </div>

    <div class="col-md-12 col-lg-6 col-xl-4">
        <div class="form-group">
            {!! Form::label('business_activity', __('business.main_activity') . ':*') !!}
            {!! Form::text('business_activity', null, ['class' => 'form-control', 'placeholder' => __('business.activity_placeholder'), 'required']) !!}
        </div>
    </div>

    <div class="col-md-12 col-lg-6 col-xl-4">
        <div class="form-group">
            {!! Form::label('start_date', __('business.activity_start_date') . ':') !!}
            {!! Form::text('start_date', null, ['class' => 'form-control start-date-picker','placeholder' => __('business.activity_start_date'), 'readonly']); !!}
        </div>
    </div>
    <div class="col-md-12">
        <div class="form-group">
            {!! Form::label('currency_id', __('business.currency') . ':*') !!}
            {!! Form::select('currency_id', $currencies, '110', ['class' => 'form-control select2_register','placeholder' => __('business.currency_placeholder'), 'required']); !!}
        </div>
    </div>
    <div class="clearfix"></div>
    <div class="col-md-12 col-lg-6 col-xl-4">
        <div class="form-group">
            {!! Form::label('business_logo', __('business.upload_logo') . ':') !!}
            {!! Form::file('business_logo', ['accept' => 'image/*']); !!}
        </div>
    </div>
    <div class="col-md-12 col-lg-6 col-xl-4">
        <div class="form-group">
            {!! Form::label('website', __('lang_v1.website') . ':') !!}
            {!! Form::text('website', null, ['class' => 'form-control','placeholder' => __('lang_v1.website')]); !!}
        </div>
    </div>
    <div class="clearfix"></div>
    <legend>Contact Information:</legend>
    <div class="col-md-12 col-lg-6 col-xl-4">
        <div class="form-group">
            {!! Form::label('contact_person', __('business.contact_person_name') . ':*') !!}
            {!! Form::text('contact_person', null, ['class' => 'form-control', 'placeholder' => __('business.contact_person_placeholder'), 'required']) !!}
        </div>
    </div>
    <div class="col-md-12 col-lg-6 col-xl-4">
        <div class="form-group">
            {!! Form::label('mobile', __('lang_v1.business_phone') . ':') !!}
            {!! Form::text('mobile', null, ['class' => 'form-control','placeholder' => __('lang_v1.business_phone'), 'required']); !!}
        </div>
    </div>
    <div class="col-md-12 col-lg-6 col-xl-4">
        <div class="form-group">
            {!! Form::label('contact_email', __('business.business_email') . ':*') !!}
            {!! Form::email('contact_email', null, ['class' => 'form-control', 'placeholder' => __('business.email'), 'required']) !!}
        </div>
    </div>

{{--    <div class="col-md-12 col-lg-6 col-xl-4">--}}
{{--        <div class="form-group">--}}
{{--            {!! Form::label('alternate_number', __('business.alternate_number') . ':') !!}--}}
{{--            {!! Form::text('alternate_number', null, ['class' => 'form-control','placeholder' => __('business.alternate_number')]); !!}--}}
{{--        </div>--}}
{{--    </div>--}}
    <div class="col-md-12 col-lg-6 col-xl-4">
        <div class="form-group">
            {!! Form::label('whatsapp_number', __('business.whatsapp_number') . ':') !!}
            {!! Form::text('whatsapp_number', null, ['class' => 'form-control', 'placeholder' => __('business.whatsapp_placeholder')]) !!}
        </div>
    </div>

    <div class="clearfix"></div>

    <div class="col-md-12 col-lg-6 col-xl-4">
        <div class="form-group">
            {!! Form::label('country', __('business.country') . ':*') !!}
            {!! Form::text('country', 'Spain', ['class' => 'form-control','placeholder' => __('business.country'), 'required']); !!}
        </div>
    </div>

    <div class="col-md-12 col-lg-6 col-xl-4">
        <div class="form-group">
            {!! Form::label('state',__('business.province') . ':*') !!}
            {!! Form::text('state', null, ['class' => 'form-control','placeholder' => __('business.province'), 'required']); !!}
        </div>
    </div>
    <div class="clearfix"></div>
    <div class="col-md-12 col-lg-6 col-xl-4">
        <div class="form-group">
            {!! Form::label('city',__('business.city'). ':*') !!}
            {!! Form::text('city', null, ['class' => 'form-control','placeholder' => __('business.city'), 'required']); !!}
        </div>
    </div>
    <div class="col-md-12 col-lg-6 col-xl-4">
        <div class="form-group">
            {!! Form::label('zip_code', __('business.postal_code') . ':*') !!}
            {!! Form::text('zip_code', null, ['class' => 'form-control','placeholder' => __('business.postal_code'), 'required']); !!}
        </div>
    </div>
    <div class="clearfix"></div>
    <div class="col-md-12 col-lg-6 col-xl-4">
        <div class="form-group">
            {!! Form::label('landmark', __('business.physical_address') . ':*') !!}
            {!! Form::text('landmark', null, ['class' => 'form-control','placeholder' => __('business.physical_address'), 'required']); !!}
        </div>
    </div>

    <div class="col-md-12 col-lg-6 col-xl-4">
        <div class="form-group">
            {!! Form::label('address_line_2', __('business.address_line_2') . ':') !!}
            {!! Form::text('address_line_2', null, ['class' => 'form-control', 'placeholder' => __('business.address_line2_placeholder')]) !!}
        </div>
    </div>
    <div class="col-md-12 col-lg-6 col-xl-4">
        <div class="form-group">
            {!! Form::label('time_zone', __('business.time_zone') . ':*') !!}
            {!! Form::select('time_zone', $timezone_list, config('app.timezone'), ['class' => 'form-control select2_register','placeholder' => __('business.time_zone'), 'required']); !!}
        </div>
    </div>
</fieldset>

<!-- tax details -->
@if(empty($is_admin))
    <h3>@lang('business.business_settings')</h3>

    <fieldset>
        <legend>@lang('business.business_settings'):</legend>
        <!-- when business_type is self_employed:start -->
        <div class="col-md-12 col-lg-6 col-xl-4">
            <div class="form-group">
                {!! Form::label('tax_label_1', __('business.nif_cif') . ':') !!}
                {!! Form::select('tax_label_1', ['NIF' => 'NIF', 'CIF'=>'CIF'], null, ['class' => 'form-control select2_register', 'required', 'style' => 'width:100%;']); !!}
            </div>
        </div>

        <div class="col-md-12 col-lg-6 col-xl-4">
            <div class="form-group">
                {!! Form::label('tax_number_1', __('business.nif_cif') . ':') !!}
                {!! Form::text('tax_number_1', null, ['class' => 'form-control', 'placeholder' => __('business.nif_cif_placeholder')]); !!}
                <small class="help-block">@lang('business.nif_cif_help')</small>
            </div>
        </div>
        <!-- when business_type is self_employed:end -->

        <div class="clearfix"></div>
        <!-- when business_type is company:start -->
        <div class="col-md-12 col-lg-6 col-xl-4 company-only">
            <div class="form-group">
                {!! Form::label('tax_label_2',__('business.representative_dni_nie') . ':') !!}
                {!! Form::select('tax_label_2', ['DNI' => 'DNI', 'NIE'=>'NIE'], null, ['class' => 'form-control select2_register', 'required', 'style' => 'width:100%;']); !!}
            </div>
        </div>

        <div class="col-md-12 col-lg-6 col-xl-4 company-only company-only">
            <div class="form-group">
                {!! Form::label('tax_number_2',__('business.representative_dni_nie') . ':') !!}
                {!! Form::text('tax_number_2', null, ['class' => 'form-control', 'placeholder' => __('business.representative_dni_nie')]); !!}
            </div>
        </div>
        <!-- when business_type is company:end -->
        <div class="clearfix"></div>
        <div class="col-md-12 col-lg-6 col-xl-4">
            <div class="form-group">
                {!! Form::label('business_sector', 'Business Category') !!}
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

{{--        <div class="col-md-12 col-lg-6 col-xl-4">--}}
{{--            <div class="form-group">--}}
{{--                {!! Form::label('package_id', 'Package') !!}--}}
{{--                {!! Form::select('business_sector', [--}}
{{--                    '1'   => 'Monthly',--}}
{{--                    '2'   =>  '6 month (Discount 10%)',--}}
{{--                    '3'   =>  '12 month (Discount 200%)',--}}
{{--                ], 2, ['class' => 'form-control select2_register', 'required', 'style' => 'width:100%;', 'placeholder' => 'Select Package']) !!}--}}
{{--            </div>--}}
{{--        </div>--}}

        <div class="col-md-12 col-lg-6 col-xl-4">
            <div class="form-group">
                {!! Form::label('fy_start_month', __('business.fy_start_month') . ':*') !!} @show_tooltip(__('tooltip.fy_start_month'))
                {!! Form::select('fy_start_month', $months, null, ['class' => 'form-control select2_register', 'required', 'style' => 'width:100%;']); !!}
            </div>
        </div>
        <div class="clearfix"></div>
        <div class="col-md-12 col-lg-6 col-xl-4">
            <div class="form-group">
                {!! Form::label('accounting_method', __('business.accounting_method') . ':*') !!}
                {!! Form::select('accounting_method', $accounting_methods, null, ['class' => 'form-control select2_register', 'required', 'style' => 'width:100%;']); !!}
            </div>
        </div>
    </fieldset>
@endif

<!-- Owner Information -->
@if(empty($is_admin))
    <h3>@lang('business.owner')</h3>
@endif

<fieldset>
    <legend>@lang('business.yaigo_account')</legend>
{{--    <div class="col-md-12 col-lg-6 col-xl-4">--}}
{{--        <div class="form-group">--}}
{{--            {!! Form::label('surname', __('business.prefix') . ':') !!}--}}
{{--            {!! Form::text('surname', null, ['class' => 'form-control','placeholder' => __('business.prefix_placeholder')]); !!}--}}
{{--        </div>--}}
{{--    </div>--}}

    <div class="col-md-12 company-only">
        <div class="checkbox">
            <label>
                {!! Form::checkbox('same_as_rep', 1, false, ['id' => 'same_as_rep']) !!}
               Same as contact Person
            </label>
        </div>
    </div>

    <div class="col-md-12 col-lg-6 col-xl-4">
        <div class="form-group">
            {!! Form::label('first_name', __('business.first_name') . ':*') !!}
            {!! Form::text('first_name', null, ['class' => 'form-control','placeholder' => __('business.first_name'), 'required']); !!}
        </div>
    </div>

    <div class="col-md-12 col-lg-6 col-xl-4">
        <div class="form-group">
            {!! Form::label('last_name', __('business.last_name') . ':') !!}
            {!! Form::text('last_name', null, ['class' => 'form-control','placeholder' =>  __('business.last_name')]); !!}
        </div>
    </div>
    <div class="clearfix"></div>
    <div class="col-md-12 col-lg-6 col-xl-4">
        <div class="form-group">
            {!! Form::label('username', __('business.username') . ':*') !!}
            {!! Form::text('username', null, ['class' => 'form-control','placeholder' => __('business.username'), 'required']); !!}
        </div>
    </div>

    <div class="col-md-12 col-lg-6 col-xl-4">
        <div class="form-group">
            {!! Form::label('email', __('business.email') . ':*') !!}
            {!! Form::text('email', null, ['class' => 'form-control','placeholder' => __('business.email'), 'required']); !!}
        </div>
    </div>

    <div class="clearfix"></div>
    <div class="col-md-12 col-lg-6 col-xl-4">
        <div class="form-group">
            {!! Form::label('password', __('business.password') . ':*') !!}
            {!! Form::password('password', ['class' => 'form-control','placeholder' => __('business.password'), 'required']); !!}
        </div>
    </div>

    <div class="col-md-12 col-lg-6 col-xl-4">
        <div class="form-group">
            {!! Form::label('confirm_password', __('business.confirm_password') . ':*') !!}
            {!! Form::password('confirm_password', ['class' => 'form-control','placeholder' => __('business.confirm_password'), 'required']); !!}
        </div>
    </div>
    <div class="clearfix"></div>
    @if(!empty($system_settings['superadmin_enable_register_tc']) && !empty($is_register))
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
        <div class="clearfix"></div>
    @endif


    @if(config('constants.enable_recaptcha') && !empty($is_register))
        <div class="col-md-12 col-lg-6 col-xl-4">
            <div class="form-group">
                <div id="recaptcha-container"></div>
                @if ($errors->has('g-recaptcha-response'))
                    <span class="text-danger">{{ $errors->first('g-recaptcha-response') }}</span>
                @endif
            </div>
        </div>
    @endif
    <div class="clearfix"></div>
</fieldset>

@if(config('constants.enable_recaptcha') && !empty($is_register))
    <script>
        window.RECAPTCHA_SITE_KEY = "{{ config('constants.google_recaptcha_key') }}";
    </script>
@endif

@section('javascript')
    @parent
<script>
    $(document).ready(function() {
        //$('.reg-form').hide();
        // ---------- Business type toggle ----------
        function toggleBusinessType() {
            var type = $('input[name="business_type"]:checked').val();

            if (type === 'company') {
                $('.company-only').show();
                // Make company-only fields required
                $('#legal_name').prop('required', true);
                $('#tax_label_2').prop('required', true);
                $('#tax_number_2').prop('required', true);
            } else {
                $('.company-only').hide();
                $('#legal_name').prop('required', false);
                $('#tax_label_1').prop('required', true);
                $('#tax_number_1').prop('required', true);

                $('#tax_label_2').prop('required', false);
                $('#tax_number_2').prop('required', false);
            }
        }

        // Run on load and on change
        toggleBusinessType();
        $('input[name="business_type"]').change(toggleBusinessType);

        // ---------- Same as representative auto-fill ----------
        $('#same_as_rep').change(function() {
            if ($(this).is(':checked')) {
                var contactPersonFullName = $('input[name="contact_person"]').val();
                var contactEmail = $('input[name="contact_email"]').val();
                const nameParts = contactPersonFullName.trim().split(/\s+/);
                const firstName = nameParts[0];
                const lastName = nameParts.slice(1).join(" ");

                $('#first_name').val(firstName)
                $('#last_name').val(lastName)
                $('#email').val(contactEmail)

            }
        });
    })
</script>

@endsection