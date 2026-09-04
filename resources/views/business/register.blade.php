@extends('layouts.auth2')
@section('title', __('lang_v1.register'))

@section('content')
    <div class="col-md-8 col-xs-12 col-md-offset-2 tw-mt-6">
        <div class="tw-p-2 sm:tw-p-3 tw-mb-4 tw-transition-all tw-duration-200 tw-bg-white tw-shadow-sm tw-rounded-xl tw-ring-1 tw-ring-gray-200">
            <div class="tw-flex tw-flex-col tw-gap-4 tw-dw-rounded-box tw-dw-p-6 tw-dw-max-w-md">
                <div class="tw-flex tw-flex-col rounded-2xl tw-dw-p-6 tw-dw-max-w-md text-center">
                    <h1 class="tw-text-lg md:tw-text-xl tw-font-semibold tw-text-[#1e1e1e]">
                        {{ config('app.name', 'YaigoPos') }}
                    </h1>
                    <h2 class="tw-text-sm tw-font-medium tw-text-gray-500">
                        @lang('business.register_and_get_started_in_minutes')
                    </h2>
                </div>

                {!! Form::open([
                    'url' => route('business.postRegister'),
                    'method' => 'post',
                    'id' => 'business_register_form',
                    'files' => true,
                ]) !!}
                @include('business.partials.register_form', ['is_register' => true])
                {!! Form::hidden('package_id', $package_id) !!}
                {!! Form::close() !!}
            </div>
        </div>
    </div>
@stop

@section('javascript')
    <script type="text/javascript">
        $(document).ready(function() {
            $('.change_lang').click(function() {
                window.location = "{{ route('business.getRegister') }}?lang=" + $(this).attr('value');
            });
        })
    </script>
@endsection