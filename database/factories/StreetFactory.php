<?php

namespace Database\Factories;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\Factory;

class StreetFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $now = Carbon::now();

        return [
            'id' => 1,
            'name' => 'Rua Fictícia',
            'postCode' => '36000000',
            'neighborhoodId' => 1,
            'created_at' => $now,
            'updated_at' => $now,
        ];
    }
}
