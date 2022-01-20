<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class ServiceFactory extends Factory
{
	public function definition(): array
	{
		return [
			'name' => 'Corte na Régua',
			'description' => 'Combina com o bigodinho fininho.',
			'integerValue' => 15,
			'fractionalValue' => 50,
		];
	}

	public function definitionForAPI(): array
	{
		return [
			'serviceName' => 'Corte na Régua',
			'serviceDescription' => 'Combina com o bigodinho fininho.',
			'serviceIntegerValue' => 15,
			'serviceFractionalValue' => 50,
		];
	}
}
