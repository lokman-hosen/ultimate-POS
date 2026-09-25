@php
    $business_type_options = [
        '' => __('business.select_business_type'),
        'self_employed' => __('business.self_employed'),
        'company' => __('business.company_legal_entity'),
    ];

    //The business operates in Spain only: show the Spain / Euro entry translated
    $spain_currency_id = null;
    foreach ($currencies as $currency_id => $currency_info) {
        if (strpos($currency_info, 'Spain - ') === 0) {
            $spain_currency_id = $currency_id;
            $currencies[$currency_id] = __('business.spain_euro');
        }
    }

    $is_company = old('business_type') == 'company';
    $activity_choice = old('business_activity_choice', old('business_sector'));
    $tax_label_1_options = $is_company ? ['CIF' => 'CIF'] : ['DNI' => 'DNI', 'NIE' => 'NIE'];
    $phone_prefixes = $phone_prefixes ?? [];
@endphp

@if(empty($is_admin))
    <h3>@lang('business.business')</h3>
@endif

{!! Form::hidden('language', app()->getLocale()) !!}
{{-- keeps the chosen language on the POST request (validation messages) --}}
{!! Form::hidden('lang', app()->getLocale()) !!}

<fieldset>
    @if ($errors->any())
        <div class="col-md-12">
            <div class="alert alert-danger">@lang('business.fix_errors_below')</div>
        </div>
    @endif

    <legend>@lang('business.business_details'):</legend>

    <div class="col-md-12 col-lg-6 col-xl-4">
        <div class="form-group">
            {!! Form::label('business_type', __('business.business_type') . ':*') !!}
            {!! Form::select('business_type', $business_type_options, old('business_type'), ['class' => 'form-control', 'required', 'id' => 'business_type']) !!}
            @include('business.partials.register_field_error', ['field' => 'business_type'])
        </div>
    </div>

    <div class="col-md-12 col-lg-6 col-xl-4">
        <div class="form-group">
            {!! Form::label('name', __('business.trading_name') . ':*') !!}
            {!! Form::text('name', null, ['class' => 'form-control', 'placeholder' => __('business.trading_name'), 'required', 'maxlength' => 255]) !!}
            @include('business.partials.register_field_error', ['field' => 'name'])
        </div>
    </div>
    <div class="clearfix"></div>

    @if(empty($is_admin))
        {{-- Self-employed: DNI / NIE; company (SL): CIF --}}
        <div class="col-md-12 col-lg-6 col-xl-4">
            <div class="form-group">
                {!! Form::label('tax_label_1', __('business.document_type') . ':*') !!}
                {!! Form::select('tax_label_1', $tax_label_1_options, old('tax_label_1'), ['class' => 'form-control', 'required', 'id' => 'tax_label_1']) !!}
                @include('business.partials.register_field_error', ['field' => 'tax_label_1'])
            </div>
        </div>
        <div class="col-md-12 col-lg-6 col-xl-4">
            <div class="form-group">
                {!! Form::label('tax_number_1', __('business.document_number') . ':*') !!}
                {!! Form::text('tax_number_1', null, ['class' => 'form-control spanish-tax-id', 'placeholder' => \App\Rules\SpanishTaxId::EXAMPLES[array_key_first($tax_label_1_options)], 'required', 'data-type-field' => '#tax_label_1', 'autocomplete' => 'off', 'maxlength' => 9]) !!}
                <small class="help-block tax-id-hint" data-for="#tax_label_1"></small>
                @include('business.partials.register_field_error', ['field' => 'tax_number_1'])
            </div>
        </div>
        <div class="clearfix"></div>
    @endif

    <div class="col-md-12 col-lg-6 col-xl-4 company-only" @if(!$is_company) style="display: none;" @endif>
        <div class="form-group">
            {!! Form::label('legal_name', __('business.legal_company_name') . ':*') !!}
            {!! Form::text('legal_name', null, ['class' => 'form-control', 'placeholder' => __('business.legal_company_name'), 'required', 'maxlength' => 255]) !!}
            @include('business.partials.register_field_error', ['field' => 'legal_name'])
        </div>
    </div>

    <div class="col-md-12 col-lg-6 col-xl-4">
        <div class="form-group">
            {!! Form::label('business_sector', __('business.main_activity') . ':*') !!}
            {!! Form::select('business_sector', ['' => __('business.select_main_activity')] + $business_activities, $activity_choice, ['class' => 'form-control', 'required', 'id' => 'business_sector']) !!}
            @include('business.partials.register_field_error', ['field' => 'business_sector'])
            @include('business.partials.register_field_error', ['field' => 'business_activity'])
        </div>
    </div>
    <div class="col-md-12 col-lg-6 col-xl-4 business-activity-other" @if($activity_choice != 'other') style="display: none;" @endif>
        <div class="form-group">
            {!! Form::label('business_activity_other', __('business.specify_activity') . ':*') !!}
            {!! Form::text('business_activity_other', null, ['class' => 'form-control', 'placeholder' => __('business.specify_activity_placeholder'), 'required', 'maxlength' => 100]) !!}
        </div>
    </div>
    <div class="clearfix"></div>

    <div class="col-md-12 col-lg-6 col-xl-4">
        <div class="form-group">
            {!! Form::label('start_date', __('business.activity_start_date') . ':') !!}
            {!! Form::text('start_date', null, ['class' => 'form-control start-date-picker', 'placeholder' => __('business.activity_start_date'), 'readonly']) !!}
        </div>
    </div>
    <div class="col-md-12 col-lg-6 col-xl-4">
        <div class="form-group">
            {!! Form::label('currency_id', __('business.currency') . ':*') !!}
            {!! Form::select('currency_id', $currencies, old('currency_id', $spain_currency_id), ['class' => 'form-control', 'placeholder' => __('business.currency_placeholder'), 'required']) !!}
            @include('business.partials.register_field_error', ['field' => 'currency_id'])
        </div>
    </div>
    <div class="clearfix"></div>

    <div class="col-md-12 col-lg-6 col-xl-4">
        <div class="form-group">
            {!! Form::label('business_logo', __('business.upload_logo') . ':') !!}
            {!! Form::file('business_logo', ['accept' => 'image/*']) !!}
        </div>
    </div>
    <div class="col-md-12 col-lg-6 col-xl-4">
        <div class="form-group">
            {!! Form::label('website', __('lang_v1.website') . ':') !!}
            {!! Form::text('website', null, ['class' => 'form-control', 'placeholder' => __('business.website_placeholder'), 'maxlength' => 255]) !!}
            @include('business.partials.register_field_error', ['field' => 'website'])
        </div>
    </div>
    <div class="clearfix"></div>

    @if(empty($is_admin))
        <div class="col-md-12 col-lg-6 col-xl-4">
            <div class="form-group">
                {!! Form::label('referred_by', __('business.referred_by') . ':') !!}
                {!! Form::select('referred_by', [
                    '' => __('business.select_referred_by'),
                    'Lokman Hosen'   => 'Lokman Hosen',
                    'Abdul Karim'    => 'Abdul Karim',
                    'Rahim Uddin'    => 'Rahim Uddin',
                    'Karim Ahmed'    => 'Karim Ahmed',
                    'Sohel Rana'     => 'Sohel Rana',
                    'Tanvir Hasan'   => 'Tanvir Hasan',
                    'Rasel Mia'      => 'Rasel Mia',
                    'Imran Hossain'  => 'Imran Hossain',
                    'Sakib Khan'     => 'Sakib Khan',
                    'Jahid Hasan'    => 'Jahid Hasan',
                ], null, ['class' => 'form-control']) !!}
            </div>
        </div>
        <div class="clearfix"></div>

        {{-- Legal representative (SL only) --}}
        <div class="company-only" @if(!$is_company) style="display: none;" @endif>
            <legend>@lang('business.legal_representative'):</legend>
            <div class="col-md-12 col-lg-6 col-xl-4">
                <div class="form-group">
                    {!! Form::label('legal_rep_name', __('business.legal_rep_full_name') . ':*') !!}
                    {!! Form::text('legal_rep_name', null, ['class' => 'form-control', 'placeholder' => __('business.legal_rep_full_name'), 'required', 'maxlength' => 255]) !!}
                    @include('business.partials.register_field_error', ['field' => 'legal_rep_name'])
                </div>
            </div>
            <div class="col-md-12 col-lg-6 col-xl-4">
                <div class="form-group">
                    {!! Form::label('legal_rep_position', __('business.legal_rep_position') . ':*') !!}
                    {!! Form::text('legal_rep_position', null, ['class' => 'form-control', 'placeholder' => __('business.legal_rep_position_placeholder'), 'required', 'maxlength' => 255]) !!}
                    @include('business.partials.register_field_error', ['field' => 'legal_rep_position'])
                </div>
            </div>
            <div class="clearfix"></div>
            <div class="col-md-12 col-lg-6 col-xl-4">
                <div class="form-group">
                    {!! Form::label('tax_label_2', __('business.document_type') . ':*') !!}
                    {!! Form::select('tax_label_2', ['DNI' => 'DNI', 'NIE' => 'NIE'], old('tax_label_2'), ['class' => 'form-control', 'required', 'id' => 'tax_label_2']) !!}
                    @include('business.partials.register_field_error', ['field' => 'tax_label_2'])
                </div>
            </div>
            <div class="col-md-12 col-lg-6 col-xl-4">
                <div class="form-group">
                    {!! Form::label('tax_number_2', __('business.document_number') . ':*') !!}
                    {!! Form::text('tax_number_2', null, ['class' => 'form-control spanish-tax-id', 'placeholder' => \App\Rules\SpanishTaxId::EXAMPLES['DNI'], 'required', 'data-type-field' => '#tax_label_2', 'autocomplete' => 'off', 'maxlength' => 9]) !!}
                    <small class="help-block tax-id-hint" data-for="#tax_label_2"></small>
                    @include('business.partials.register_field_error', ['field' => 'tax_number_2'])
                </div>
            </div>
            <div class="clearfix"></div>
        </div>
    @endif

    <legend>@lang('business.contact_information'):</legend>
    <div class="col-md-12 col-lg-6 col-xl-4">
        <div class="form-group">
            {!! Form::label('contact_person', __('business.contact_person_name') . ':*') !!}
            {!! Form::text('contact_person', null, ['class' => 'form-control', 'placeholder' => __('business.contact_person_placeholder'), 'required', 'maxlength' => 255]) !!}
            @include('business.partials.register_field_error', ['field' => 'contact_person'])
        </div>
    </div>
    <div class="col-md-12 col-lg-6 col-xl-4">
        <div class="form-group">
            {!! Form::label('contact_email', __('business.business_email') . ':*') !!}
            {!! Form::email('contact_email', null, ['class' => 'form-control', 'placeholder' => __('business.email'), 'required', 'maxlength' => 255]) !!}
            @include('business.partials.register_field_error', ['field' => 'contact_email'])
        </div>
    </div>
    <div class="clearfix"></div>
    <div class="col-md-12 col-lg-6 col-xl-4">
        <div class="form-group">
            {!! Form::label('mobile', __('lang_v1.business_telephone') . ':*') !!}
            <div class="input-group register-phone">
                {!! Form::select('mobile_prefix', $phone_prefixes, old('mobile_prefix', '+34'), ['class' => 'form-control phone-prefix', 'id' => 'mobile_prefix', 'aria-label' => __('business.phone_prefix')]) !!}
                {!! Form::tel('mobile', null, ['class' => 'form-control phone-number', 'placeholder' => __('business.phone_placeholder'), 'required', 'data-prefix-field' => '#mobile_prefix', 'inputmode' => 'numeric', 'maxlength' => 14]) !!}
            </div>
            @include('business.partials.register_field_error', ['field' => 'mobile'])
        </div>
    </div>
    <div class="col-md-12 col-lg-6 col-xl-4">
        <div class="form-group">
            {!! Form::label('whatsapp_number', __('business.whatsapp_number') . ':') !!}
            <div class="input-group register-phone">
                {!! Form::select('whatsapp_prefix', $phone_prefixes, old('whatsapp_prefix', '+34'), ['class' => 'form-control phone-prefix', 'id' => 'whatsapp_prefix', 'aria-label' => __('business.phone_prefix')]) !!}
                {!! Form::tel('whatsapp_number', null, ['class' => 'form-control phone-number', 'placeholder' => __('business.phone_placeholder'), 'data-prefix-field' => '#whatsapp_prefix', 'inputmode' => 'numeric', 'maxlength' => 14]) !!}
            </div>
            @include('business.partials.register_field_error', ['field' => 'whatsapp_number'])
            <div class="checkbox">
                <label>
                    {!! Form::checkbox('whatsapp_same_as_mobile', 1, old('whatsapp_same_as_mobile'), ['id' => 'whatsapp_same_as_mobile', 'class' => 'input-check-box']) !!}
                    @lang('business.same_as_contact_number')
                </label>
            </div>
        </div>
    </div>
    <div class="clearfix"></div>

    {{-- Address: Country -> Autonomous community -> Province -> Municipality -> Postal code -> Address --}}
    <legend>@lang('business.address'):</legend>
    <div class="col-md-12 col-lg-6 col-xl-4">
        <div class="form-group">
            {!! Form::label('country', __('business.country') . ':*') !!}
            {!! Form::select('country', ['Spain' => __('business.spain')], 'Spain', ['class' => 'form-control', 'required', 'id' => 'country']) !!}
        </div>
    </div>
    <div class="col-md-12 col-lg-6 col-xl-4">
        <div class="form-group">
            {!! Form::label('community_code', __('business.autonomous_community') . ':*') !!}
            {!! Form::select('community_code', ['' => __('business.select_community')] + $communities, old('community_code'), ['class' => 'form-control', 'required', 'id' => 'community_code']) !!}
            @include('business.partials.register_field_error', ['field' => 'community_code'])
        </div>
    </div>
    <div class="clearfix"></div>
    <div class="col-md-12 col-lg-6 col-xl-4">
        <div class="form-group">
            {!! Form::label('province_code', __('business.province') . ':*') !!}
            {!! Form::select('province_code', ['' => __('business.select_province')], null, ['class' => 'form-control', 'required', 'id' => 'province_code', 'data-old' => old('province_code')]) !!}
            @include('business.partials.register_field_error', ['field' => 'province_code'])
        </div>
    </div>
    <div class="col-md-12 col-lg-6 col-xl-4">
        <div class="form-group">
            {!! Form::label('municipality_code', __('business.city_municipality') . ':*') !!}
            {!! Form::select('municipality_code', ['' => __('business.select_municipality')], null, ['class' => 'form-control', 'required', 'id' => 'municipality_code', 'data-old' => old('municipality_code')]) !!}
            @include('business.partials.register_field_error', ['field' => 'municipality_code'])
        </div>
    </div>
    <div class="clearfix"></div>
    <div class="col-md-12 col-lg-6 col-xl-4">
        <div class="form-group">
            {!! Form::label('zip_code', __('business.postal_code') . ':*') !!}
            {!! Form::text('zip_code', null, ['class' => 'form-control', 'placeholder' => '08001', 'required', 'inputmode' => 'numeric', 'maxlength' => 5]) !!}
            @include('business.partials.register_field_error', ['field' => 'zip_code'])
        </div>
    </div>
    <div class="col-md-12 col-lg-6 col-xl-4">
        <div class="form-group">
            {!! Form::label('landmark', __('business.physical_address') . ':*') !!}
            {!! Form::text('landmark', null, ['class' => 'form-control', 'placeholder' => __('business.physical_address_placeholder'), 'required', 'maxlength' => 255]) !!}
            @include('business.partials.register_field_error', ['field' => 'landmark'])
        </div>
    </div>
    <div class="clearfix"></div>
    <div class="col-md-12 col-lg-6 col-xl-4">
        <div class="form-group">
            {!! Form::label('address_line_2', __('business.address_line_2') . ':') !!}
            {!! Form::text('address_line_2', null, ['class' => 'form-control', 'placeholder' => __('business.address_line2_placeholder'), 'maxlength' => 255]) !!}
            @include('business.partials.register_field_error', ['field' => 'address_line_2'])
        </div>
    </div>
    <div class="clearfix"></div>
</fieldset>

<!-- Owner Information -->
@if(empty($is_admin))
    <h3>@lang('business.owner')</h3>
@endif

<fieldset>
    <legend>@lang('business.yaigo_account'):</legend>

    <div class="col-md-12">
        <div class="checkbox">
            <label>
                {!! Form::checkbox('same_as_rep', 1, false, ['id' => 'same_as_rep', 'class' => 'input-check-box']) !!}
                @lang('business.same_as_contact_person')
            </label>
        </div>
    </div>

    <div class="col-md-12 col-lg-6 col-xl-4">
        <div class="form-group">
            {!! Form::label('first_name', __('business.first_name') . ':*') !!}
            {!! Form::text('first_name', null, ['class' => 'form-control', 'placeholder' => __('business.first_name'), 'required', 'maxlength' => 255]) !!}
            @include('business.partials.register_field_error', ['field' => 'first_name'])
        </div>
    </div>

    <div class="col-md-12 col-lg-6 col-xl-4">
        <div class="form-group">
            {!! Form::label('last_name', __('business.last_name') . ':') !!}
            {!! Form::text('last_name', null, ['class' => 'form-control', 'placeholder' => __('business.last_name')]) !!}
        </div>
    </div>
    <div class="clearfix"></div>
    <div class="col-md-12 col-lg-6 col-xl-4">
        <div class="form-group">
            {!! Form::label('username', __('business.username') . ':*') !!}
            {!! Form::text('username', null, ['class' => 'form-control', 'placeholder' => __('business.username'), 'required', 'autocomplete' => 'username']) !!}
            @include('business.partials.register_field_error', ['field' => 'username'])
        </div>
    </div>

    <div class="col-md-12 col-lg-6 col-xl-4">
        <div class="form-group">
            {!! Form::label('email', __('business.email') . ':*') !!}
            {!! Form::text('email', null, ['class' => 'form-control', 'placeholder' => __('business.email'), 'required', 'autocomplete' => 'email']) !!}
            @include('business.partials.register_field_error', ['field' => 'email'])
        </div>
    </div>

    <div class="clearfix"></div>
    <div class="col-md-12 col-lg-6 col-xl-4">
        <div class="form-group">
            {!! Form::label('password', __('business.password') . ':*') !!}
            {!! Form::password('password', ['class' => 'form-control', 'placeholder' => __('business.password'), 'required', 'autocomplete' => 'new-password']) !!}
            @include('business.partials.register_field_error', ['field' => 'password'])
        </div>
    </div>

    <div class="col-md-12 col-lg-6 col-xl-4">
        <div class="form-group">
            {!! Form::label('confirm_password', __('business.confirm_password') . ':*') !!}
            {!! Form::password('confirm_password', ['class' => 'form-control', 'placeholder' => __('business.confirm_password'), 'required', 'autocomplete' => 'new-password']) !!}
            @include('business.partials.register_field_error', ['field' => 'confirm_password'])
        </div>
    </div>
    <div class="clearfix"></div>
    @if(!empty($system_settings['superadmin_enable_register_tc']) && !empty($is_register))
        <div class="col-md-12">
            <div class="form-group">
                <label>
                    {!! Form::checkbox('accept_tc', 1, false, ['required', 'class' => 'input-check-box']) !!}
                    <a class="terms_condition cursor-pointer" data-toggle="modal" data-target="#tc_modal">
                        @lang('lang_v1.accept_terms_and_conditions') <i></i>
                    </a>
                </label>
            </div>
            @include('business.partials.terms_conditions')
            <div class="form-group">
                <label>
                    {!! Form::checkbox('accept_marketing', 1, false, ['class' => 'input-check-box']) !!}
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
    @php
        $business_register_config = [
            'ine_data_url' => asset('js/data/spain-ine.json?v=' . $asset_v),
            'select_province' => __('business.select_province'),
            'select_municipality' => __('business.select_municipality'),
            'tax_examples' => \App\Rules\SpanishTaxId::EXAMPLES,
            'lang' => [
                'required' => __('business.js_required'),
                'email' => __('business.js_email'),
                'minlength' => __('business.js_minlength'),
                'equal_to' => __('business.js_equal_to'),
                'example' => __('business.document_example'),
                'invalid_document' => __('business.invalid_document_number'),
                'phone_invalid' => __('business.phone_invalid'),
                'website_invalid' => __('business.website_invalid'),
                'postal_code_invalid' => __('business.postal_code_invalid'),
                'postal_code_province_mismatch' => __('business.postal_code_province_mismatch'),
            ],
        ];
    @endphp
    <script>
        window.BUSINESS_REGISTER = {!! json_encode($business_register_config, JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_AMP | JSON_HEX_QUOT | JSON_UNESCAPED_UNICODE) !!};
    </script>
    <script src="{{ asset('js/business_register.js?v=' . $asset_v) }}"></script>
@endsection
