<?php

namespace Tests\Feature\Services;

use App\Http\Handlers\LogHandler;
use App\Models\{
	Establishment,
	Neighborhood,
	Street,
	User,
};
use Database\Factories\EstablishmentFactory;
use Database\Factories\NeighborhoodFactory;
use Database\Factories\StreetFactory;
use Database\Factories\UserFactory;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Artisan;
use Tests\TestCaseWithDatabase;

class ServicesIndexTest extends TestCaseWithDatabase
{
	use RefreshDatabase;

	protected $sut;
	protected $locale = 'pt-BR';

	public function generateAddresses(): void
	{
		Artisan::call('db:seed');

		$neighborhood = new Neighborhood((new NeighborhoodFactory())->definition());

		$neighborhood->save();

		$street = new Street((new StreetFactory())->definition());

		$street->save();
	}

	public function getURI(): String
	{
		return '/api/services';
	}

	public function makeSut(): SUT
	{
		$establishment = new Establishment((new EstablishmentFactory())->definition());

		$establishment->save();

		$sut = new SUT();

		$sut->establishment = $establishment;

		$sut->token = $establishment->createToken(
			Establishment::GENERAL_TOKEN_NAME,
			[Establishment::GENERAL_ABILITY],
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
		$response = $this->get($this->getURI());

		$response->assertStatus(401);
	}

	public function test_AssertsIfServicesListNotReturnsErrorWithEstablishmentAuth(): void
	{
		$response = $this->get($this->getURI(), [
			'Authorization' => 'Bearer ' . $this->sut->token
		]);

		$response->assertStatus(200);
	}

	public function test_AssertsIfServicesListReturnsErrorWithUserAuth(): void
	{
		$this->generateAddresses();

		$user = new User((new UserFactory())->definition());

		$user->save();

		$token = $user->createToken(
			User::GENERAL_TOKEN_NAME,
			[User::GENERAL_ABILITY]
		)->plainTextToken;

		$auth = 'Bearer ' . $token;

		$response = $this->get($this->getURI(), [
			'Authorization' => $auth
		]);

		$response->assertStatus(403);
	}
}
