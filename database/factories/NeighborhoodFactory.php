<?php

namespace Database\Factories;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\Factory;

class NeighborhoodFactory extends Factory
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
            'name' => 'Bairro Fictício',
            'cityId' => 1,
            'created_at' => $now,
            'updated_at' => $now,
        ];
    }
}
