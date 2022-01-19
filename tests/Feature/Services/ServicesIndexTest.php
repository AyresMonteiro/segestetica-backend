<?php

namespace Tests\Feature\Services;

use App\Models\{
	Establishment,
};
use Database\Factories\EstablishmentFactory;
use Tests\TestCaseWithDatabase;

class ServicesIndexTest extends TestCaseWithDatabase
{
	protected $sut;
	protected $locale = 'pt-BR';

	public function makeSut(): SUT
	{
		$establishment = new Establishment((new EstablishmentFactory())->definition());

		$establishment->save();

		$sut = new SUT();

		$sut->establishment = $establishment;

		$sut->token = $establishment->createToken(
			'general-establishment-login',
			['establishment:general'],
		)->plainTextToken;

		return $sut;
	}

	public function setUp(): void
	{
		parent::setUp();

		$this->sut = $this->makeSut();

		$this->withHeaders([
			'Content-Type' => 'application/json',
			'messages-language' => $this->locale,
		]);
	}

	public function tearDown(): void
	{
		parent::tearDown();
	}

	public function test_AssertsIfServicesListReturnsErrorWithoutAuth(): void
	{
		$response = $this->get('/api/services');

		$response->assertStatus(401);
	}

	public function test_AssertsIfServicesListNotReturnsErrorWithAuth(): void
	{
		$response = $this->get('/api/services', [
			'Authorization' => 'Bearer ' . $this->sut->token
		]);

		$response->assertStatus(200);
	}
}
