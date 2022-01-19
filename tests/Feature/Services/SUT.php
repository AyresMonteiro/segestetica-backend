<?php

namespace Tests\Feature\Services;

use App\Models\Establishment;
use App\Models\Service;

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
	public Service $service;
	public String $token;
}
