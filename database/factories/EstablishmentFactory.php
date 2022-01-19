<?php

namespace Database\Factories;

use App\Http\Helpers\GenericHelper;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;

class EstablishmentFactory extends Factory
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
            'uuid' => GenericHelper::generateUUIDString(),
            'name' => 'Bom Barbeiro',
            'email' => 'bombarbeiro@segesteticamail.com',
            'photoUrl' => null,
            'streetId' => 1,
            'addressNumber' => '2B',
            'deleted' => false,
            'passwordHash' => Hash::make('123'),
            'created_at' => $now,
            'updated_at' => $now,
        ];
    }
}
