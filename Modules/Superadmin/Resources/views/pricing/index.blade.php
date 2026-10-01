@extends('layouts.auth2')
@section('title', __('superadmin::lang.pricing'))

@section('css')
    {{-- card styles + brand colors live in admin.css; pricing.css holds this page's public layout --}}
    <link rel="stylesheet" href="{{ asset('css/admin.css?v=' . $asset_v) }}">
    <link rel="stylesheet" href="{{ asset('css/pricing.css?v=' . $asset_v) }}">
@endsection

@section('content')
    @include('superadmin::layouts.partials.currency')
    <div class="pos-price-page">
        <div class="pos-card pos-price-head">
            <h1 class="pos-h1 pos-price-heading">@lang('superadmin::lang.pricing')</h1>
            <p class="pos-p">
                @lang('superadmin::lang.choose_pricing_plan', ['app' => config('app.name', 'YaigoPos')])
            </p>

            <!-- Monthly/annual -->
            <div class="pos-price-toggle-row">
                <span class="pos-price-toggle-monthly">@lang('superadmin::lang.monthly')</span>
                <input type="checkbox" id="durationCheck" class="tw-dw-toggle tw-dw-toggle-secondary duration_check"
                       aria-label="@lang('superadmin::lang.annual')" style="margin: 0px" />
                <span class="pos-price-toggle-annual">@lang('superadmin::lang.annual')</span>
            </div>
        </div>

        <div class="pos-price-grid" id="packages">
            @include('superadmin::subscription.partials.packages', [
                'action_type' => 'register',
            ])
        </div>
    </div>
@stop

@section('javascript')
    <script type="text/javascript">
        $(document).ready(function() {
            $('.change_lang').click(function() {
                window.location = "{{ route('pricing')}}?lang=" + $(this).attr('value');
            });

            $('#durationCheck').off('change').on('change', function() {
                var interval = $(this).is(':checked') ? 'years' : 'months';
                set_packages(interval);
            });

            function set_packages(interval) {
                $.ajax({
                    method: 'get',
                    url: "{{ route('package_duration_update') }}",
                    dataType: 'html',
                    data: {
                        interval: interval
                    },
                    success: function(response) {
                        $('#packages').html(response);
                        // this function use for formate currency
                        __currency_convert_recursively($('.price_card'))
                    },
                    error: function(jqXHR, textStatus, errorThrown) {
                        console.error(textStatus, errorThrown);
                    },
                });
            }
            set_packages('months');
        })
    </script>
@endsection