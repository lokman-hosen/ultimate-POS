<?php

namespace Tests\Feature\Concerns;

/**
 * Valid business registration input (Barcelona, self-employed with NIE)
 */
trait RegistrationPayload
{
    protected function payload(array $overrides = [])
    {
        $suffix = uniqid();

        return array_merge([
            'lang' => 'es',
            'language' => 'es',
            'business_type' => 'self_employed',
            'name' => 'Test Shop '.$suffix,
            'tax_label_1' => 'NIE',
            'tax_number_1' => 'x1234567l',
            'business_sector' => 'restaurant',
            'currency_id' => 110,
            'website' => 'unimerkat.es',
            'contact_person' => 'Ana García',
            'contact_email' => 'shop'.$suffix.'@example.com',
            'mobile_prefix' => '+34',
            'mobile' => '612 345 678',
            'whatsapp_same_as_mobile' => 1,
            'country' => 'Spain',
            'community_code' => '09',
            'province_code' => '08',
            'municipality_code' => '08019',
            'zip_code' => '08001',
            'landmark' => 'Carrer Major 1',
            'address_line_2' => '2º 1ª',
            'first_name' => 'Ana',
            'last_name' => 'García',
            'username' => 'user'.$suffix,
            'email' => 'owner'.$suffix.'@example.com',
            'password' => 'secret123',
            'confirm_password' => 'secret123',
        ], $overrides);
    }
}
