{{--
    Quick access shortcuts built from the already permission-filtered sidebar menu
    (App\Http\Middleware\AdminSidebarMenu + modules' modifyAdminMenu), so the tiles
    always match what the logged-in user can see in the sidebar.
--}}
@php
    $qa_icons = [
        'add' => '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.75" stroke-linecap="round" stroke-linejoin="round"><path d="M12 5v14"/><path d="M5 12h14"/></svg>',
        'import' => '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.75" stroke-linecap="round" stroke-linejoin="round"><path d="M4 17v2a2 2 0 0 0 2 2h12a2 2 0 0 0 2 -2v-2"/><path d="M7 9l5 -5l5 5"/><path d="M12 4v12"/></svg>',
        'report' => '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.75" stroke-linecap="round" stroke-linejoin="round"><path d="M3 3v18h18"/><path d="M7 15l4 -4l3 3l5 -6"/></svg>',
        'link' => '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.75" stroke-linecap="round" stroke-linejoin="round"><path d="M4 6h16"/><path d="M4 12h16"/><path d="M4 18h10"/></svg>',
    ];

    // Subtitle (lang/*/home.php key) per menu, keyed by the URL relative to the app root
    $qa_description_keys = [
        'superadmin' => 'qa_superadmin',
        'users' => 'qa_users',
        'roles' => 'qa_roles',
        'sales-commission-agents' => 'qa_commission_agents',
        'contacts?type=supplier' => 'vendor_directory',
        'contacts?type=customer' => 'directory_balances',
        'customer-group' => 'qa_customer_groups',
        'contacts/import' => 'qa_import_contacts',
        'contacts/map' => 'qa_contacts_map',
        'products' => 'catalog_pricing_stock',
        'products/create' => 'qa_add_product',
        'update-product-price' => 'qa_update_price',
        'labels/show' => 'qa_print_labels',
        'variation-templates' => 'qa_variations',
        'import-products' => 'qa_import_products',
        'import-opening-stock' => 'qa_import_opening_stock',
        'selling-price-group' => 'qa_selling_price_group',
        'units' => 'qa_units',
        'taxonomies?type=product' => 'qa_categories',
        'brands' => 'qa_brands',
        'warranties' => 'qa_warranties',
        'purchase-requisition' => 'qa_purchase_requisition',
        'purchase-order' => 'qa_purchase_order',
        'purchases' => 'orders_from_suppliers',
        'purchases/create' => 'qa_add_purchase',
        'purchase-return' => 'qa_purchase_return',
        'sales-order' => 'qa_sales_order',
        'sells' => 'all_orders_invoices',
        'sells/create' => 'qa_add_sale',
        'pos' => 'qa_list_pos',
        'pos/create' => 'take_order',
        'sells/create?status=draft' => 'qa_add_draft',
        'sells/drafts' => 'qa_list_drafts',
        'sells/create?status=quotation' => 'qa_add_quotation',
        'sells/quotations' => 'qa_list_quotations',
        'sell-return' => 'qa_sell_return',
        'shipments' => 'qa_shipments',
        'discount' => 'qa_discounts',
        'sells/subscriptions' => 'qa_subscriptions',
        'import-sales' => 'qa_import_sales',
        'stock-transfers' => 'move_stock_between_locations',
        'stock-transfers/create' => 'qa_add_stock_transfer',
        'stock-adjustments' => 'qa_stock_adjustments',
        'stock-adjustments/create' => 'qa_add_stock_adjustment',
        'expenses' => 'track_categorize_spend',
        'expenses/create' => 'qa_add_expense',
        'expense-categories' => 'qa_expense_categories',
        'account/account' => 'qa_accounts',
        'account/balance-sheet' => 'qa_balance_sheet',
        'account/trial-balance' => 'qa_trial_balance',
        'account/cash-flow' => 'qa_cash_flow',
        'account/payment-account-report' => 'qa_payment_account_report',
        'reports/profit-loss' => 'qa_profit_loss',
        'reports/purchase-report' => 'qa_report_606',
        'reports/sale-report' => 'qa_report_607',
        'reports/purchase-sell' => 'qa_purchase_sell',
        'reports/tax-report' => 'qa_tax_report',
        'reports/customer-supplier' => 'qa_contacts_report',
        'reports/customer-group' => 'qa_customer_groups_report',
        'reports/stock-report' => 'qa_stock_report',
        'reports/stock-expiry' => 'qa_stock_expiry',
        'reports/lot-report' => 'qa_lot_report',
        'reports/stock-adjustment-report' => 'qa_stock_adjustment_report',
        'reports/trending-products' => 'qa_trending_products',
        'reports/items-report' => 'qa_items_report',
        'reports/product-purchase-report' => 'qa_product_purchase_report',
        'reports/product-sell-report' => 'qa_product_sell_report',
        'reports/purchase-sale-product' => 'qa_purchase_sale_product',
        'reports/purchase-payment-report' => 'qa_purchase_payment_report',
        'reports/sell-payment-report' => 'qa_sell_payment_report',
        'reports/payment-by-age-report' => 'qa_payment_by_age',
        'reports/expense-report' => 'qa_expense_report',
        'reports/register-report' => 'qa_register_report',
        'reports/sales-representative-report' => 'qa_sales_rep_report',
        'reports/table-report' => 'qa_table_report',
        'reports/gst-sales-report' => 'qa_gst_sales',
        'reports/gst-purchase-report' => 'qa_gst_purchases',
        'reports/service-staff-report' => 'qa_service_staff_report',
        'reports/activity-log' => 'qa_activity_log',
        'backup' => 'qa_backup',
        'manage-modules' => 'qa_modules',
        'bookings' => 'qa_bookings',
        'modules/kitchen' => 'qa_kitchen',
        'modules/orders' => 'qa_orders',
        'notification-templates' => 'qa_notification_templates',
        'business/settings' => 'qa_business_settings',
        'business-location' => 'qa_business_locations',
        'invoice-schemes' => 'qa_invoice_settings',
        'barcodes' => 'qa_barcode_settings',
        'printers' => 'qa_receipt_printers',
        'tax-rates' => 'qa_tax_rates',
        'modules/tables' => 'qa_tables',
        'modules/modifiers' => 'qa_modifiers',
        'types-of-service' => 'qa_types_of_service',
        'subscription' => 'qa_subscription',
    ];

    $qa_description = function ($url) use ($qa_description_keys) {
        $key = ltrim(str_replace(url('/'), '', $url), '/');

        return isset($qa_description_keys[$key]) ? __('home.' . $qa_description_keys[$key]) : null;
    };

    // Same rendering rule as AdminlteCustomPresenter::formatIcon()
    $qa_format_icon = function ($icon) use ($qa_icons) {
        if (empty(trim((string) $icon))) {
            return $qa_icons['link'];
        }

        return strpos($icon, '<svg') !== false ? $icon : '<i class="' . e($icon) . '" aria-hidden="true"></i>';
    };

    // Children have no icon in the sidebar: pick one from the URL, else reuse the section icon
    $qa_child_icon = function ($child, $parent_icon) use ($qa_icons, $qa_format_icon) {
        if (!empty(trim((string) $child->icon))) {
            return $qa_format_icon($child->icon);
        }

        $path = trim((string) parse_url($child->getUrl(), PHP_URL_PATH), '/');
        parse_str((string) parse_url($child->getUrl(), PHP_URL_QUERY), $query);

        if (str_ends_with($path, '/create') || in_array($query['status'] ?? null, ['draft', 'quotation'])) {
            return $qa_icons['add'];
        }
        if (str_contains($path, 'import')) {
            return $qa_icons['import'];
        }
        if (str_starts_with($path, 'reports/')) {
            return $qa_icons['report'];
        }

        return $qa_format_icon($parent_icon);
    };

    $qa_is_link = function ($item) {
        $url = $item->getUrl();

        return !$item->isDivider() && !$item->isHeader() && !empty($url) && $url !== '#';
    };

    // Build sections in sidebar order; consecutive single links share one untitled grid
    $qa_sections = [];
    $qa_seen = [route('home'), action([\App\Http\Controllers\HomeController::class, 'index'])];
    if (Menu::has('admin-sidebar-menu')) {
        foreach (Menu::instance('admin-sidebar-menu')->getOrderedItems() as $item) {
            if ($item->hidden()) {
                continue;
            }

            if ($item->hasSubMenu()) {
                $tiles = [];
                foreach ($item->getChilds() as $child) {
                    if (!$qa_is_link($child) || in_array($child->getUrl(), $qa_seen)) {
                        continue;
                    }
                    $qa_seen[] = $child->getUrl();
                    $tiles[] = ['url' => $child->getUrl(), 'title' => $child->title, 'icon' => $qa_child_icon($child, $item->icon), 'desc' => $qa_description($child->getUrl())];
                }
                if (!empty($tiles)) {
                    $qa_sections[] = ['title' => $item->title, 'icon' => $qa_format_icon($item->icon), 'tiles' => $tiles];
                }
            } elseif ($qa_is_link($item) && !in_array($item->getUrl(), $qa_seen)) {
                $qa_seen[] = $item->getUrl();
                $tile = ['url' => $item->getUrl(), 'title' => $item->title, 'icon' => $qa_format_icon($item->icon), 'desc' => $qa_description($item->getUrl())];
                $last = count($qa_sections) - 1;
                if ($last >= 0 && is_null($qa_sections[$last]['title'])) {
                    $qa_sections[$last]['tiles'][] = $tile;
                } else {
                    $qa_sections[] = ['title' => null, 'icon' => null, 'tiles' => [$tile]];
                }
            }
        }
    }
@endphp

@foreach($qa_sections as $section)
    <div class="launch-group">
        @if(!is_null($section['title']))
            <h2 class="launch-group-heading">
                <span class="launch-group-icon">{!! $section['icon'] !!}</span>
                <span>{{ $section['title'] }}</span>
            </h2>
        @endif

        <div class="launch-grid">
            @foreach($section['tiles'] as $tile)
                <a href="{{ $tile['url'] }}" class="launch-card">
                    <span class="launch-icon">{!! $tile['icon'] !!}</span>
                    <p class="launch-title">{{ $tile['title'] }}</p>
                    @if(!empty($tile['desc']))
                        <p class="launch-desc">{{ $tile['desc'] }}</p>
                    @endif
                </a>
            @endforeach
        </div>
    </div>
@endforeach
