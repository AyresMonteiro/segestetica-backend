<?php

namespace Tests\Feature\Services;

use App\Models\{
	Establishment,
	Neighborhood,
	Service,
	Street
};

class SUT
{
	public const CREATE_SERVICE_CORRECT_DATA_1 = [
		'serviceName' => 'Corte na Régua',
		'serviceDescription' => 'Combina com o bigodinho fininho.',
		'serviceIntegerValue' => 15,
		'serviceFractionalValue' => 50,
	];

	public const CREATE_SERVICE_CORRECT_DATA_2 = [
		'serviceName' => 'Bigodinho Fininho',
		'serviceDescription' => 'Combina com o corte na régua.',
		'serviceIntegerValue' => 10,
		'serviceFractionalValue' => 0,
	];

	public Establishment $establishment;
	public Neighborhood $neighborhood;
	public Service $service;
	public Street $street;
	public String $token;
}
