<?php

namespace Tests\Feature\Services;

use App\Models\{
	Establishment,
	EstablishmentService,
	Neighborhood,
	Service,
	Street
};

class SUT
{
	public Establishment $establishment;
	public EstablishmentService $establishmentService;
	public Neighborhood $neighborhood;
	public Service $service;
	public Street $street;
	public String $token;
}
