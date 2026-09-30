<?php

namespace App\Utils;

use Illuminate\Support\Facades\Cache;

/**
 * INE (Instituto Nacional de Estadística) autonomous communities,
 * provinces and municipalities (with their postal codes) used by the
 * business registration form.
 *
 * The same data file is served to the browser (public/js/data/spain-ine.json)
 * so client-side and server-side checks always agree. It is built from the
 * YAIGO_JSON data with: php artisan pos:buildSpainLocationData
 */
class SpainLocationUtil
{
    const DATA_FILE = 'js/data/spain-ine.json';

    /**
     * Provinces whose postal codes belong to the Canary Islands time zone
     */
    const CANARY_PROVINCES = ['35', '38'];

    /**
     * Flattened lookup tables keyed by INE code
     *
     * @return array ['communities' => [code => name], 'provinces' => [code => [name, community]], 'municipalities' => [code => [name, province, postal codes]]]
     */
    public function lookup()
    {
        $path = public_path(self::DATA_FILE);

        return Cache::remember('spain_ine_lookup_v2_'.filemtime($path), 60 * 60 * 24, function () use ($path) {
            $communities = [];
            $provinces = [];
            $municipalities = [];

            foreach (json_decode(file_get_contents($path), true) as $community) {
                $communities[$community['c']] = $community['n'];
                foreach ($community['p'] as $province) {
                    $provinces[$province['c']] = [$province['n'], $community['c']];
                    foreach ($province['m'] as $municipality) {
                        $municipalities[$municipality[0]] = [$municipality[1], $province['c'], array_map('strval', $municipality[2] ?? [])];
                    }
                }
            }

            return compact('communities', 'provinces', 'municipalities');
        });
    }

    /**
     * Communities for a dropdown, sorted by name
     *
     * @return array
     */
    public function communitiesDropdown()
    {
        $communities = $this->lookup()['communities'];
        asort($communities);

        return $communities;
    }

    public function isValidCommunity($community_code)
    {
        return isset($this->lookup()['communities'][$community_code]);
    }

    public function provinceBelongsToCommunity($province_code, $community_code)
    {
        $provinces = $this->lookup()['provinces'];

        return isset($provinces[$province_code]) && $provinces[$province_code][1] === $community_code;
    }

    public function municipalityBelongsToProvince($municipality_code, $province_code)
    {
        $municipalities = $this->lookup()['municipalities'];

        return isset($municipalities[$municipality_code]) && $municipalities[$municipality_code][1] === $province_code;
    }

    /**
     * Spanish postal codes are 5 digits and start with the INE province code
     */
    public function postalCodeMatchesProvince($postal_code, $province_code)
    {
        return preg_match('/^[0-9]{5}$/', (string) $postal_code) && substr($postal_code, 0, 2) === $province_code;
    }

    /**
     * Postal codes (strings) that can be used for a municipality.
     * Codes of another province are left out as postalCodeMatchesProvince() never accepts them.
     *
     * @return array
     */
    public function postalCodesForMunicipality($municipality_code)
    {
        $municipality = $this->lookup()['municipalities'][$municipality_code] ?? null;
        if (empty($municipality)) {
            return [];
        }

        return array_values(array_filter($municipality[2] ?? [], function ($postal_code) use ($municipality) {
            return $this->postalCodeMatchesProvince($postal_code, $municipality[1]);
        }));
    }

    public function provinceName($province_code)
    {
        return $this->lookup()['provinces'][$province_code][0] ?? null;
    }

    public function municipalityName($municipality_code)
    {
        return $this->lookup()['municipalities'][$municipality_code][0] ?? null;
    }

    /**
     * Time zone derived from the province: Canary Islands or mainland/Balearics/Ceuta/Melilla
     */
    public function timezoneForProvince($province_code)
    {
        return in_array($province_code, self::CANARY_PROVINCES, true) ? 'Atlantic/Canary' : 'Europe/Madrid';
    }
}
