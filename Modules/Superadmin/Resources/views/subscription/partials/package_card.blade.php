<div class="col-md-4 tw-mb-5 {{ $package->interval }} tw-relative price_card">
    <div class="pos-price-card">

        @if ($package->mark_package_as_popular == 1)
            <div class="pos-price-badge">
                @lang('superadmin::lang.popular')
            </div>
        @endif

        <div class="tw-flex tw-flex-col tw-text-center">
            <h2 class="pos-price-name">{{ $package->name }}</h2>

            <h3 class="pos-price-amount">
                @php
                    $interval_type = !empty($intervals[$package->interval])
                        ? $intervals[$package->interval]
                        : __('lang_v1.' . $package->interval);
                @endphp
                @if ($package->price != 0)
                    <span class="display_currency" data-use_page_currency="true" data-currency_symbol="true">
                        {{ $package->price }}
                    </span>

                    <span class="pos-price-interval">/ {{ $package->interval_count }} {{ $interval_type }}</span>
                @else
                    <span class="pos-price-free">
                        @lang('superadmin::lang.free_for_duration', ['duration' => $package->interval_count . ' ' . $interval_type])
                    </span>
                @endif
            </h3>

            <span class="pos-price-desc">{{ $package->description }}</span>
        </div>

        <!-- Features -->
        <div class="pos-price-features">
            <div class="pos-price-feature">
                <span class="pos-price-feature-icon"><i class="fa fa-check"></i></span>
                @if ($package->location_count == 0)
                    @lang('superadmin::lang.unlimited')
                @else
                    {{ $package->location_count }}
                @endif

                @lang('business.business_locations')
            </div>
            <div class="pos-price-feature">
                <span class="pos-price-feature-icon"><i class="fa fa-check"></i></span>
                @if ($package->user_count == 0)
                    @lang('superadmin::lang.unlimited')
                @else
                    {{ $package->user_count }}
                @endif

                @lang('superadmin::lang.users')
            </div>
            <div class="pos-price-feature">
                <span class="pos-price-feature-icon"><i class="fa fa-check"></i></span>
                @if ($package->product_count == 0)
                    @lang('superadmin::lang.unlimited')
                @else
                    {{ $package->product_count }}
                @endif

                @lang('superadmin::lang.products')
            </div>
            <div class="pos-price-feature">
                <span class="pos-price-feature-icon"><i class="fa fa-check"></i></span>
                @if ($package->invoice_count == 0)
                    @lang('superadmin::lang.unlimited')
                @else
                    {{ $package->invoice_count }}
                @endif

                @lang('superadmin::lang.invoices')
            </div>

            @if (!empty($package->custom_permissions))
                @foreach ($package->custom_permissions as $permission => $value)
                    @isset($permission_formatted[$permission])
                        <div class="pos-price-feature">
                            <span class="pos-price-feature-icon"><i class="fa fa-check"></i></span>
                            {{ $permission_formatted[$permission] }}
                        </div>
                    @endisset
                @endforeach
            @endif

            @if ($package->trial_days != 0)
                <div class="pos-price-feature">
                    <span class="pos-price-feature-icon"><i class="fa fa-check"></i></span>
                    {{ $package->trial_days }} @lang('superadmin::lang.trial_days')
                </div>
            @endif
        </div>

        @if ($package->enable_custom_link == 1)
            <a href="{{ $package->custom_link }}" class="pos-price-cta">{{ $package->custom_link_text }}</a>
        @else
            @if (isset($action_type) && $action_type == 'register')
                <a href="{{ route('business.getRegister') }}?package={{ $package->id }}" class="pos-price-cta">
                    @if ($package->price != 0)
                        @lang('superadmin::lang.register_subscribe')
                    @else
                        @lang('superadmin::lang.register_free')
                    @endif
                </a>
            @else
                <a href="{{ action([\Modules\Superadmin\Http\Controllers\SubscriptionController::class, 'pay'], [$package->id]) }}" class="pos-price-cta">
                    @if ($package->price != 0)
                        @lang('superadmin::lang.pay_and_subscribe')
                    @else
                        @lang('superadmin::lang.subscribe')
                    @endif
                </a>
            @endif
        @endif
    </div>
</div>