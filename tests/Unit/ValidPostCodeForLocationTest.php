<?php

namespace Tests\Unit;

use App\Rules\ValidPostCodeForLocation;
use App\Utils\SpainLocationUtil;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Tests\TestCase;

/**
 * Postal code <-> province + municipality check of the registration form,
 * against the real data file (public/js/data/spain-ine.json).
 */
class ValidPostCodeForLocationTest extends TestCase
{
    protected function passes($postal_code, $province_code, $municipality_code)
    {
        return Validator::make(['zip_code' => $postal_code], [
            'zip_code' => [new ValidPostCodeForLocation($province_code, $municipality_code)],
        ])->passes();
    }

    public function test_postal_codes_are_strings_with_leading_zeros()
    {
        $location_util = new SpainLocationUtil();

        //Abla (Almería) has a single postal code
        $this->assertSame(['04510'], $location_util->postalCodesForMunicipality('04001'));
        $this->assertContains('08001', $location_util->postalCodesForMunicipality('08019'));
        $this->assertSame([], $location_util->postalCodesForMunicipality('99999'));
    }

    public function test_every_municipality_has_postal_codes_in_the_data_file()
    {
        foreach ((new SpainLocationUtil())->lookup()['municipalities'] as $code => $municipality) {
            $this->assertNotEmpty($municipality[2], 'municipality '.$code);
        }
    }

    public function test_postal_code_of_the_selected_municipality_passes()
    {
        $this->assertTrue($this->passes('08001', '08', '08019'));
        $this->assertTrue($this->passes('04510', '04', '04001'));
        $this->assertTrue($this->passes(' 04510 ', '04', '04001'));
    }

    public function test_postal_code_of_another_municipality_fails()
    {
        //04520 belongs to Abrucena (04002), same province as Abla (04001)
        $this->assertTrue($this->passes('04520', '04', '04002'));
        $this->assertFalse($this->passes('04520', '04', '04001'));
        //Madrid postal code for Barcelona
        $this->assertFalse($this->passes('28001', '08', '08019'));
    }

    public function test_unknown_postal_code_fails_with_the_spanish_message()
    {
        app()->setLocale('es');

        $validator = Validator::make(['zip_code' => '08999'], [
            'zip_code' => [new ValidPostCodeForLocation('08', '08019')],
        ]);

        $this->assertTrue($validator->fails());
        $this->assertSame('El código postal no corresponde a la ciudad/municipio seleccionado.', $validator->errors()->first('zip_code'));

        //The leading zero matters: 4510 is not 04510
        $this->assertFalse($this->passes('4510', '04', '04001'));
        $this->assertFalse($this->passes(4510, '04', '04001'));
    }

    public function test_municipality_without_usable_postal_codes_falls_back_to_the_other_rules()
    {
        //Tresviso (Cantabria) is only listed with a postal code of Asturias (33554)
        $this->assertSame([], (new SpainLocationUtil())->postalCodesForMunicipality('39088'));

        Log::shouldReceive('warning')->once();

        $this->assertTrue($this->passes('39580', '39', '39088'));
    }

    public function test_wrong_province_and_municipality_pair_is_left_to_their_own_rules()
    {
        Log::shouldReceive('warning')->never();

        $this->assertTrue($this->passes('08001', '28', '08019'));
        $this->assertTrue($this->passes('08001', '08', ''));
    }
}
