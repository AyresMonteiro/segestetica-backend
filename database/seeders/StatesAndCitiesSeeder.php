<?php

namespace Database\Seeders;

use App\Models\City;
use App\Models\State;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class StatesAndCitiesSeeder extends Seeder
{
    public static function runStatic()
    {
        $i = 0;
        $cities = [];
        $data = json_decode(file_get_contents(__DIR__ . '/data/estados-e-cidades-do-brasil.json'));

        foreach ($data->estados as $key => $estado) {
            $now = Carbon::now();

            $state = new State([
                'name' => $estado->nome,
                'abbreviation' => $estado->sigla,
            ]);

            $state->created_at = $now;
            $state->updated_at = $now;
            $state->save();

            foreach ($estado->cidades as $key => $cidade) {
                $city = new City([
                    'name' => $cidade,
                    'stateId' => $state->id,
                ]);

                $city->created_at = $now;
                $city->updated_at = $now;
                array_push($cities, $city->toArray());

                $i++;

                if ($i == 1500) {
                    City::insert($cities);
                    $cities = [];
                    $i = 0;
                }
            }

            if ($i > 0) {
                City::insert($cities);
                $cities = [];
                $i = 0;
            }
        }
    }

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        self::runStatic();
    }
}
