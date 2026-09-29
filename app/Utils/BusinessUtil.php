<?php

namespace App\Utils;

use App\Barcode;
use App\Business;
use App\BusinessActivity;
use App\BusinessLocation;
use App\Contact;
use App\Currency;
use App\InvoiceLayout;
use App\InvoiceScheme;
use App\NotificationTemplate;
use App\Printer;
use App\Unit;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Role;
use App\VariationLocationDetails;


class BusinessUtil extends Util
{
    /**
     * Adds a default settings/resources for a new business
     *
     * @param  int  $business_id
     * @param  int  $user_id
     * @return bool
     */
    public function newBusinessDefaultResources($business_id, $user_id)
    {
        $user = User::find($user_id);

        //create Admin role and assign to user
        $role = Role::create(['name' => 'Admin#'.$business_id,
            'business_id' => $business_id,
            'guard_name' => 'web', 'is_default' => 1,
        ]);
        $user->assignRole($role->name);

        //Create Cashier role for a new business
        $cashier_role = Role::create(['name' => 'Cashier#'.$business_id,
            'business_id' => $business_id,
            'guard_name' => 'web',
        ]);
        $cashier_role->syncPermissions(['sell.view', 'sell.create', 'sell.update', 'sell.delete', 'access_all_locations', 'view_cash_register', 'close_cash_register']);

        $business = Business::findOrFail($business_id);

        //Update reference count
        $ref_count = $this->setAndGetReferenceCount('contacts', $business_id);
        $contact_id = $this->generateReferenceNumber('contacts', $ref_count, $business_id);

        //Add Default/Walk-In Customer for new business
        $customer = [
            'business_id' => $business_id,
            'type' => 'customer',
            'name' => 'Walk-In Customer',
            'created_by' => $user_id,
            'is_default' => 1,
            'contact_id' => $contact_id,
            'credit_limit' => 0,
        ];
        Contact::create($customer);

        //create default invoice setting for new business
        InvoiceScheme::create(['name' => 'Default',
            'scheme_type' => 'blank',
            'prefix' => '',
            'start_number' => 1,
            'total_digits' => 4,
            'is_default' => 1,
            'business_id' => $business_id,
        ]);
        //create default invoice layour for new business
        InvoiceLayout::create(['name' => 'Default',
            'header_text' => null,
            'invoice_no_prefix' => 'Invoice No.',
            'invoice_heading' => 'Invoice',
            'sub_total_label' => 'Subtotal',
            'discount_label' => 'Discount',
            'tax_label' => 'Tax',
            'total_label' => 'Total',
            'show_landmark' => 1,
            'show_city' => 1,
            'show_state' => 1,
            'show_zip_code' => 1,
            'show_country' => 1,
            'highlight_color' => '#000000',
            'footer_text' => '',
            'is_default' => 1,
            'business_id' => $business_id,
            'invoice_heading_not_paid' => '',
            'invoice_heading_paid' => '',
            'total_due_label' => 'Total Due',
            'paid_label' => 'Total Paid',
            'show_payments' => 1,
            'show_customer' => 1,
            'customer_label' => 'Customer',
            'table_product_label' => 'Product',
            'table_qty_label' => 'Quantity',
            'table_unit_price_label' => 'Unit Price',
            'table_subtotal_label' => 'Subtotal',
            'date_label' => 'Date',
        ]);

        //create default barcode setting for new business
        // Barcode::create(['name' => 'Default',
        //                 'description' => '',
        //                 'width' => 37.29,
        //                 'height' => 25.93,
        //                 'top_margin' => 5,
        //                 'left_margin' => 5,
        //                 'row_distance' => 1,
        //                 'col_distance' => 1,
        //                 'stickers_in_one_row' => 4,
        //                 'is_default' => 1,
        //                 'business_id' => $business_id
        //             ]);

        //Add Default Unit for new business
        $unit = [
            'business_id' => $business_id,
            'actual_name' => 'Pieces',
            'short_name' => 'Pc(s)',
            'allow_decimal' => 0,
            'created_by' => $user_id,
        ];
        Unit::create($unit);

        //Create default notification templates
        $notification_templates = NotificationTemplate::defaultNotificationTemplates($business_id);
        foreach ($notification_templates as $notification_template) {
            NotificationTemplate::create($notification_template);
        }

        return true;
    }

    /**
     * Gives a list of all currencies
     *
     * @return array
     */
    public function allCurrencies()
    {
        $currencies = Currency::select('id', DB::raw("concat(country, ' - ',currency, '(', code, ') ') as info"))
                ->orderBy('country')
                ->pluck('info', 'id');

        return $currencies;
    }

    /**
     * Gives a list of all timezone
     *
     * @return array
     */
    public function allTimeZones()
    {
        $datetime = new \DateTimeZone('EDT');

        $timezones = $datetime->listIdentifiers();
        $timezone_list = [];
        foreach ($timezones as $timezone) {
            $timezone_list[$timezone] = $timezone;
        }

        return $timezone_list;
    }

    /**
     * Gives a list of all accouting methods
     *
     * @return array
     */
    public function allAccountingMethods()
    {
        return [
            'fifo' => __('business.fifo'),
            'lifo' => __('business.lifo'),
        ];
    }

    /**
     * Gives a list of all business sectors shown in the registration forms
     *
     * @return array
     */
    public function allBusinessSectors($locale = null)
    {
        $sectors = [
            'bakery' => 'business.bakery',
            'butcher' => 'business.butcher_shop',
            'cafe' => 'business.cafe',
            'clothing' => 'business.clothing_store',
            'electronics' => 'business.electronics',
            'fast_food' => 'business.fast_food',
            'grocery' => 'business.grocery_store',
            'hairdresser' => 'business.hairdresser_beauty',
            'hotel' => 'business.hotel',
            'manufacturing' => 'business.manufacturing',
//            'pharmacy' => 'business.pharmacy',
            'restaurant' => 'business.restaurant',
            'retail' => 'business.retail_store',
            'super_market' => 'business.supermarket',
            'other' => 'business.other',
        ];

        return array_map(function ($lang_key) use ($locale) {
            return __($lang_key, [], $locale);
        }, $sectors);
    }

    /**
     * Main activity dropdown for the registration forms: business sectors
     * plus activities entered by earlier registrations, "Other" last.
     *
     * @return array
     */
    public function businessActivitiesDropdown()
    {
        $sectors = $this->allBusinessSectors();
        $other = $sectors['other'];
        unset($sectors['other']);
        asort($sectors);

        $custom = BusinessActivity::orderBy('name')->pluck('name', 'id');
        foreach ($custom as $id => $name) {
            $sectors['activity:'.$id] = $name;
        }

        $sectors['other'] = $other;

        return $sectors;
    }

    /**
     * Phone prefixes offered in the registration forms (+34 is the default)
     *
     * @return array
     */
    public function phonePrefixes()
    {
        return [
            '+34' => 'ES +34',
            '+351' => 'PT +351',
            '+33' => 'FR +33',
            '+376' => 'AD +376',
            '+39' => 'IT +39',
            '+49' => 'DE +49',
            '+44' => 'GB +44',
            '+1' => 'US +1',
            '+52' => 'MX +52',
            '+54' => 'AR +54',
            '+57' => 'CO +57',
        ];
    }

    /**
     * Spanish numbers have 9 digits; other countries between 6 and 14.
     *
     * @return bool
     */
    public function isValidPhoneNumber($prefix, $number)
    {
        $pattern = $prefix == '+34' ? '/^[0-9]{9}$/' : '/^[0-9]{6,14}$/';

        return (bool) preg_match($pattern, (string) $number);
    }

    /**
     * Normalizes the registration request before validation: fields that are
     * no longer asked for are derived (time zone, state/city names, financial
     * year, accounting method), and user input is cleaned up.
     *
     * @return string|null main activity typed under "Other" that should be saved for later registrations
     */
    public function normalizeRegistrationInput(Request $request)
    {
        $location_util = new SpainLocationUtil();
        $input = [];

        //Accept a domain without protocol (example.es -> http://example.es)
        $website = trim((string) $request->input('website'));
        if ($website !== '' && ! preg_match('#^[a-z][a-z0-9+.-]*://#i', $website)) {
            $website = 'http://'.$website;
        }
        $input['website'] = $website !== '' ? $website : null;

        foreach (['mobile', 'whatsapp_number'] as $field) {
            $number = preg_replace('/[\s\-\.\(\)]/', '', (string) $request->input($field));
            $input[$field] = $number !== '' ? $number : null;
        }
        $input['mobile_prefix'] = $request->input('mobile_prefix', '+34');
        $input['whatsapp_prefix'] = $request->input('whatsapp_prefix', '+34');
        if ($request->boolean('whatsapp_same_as_mobile')) {
            $input['whatsapp_number'] = $input['mobile'];
            $input['whatsapp_prefix'] = $input['mobile_prefix'];
        }

        foreach (['tax_number_1', 'tax_number_2'] as $field) {
            if ($request->filled($field)) {
                $input[$field] = strtoupper(preg_replace('/[\s\-\.]/', '', $request->input($field)));
            }
        }

        //Address: names are stored for display, INE codes alongside
        $province_code = (string) $request->input('province_code');
        $input['state'] = $location_util->provinceName($province_code);
        $input['city'] = $location_util->municipalityName((string) $request->input('municipality_code'));
        $input['time_zone'] = $location_util->timezoneForProvince($province_code);

        //In Spain the financial year always starts in January; stock is valued with FIFO
        $input['fy_start_month'] = 1;
        $input['accounting_method'] = 'fifo';

        //Main activity
        $new_activity = null;
        $sectors = $this->allBusinessSectors();
        $sector = (string) $request->input('business_sector');
        //Raw dropdown value, to re-select it if the form is shown again with errors
        $input['business_activity_choice'] = $sector;
        $input['business_activity'] = null;
        if (strpos($sector, 'activity:') === 0) {
            $activity = BusinessActivity::find((int) substr($sector, 9));
            $input['business_sector'] = 'other';
            $input['business_activity'] = $activity->name ?? null;
        } elseif ($sector == 'other') {
            $other = trim(preg_replace('/\s+/', ' ', (string) $request->input('business_activity_other')));
            $matched_sector = $this->matchBusinessSector($other);
            if (! empty($matched_sector)) {
                $input['business_sector'] = $matched_sector;
                $input['business_activity'] = $sectors[$matched_sector];
            } elseif ($other !== '') {
                $input['business_activity'] = $new_activity = mb_strtoupper(mb_substr($other, 0, 1)).mb_substr($other, 1);
            }
        } elseif (isset($sectors[$sector])) {
            $input['business_activity'] = $sectors[$sector];
        }

        $request->merge($input);

        return $new_activity;
    }

    /**
     * Finds the business sector whose English or Spanish name equals the given text
     *
     * @return string|null
     */
    protected function matchBusinessSector($text)
    {
        $text = mb_strtolower($text);
        if ($text === '') {
            return null;
        }

        foreach (['en', 'es'] as $locale) {
            foreach ($this->allBusinessSectors($locale) as $key => $name) {
                if ($key != 'other' && mb_strtolower($name) === $text) {
                    return $key;
                }
            }
        }

        return null;
    }

    /**
     * Saves a main activity typed under "Other" so later registrations can select it
     */
    public function saveBusinessActivity($name)
    {
        if (! empty($name)) {
            BusinessActivity::firstOrCreate(['name' => $name]);
        }
    }

    /**
     * Validation rules shared by the public registration and the superadmin
     * "add business" form (both render business.partials.register_form).
     *
     * @return array [rules, attribute names]
     */
    public function registrationValidation(Request $request)
    {
        $location_util = new SpainLocationUtil();
        $prefixes = implode(',', array_keys($this->phonePrefixes()));

        $rules = [
            'business_sector' => ['required', 'in:'.implode(',', array_keys($this->allBusinessSectors()))],
            'business_activity' => 'required|max:100',
            'website' => 'nullable|url|max:255',
            'country' => 'required|in:Spain',
            'community_code' => ['required', function ($attribute, $value, $fail) use ($location_util) {
                if (! $location_util->isValidCommunity($value)) {
                    $fail(__('validation.in', ['attribute' => __('business.autonomous_community')]));
                }
            }],
            'province_code' => ['required', function ($attribute, $value, $fail) use ($location_util, $request) {
                if (! $location_util->provinceBelongsToCommunity($value, $request->input('community_code'))) {
                    $fail(__('validation.in', ['attribute' => __('business.province')]));
                }
            }],
            'municipality_code' => ['required', function ($attribute, $value, $fail) use ($location_util, $request) {
                if (! $location_util->municipalityBelongsToProvince($value, $request->input('province_code'))) {
                    $fail(__('validation.in', ['attribute' => __('business.city_municipality')]));
                }
            }],
            'zip_code' => ['required', function ($attribute, $value, $fail) use ($location_util, $request) {
                if (! preg_match('/^[0-9]{5}$/', (string) $value)) {
                    $fail(__('business.postal_code_invalid'));
                } elseif (! $location_util->postalCodeMatchesProvince($value, (string) $request->input('province_code'))) {
                    $fail(__('business.postal_code_province_mismatch'));
                }
            }],
            'landmark' => 'required|max:255',
            'address_line_2' => 'nullable|max:255',
            'contact_person' => 'required|max:255',
            'mobile_prefix' => 'required|in:'.$prefixes,
            'mobile' => ['required', function ($attribute, $value, $fail) use ($request) {
                if (! $this->isValidPhoneNumber($request->input('mobile_prefix'), $value)) {
                    $fail(__('business.phone_invalid'));
                }
            }],
            'whatsapp_prefix' => 'nullable|in:'.$prefixes,
            'whatsapp_number' => ['nullable', function ($attribute, $value, $fail) use ($request) {
                if (! $this->isValidPhoneNumber($request->input('whatsapp_prefix'), $value)) {
                    $fail(__('business.phone_invalid'));
                }
            }],
        ];

        $attributes = [
            'business_type' => __('business.business_type'),
            'name' => __('business.trading_name'),
            'legal_name' => __('business.legal_company_name'),
            'business_sector' => __('business.main_activity'),
            'business_activity' => __('business.main_activity'),
            'currency_id' => __('business.currency'),
            'website' => __('lang_v1.website'),
            'country' => __('business.country'),
            'community_code' => __('business.autonomous_community'),
            'province_code' => __('business.province'),
            'municipality_code' => __('business.city_municipality'),
            'zip_code' => __('business.postal_code'),
            'landmark' => __('business.physical_address'),
            'address_line_2' => __('business.address_line_2'),
            'contact_person' => __('business.contact_person_name'),
            'mobile' => __('lang_v1.business_telephone'),
            'mobile_prefix' => __('business.phone_prefix'),
            'whatsapp_number' => __('business.whatsapp_number'),
            'whatsapp_prefix' => __('business.phone_prefix'),
            'contact_email' => __('business.business_email'),
            'tax_label_1' => __('business.document_type'),
            'tax_number_1' => __('business.document_number'),
            'tax_label_2' => __('business.document_type'),
            'tax_number_2' => __('business.document_number'),
            'legal_rep_name' => __('business.legal_rep_full_name'),
            'legal_rep_position' => __('business.legal_rep_position'),
            'referred_by' => __('business.referred_by'),
            'first_name' => __('business.first_name'),
            'last_name' => __('business.last_name'),
            'username' => __('business.username'),
            'email' => __('business.email'),
            'password' => __('business.password'),
            'confirm_password' => __('business.confirm_password'),
        ];

        return [$rules, $attributes];
    }

    /**
     * First location details from a (normalized) registration request
     *
     * @return array
     */
    public function registrationLocationDetails(Request $request)
    {
        $location = $request->only(['name', 'country', 'community_code', 'province_code', 'municipality_code',
            'state', 'city', 'zip_code', 'landmark', 'address_line_2', 'website', 'contact_person', 'contact_email', 'alternate_number', ]);

        $location['mobile'] = $request->input('mobile_prefix').' '.$request->input('mobile');
        $location['whatsapp_number'] = $request->filled('whatsapp_number')
            ? $request->input('whatsapp_prefix').' '.$request->input('whatsapp_number') : null;

        return $location;
    }

    /**
     * Gives the modules to enable for a new business based on its sector
     *
     * @param  string|null  $business_sector
     * @return array
     */
    public function enabledModulesForSector($business_sector)
    {
        $default = ['purchases', 'add_sale', 'pos_sale', 'stock_transfers', 'stock_adjustment', 'expenses'];
        $with_account = array_merge($default, ['account']);
        $service_based = ['purchases', 'add_sale', 'pos_sale', 'expenses', 'account', 'service_staff'];
        $food_service = array_merge($default, ['tables', 'modifiers', 'service_staff', 'kitchen', 'types_of_service']);

        $sector_modules = [
            'super_market' => $with_account,
            'pharmacy' => $with_account,
            'electronics' => array_merge($with_account, ['subscription']),
            'services' => $service_based,
            'restaurant' => array_merge($food_service, ['booking']),
            'essentials' => $with_account,
            'manufacturing' => $default,
            'cafe' => $food_service,
            'fast_food' => $food_service,
            'bakery' => $with_account,
            'grocery' => $with_account,
            'butcher' => $default,
            'clothing' => $with_account,
            'hairdresser' => $service_based,
            'retail' => $with_account,
            'hotel' => array_merge($food_service, ['booking']),
            'other' => $default,
        ];

        return $sector_modules[$business_sector] ?? $default;
    }

    /**
     * Creates new business with default settings.
     *
     * @return array
     */
    public function createNewBusiness($business_details)
    {
        $business_details['sell_price_tax'] = 'includes';

        $business_details['default_profit_percent'] = 25;

        //Add POS shortcuts
        $business_details['keyboard_shortcuts'] = '{"pos":{"express_checkout":"shift+e","pay_n_ckeckout":"shift+p","draft":"shift+d","cancel":"shift+c","edit_discount":"shift+i","edit_order_tax":"shift+t","add_payment_row":"shift+r","finalize_payment":"shift+f","recent_product_quantity":"f2","add_new_product":"f4"}}';

        //Add prefixes
        $business_details['ref_no_prefixes'] = [
            'purchase' => 'PO',
            'stock_transfer' => 'ST',
            'stock_adjustment' => 'SA',
            'sell_return' => 'CN',
            'expense' => 'EP',
            'contacts' => 'CO',
            'purchase_payment' => 'PP',
            'sell_payment' => 'SP',
            'business_location' => 'BL',
        ];

        //Disable inline tax editing
        $business_details['enable_inline_tax'] = 0;

        $business = Business::create_business($business_details);

        return $business;
    }

    /**
     * Gives details for a business
     *
     * @return object
     */
    public function getDetails($business_id)
    {
        $details = Business::leftjoin('tax_rates AS TR', 'business.default_sales_tax', 'TR.id')
                        ->leftjoin('currencies AS cur', 'business.currency_id', 'cur.id')
                        ->select(
                            'business.*',
                            'cur.code as currency_code',
                            'cur.symbol as currency_symbol',
                            'thousand_separator',
                            'decimal_separator',
                            'TR.amount AS tax_calculation_amount',
                            'business.default_sales_discount'
                        )
                        ->where('business.id', $business_id)
                        ->first();

        return $details;
    }

    /**
     * Gives current financial year
     *
     * @return array
     */
    public function getCurrentFinancialYear($business_id)
    {
        $business = Business::where('id', $business_id)->first();
        $start_month = $business->fy_start_month;
        $end_month = $start_month - 1;
        if ($start_month == 1) {
            $end_month = 12;
        }

        $start_year = date('Y');
        //if current month is less than start month change start year to last year
        if (date('n') < $start_month) {
            $start_year = $start_year - 1;
        }

        $end_year = date('Y');
        //if current month is greater than end month change end year to next year
        if (date('n') > $end_month) {
            $end_year = $start_year + 1;
        }
        $start_date = $start_year.'-'.str_pad($start_month, 2, 0, STR_PAD_LEFT).'-01';
        $end_date = $end_year.'-'.str_pad($end_month, 2, 0, STR_PAD_LEFT).'-01';
        $end_date = date('Y-m-t', strtotime($end_date));

        $output = [
            'start' => $start_date,
            'end' => $end_date,
        ];

        return $output;
    }

    /**
     * Adds a new location to a business
     *
     * @param  int  $business_id
     * @param  array  $location_details
     * @param  int  $invoice_layout_id default null
     * @return location object
     */
    public function addLocation($business_id, $location_details, $invoice_scheme_id = null, $invoice_layout_id = null)
    {
        if (empty($invoice_scheme_id)) {
            $layout = InvoiceLayout::where('is_default', 1)
                                    ->where('business_id', $business_id)
                                    ->first();
            $invoice_layout_id = $layout->id;
        }

        if (empty($invoice_scheme_id)) {
            $scheme = InvoiceScheme::where('is_default', 1)
                                    ->where('business_id', $business_id)
                                    ->first();
            $invoice_scheme_id = $scheme->id;
        }

        //Update reference count
        $ref_count = $this->setAndGetReferenceCount('business_location', $business_id);
        $location_id = $this->generateReferenceNumber('business_location', $ref_count, $business_id);

        //Enable all payment methods by default
        $payment_types = $this->payment_types();
        $location_payment_types = [];
        foreach ($payment_types as $key => $value) {
            $location_payment_types[$key] = [
                'is_enabled' => 1,
                'account' => null,
            ];
        }
        $location = BusinessLocation::create(['business_id' => $business_id,
            'name' => $location_details['name'],
            'landmark' => $location_details['landmark'],
            'city' => $location_details['city'],
            'state' => $location_details['state'],
            'zip_code' => $location_details['zip_code'],
            'country' => $location_details['country'],
            'community_code' => $location_details['community_code'] ?? null,
            'province_code' => $location_details['province_code'] ?? null,
            'municipality_code' => $location_details['municipality_code'] ?? null,
            'address_line_2' => $location_details['address_line_2'] ?? null,
            'invoice_scheme_id' => $invoice_scheme_id,
            'invoice_layout_id' => $invoice_layout_id,
            'sale_invoice_layout_id' => $invoice_layout_id,
            'mobile' => ! empty($location_details['mobile']) ? $location_details['mobile'] : '',
            'alternate_number' => ! empty($location_details['alternate_number']) ? $location_details['alternate_number'] : '',
            'website' => ! empty($location_details['website']) ? $location_details['website'] : '',
            'email' => '',
            'contact_email' => $location_details['contact_email'] ?? null,
            'contact_person' => $location_details['contact_person'] ?? null,
            'whatsapp_number' => $location_details['whatsapp_number'] ?? null,
            'location_id' => $location_id,
            'default_payment_accounts' => json_encode($location_payment_types),
        ]);

        return $location;
    }

    /**
     * Return the invoice layout details
     *
     * @param  int  $business_id
     * @param  array  $layout_id = null
     * @return location object
     */
    public function invoiceLayout($business_id, $layout_id = null)
    {
        $layout = null;
        if (! empty($layout_id)) {
            $layout = InvoiceLayout::find($layout_id);
        }

        //If layout is not found (deleted) then get the default layout for the business
        if (empty($layout)) {
            $layout = InvoiceLayout::where('business_id', $business_id)
                        ->where('is_default', 1)
                        ->first();
        }
        //$output = []
        return $layout;
    }

    /**
     * Return the printer configuration
     *
     * @param  int  $business_id
     * @param  int  $printer_id
     * @return array
     */
    public function printerConfig($business_id, $printer_id)
    {
        $printer = Printer::where('business_id', $business_id)
                    ->find($printer_id);

        $output = [];

        if (! empty($printer)) {
            $output['connection_type'] = $printer->connection_type;
            $output['capability_profile'] = $printer->capability_profile;
            $output['char_per_line'] = $printer->char_per_line;
            $output['ip_address'] = $printer->ip_address;
            $output['port'] = $printer->port;
            $output['path'] = $printer->path;
            $output['server_url'] = $printer->server_url;
        }

        return $output;
    }

    /**
     * Return the date range for which editing of transaction for a business is allowed.
     *
     * @param  int  $business_id
     * @param  char  $edit_transaction_period
     * @return array
     */
    public function editTransactionDateRange($business_id, $edit_transaction_period)
    {
        if (is_numeric($edit_transaction_period)) {
            return ['start' => \Carbon::today()
                ->subDays($edit_transaction_period),
                'end' => \Carbon::today(),
            ];
        } elseif ($edit_transaction_period == 'fy') {
            //Editing allowed for current financial year
            return $this->getCurrentFinancialYear($business_id);
        }

        return false;
    }

    /**
     * Return the default setting for the pos screen.
     *
     * @return array
     */
    public function defaultPosSettings()
    {
        return ['disable_pay_checkout' => 0, 'disable_draft' => 0, 'disable_express_checkout' => 0, 'hide_product_suggestion' => 0, 'hide_recent_trans' => 0, 'disable_discount' => 0, 'disable_order_tax' => 0, 'is_pos_subtotal_editable' => 0];
    }

    /**
     * Return the default setting for the email.
     *
     * @return array
     */
    public function defaultEmailSettings()
    {
        return ['mail_host' => '', 'mail_port' => '', 'mail_username' => '', 'mail_password' => '', 'mail_encryption' => '', 'mail_from_address' => '', 'mail_from_name' => ''];
    }

    /**
     * Return the default setting for the email.
     *
     * @return array
     */
    public function defaultSmsSettings()
    {
        return ['url' => '', 'send_to_param_name' => 'to', 'msg_param_name' => 'text', 'request_method' => 'post', 'param_1' => '', 'param_val_1' => '', 'param_2' => '', 'param_val_2' => '', 'param_3' => '', 'param_val_3' => '', 'param_4' => '', 'param_val_4' => '', 'param_5' => '', 'param_val_5' => '', 'data_parameter_type' => 'form-data'];
    }

}
