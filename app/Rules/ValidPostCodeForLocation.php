<?php

namespace App\Rules;

use App\Utils\SpainLocationUtil;
use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Support\Facades\Log;

/**
 * Checks that a postal code belongs to the selected province + municipality
 * (INE codes), using the same data file the registration form suggests from.
 * Mirrors the client-side check in public/js/business_register.js.
 */
class ValidPostCodeForLocation implements ValidationRule
{
    protected $province_code;

    protected $municipality_code;

    public function __construct($province_code, $municipality_code)
    {
        $this->province_code = (string) $province_code;
        $this->municipality_code = (string) $municipality_code;
    }

    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $location_util = new SpainLocationUtil();

        //A wrong province/municipality selection is reported on those fields
        if (! $location_util->municipalityBelongsToProvince($this->municipality_code, $this->province_code)) {
            return;
        }

        $postal_codes = $location_util->postalCodesForMunicipality($this->municipality_code);

        //No known postal codes: only the format/province rules apply
        if (empty($postal_codes)) {
            Log::warning('No postal codes found for municipality '.$this->municipality_code.'; postal code '.$value.' accepted without municipality check.');

            return;
        }

        if (! in_array(trim((string) $value), $postal_codes, true)) {
            $fail(__('business.postal_code_municipality_mismatch'));
        }
    }
}
