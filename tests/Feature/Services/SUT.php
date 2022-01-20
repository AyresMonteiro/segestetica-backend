<?php

namespace Tests\Feature\Services;

use App\Models\{
	Establishment,
	Service,
};

class SUT
{
	public Establishment $establishment;
	public Service $service;
	public String $token;
}
