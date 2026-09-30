<?php

namespace App\Console\Commands;

use App\Utils\SpainLocationUtil;
use Illuminate\Console\Command;

class BuildSpainLocationData extends Command
{
    /**
     * Source file inside the YAIGO_JSON directory (communities -> provinces -> municipalities -> postal codes)
     */
    const SOURCE_FILE = 'YAIGO_JSON/espana-direcciones-completo.json';

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'pos:buildSpainLocationData';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Builds the compact location file used by the business registration form (public/'.SpainLocationUtil::DATA_FILE.') from the YAIGO_JSON data';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $source = base_path(self::SOURCE_FILE);
        if (! file_exists($source)) {
            $this->error('File not found: '.$source);

            return 1;
        }

        $data = json_decode(file_get_contents($source), true);
        if (empty($data['comunidades'])) {
            $this->error('Unexpected structure in '.$source);

            return 1;
        }

        $by_name = function ($a, $b) {
            return strcmp($a['nombre'], $b['nombre']) ?: strcmp($a['codigo'], $b['codigo']);
        };

        $communities = [];
        $municipality_count = 0;
        foreach ($data['comunidades'] as $community) {
            $provinces = [];
            usort($community['provincias'], $by_name);
            foreach ($community['provincias'] as $province) {
                $municipalities = [];
                usort($province['municipios'], $by_name);
                foreach ($province['municipios'] as $municipality) {
                    //Postal codes stay strings so leading zeros are kept
                    $postal_codes = array_values(array_unique(array_map('strval', $municipality['codigos_postales'] ?? [])));
                    sort($postal_codes, SORT_STRING);

                    $municipalities[] = [(string) $municipality['codigo'], $municipality['nombre'], $postal_codes];
                    $municipality_count++;
                }
                $provinces[] = ['c' => (string) $province['codigo'], 'n' => $province['nombre'], 'm' => $municipalities];
            }
            $communities[] = ['c' => (string) $community['codigo'], 'n' => $community['nombre'], 'p' => $provinces];
        }

        $target = public_path(SpainLocationUtil::DATA_FILE);
        file_put_contents($target, json_encode($communities, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES));

        $this->info($municipality_count.' municipalities written to '.$target);

        return 0;
    }
}
