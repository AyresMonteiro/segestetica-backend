<?php

namespace Tests\Feature\Services;

use App\Models\{
	Establishment,
	EstablishmentService,
	Neighborhood,
	Service,
	Street,
};
use App\Utils\TranslatedAttributeName;
use Database\Factories\{
	EstablishmentFactory,
	NeighborhoodFactory,
	ServiceFactory,
	StreetFactory
};
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Artisan;
use Tests\TestCaseWithDatabase;

class ServicesChangeTest extends TestCaseWithDatabase
{
	use RefreshDatabase;

	protected $locale = 'pt-BR';

	public $sut = null;

	public function generateAddresses(): void
	{
		Artisan::call('db:seed');

		$neighborhood = new Neighborhood((new NeighborhoodFactory())->definition());

		$neighborhood->save();

		$street = new Street((new StreetFactory())->definition());

		$street->save();
	}

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

		$service = new Service((new ServiceFactory())->definition());

		$service->save();

		$sut->service = $service;

		$establishmentService = new EstablishmentService([
			'serviceId' => $service->id,
			'establishmentUuid' => $establishment->uuid,
			'active' => true,
		]);

		$establishmentService->save();

		$sut->establishmentService = $establishmentService;

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

	public function test_AssertsIfReturnsErrorWhenNoAuthProvided(): void
	{
		$uri = "/api/services/change/" . $this->sut->service->id;

		$response = $this->json(
			'PUT',
			$uri,
			['active' => false],
			[]
		);

		$response->assertStatus(401);
	}

	public function test_AssertsIfReturnsErrorWhenNoDataProvided(): void
	{
		$uri = "/api/services/change/" . $this->sut->service->id;

		$response = $this->json(
			'PUT',
			$uri,
			[],
			['Authorization' => 'Bearer ' . $this->sut->token]
		);

		$response->assertStatus(400);

		$body = json_decode($response->getContent(), true);

		$attribute = TranslatedAttributeName::get('validation.attributes.active');

		$message = __('validation.required', ['attribute' => $attribute]);

		$this->assertIsArray($body);
		$this->assertArrayHasKey('errors', $body);
		$this->assertIsArray($body['errors']);
		$this->assertContains($message, $body['errors']);
	}

	public function test_AssertsIfReturnsErrorWhenServiceDoesntExists(): void
	{
		$uri = "/api/services/change/" . ($this->sut->service->id + 1);

		$response = $this->json(
			'PUT',
			$uri,
			['serviceActive' => false],
			['Authorization' => 'Bearer ' . $this->sut->token]
		);

		$response->assertStatus(404);

		$body = json_decode($response->getContent(), true);

		$message = __('messages.not_found_error');

		$this->assertIsArray($body);
		$this->assertArrayHasKey('errors', $body);
		$this->assertIsArray($body['errors']);
		$this->assertContains($message, $body['errors']);
	}

	public function test_AssertsIfEstablishmentDoesntShowServiceAnymoreAfterSetInactive(): void
	{
		Artisan::call('db:seed');

		$this->generateAddresses();

		$uri = "/api/services/change/" . $this->sut->service->id;

		$response = $this->json(
			'PUT',
			$uri,
			['serviceActive' => false],
			['Authorization' => 'Bearer ' . $this->sut->token]
		);

		$response->assertStatus(204);

		$uri = '/api/establishments/' . $this->sut->establishment->uuid;

		$response = $this->json(
			'GET',
			$uri,
			[],
			['Authorization' => 'Bearer ' . $this->sut->token]
		);

		$response->assertStatus(200);

		$body = json_decode($response->getContent(), true);

		$this->assertIsArray($body);
		$this->assertArrayHasKey('services', $body);
		$this->assertIsArray($body['services']);
		$this->assertSame(0, sizeof($body['services']));
	}

	public function test_AssertsIfEstablishmentShowServiceAgainAfterSetActive(): void
	{
		Artisan::call('db:seed');

		$this->generateAddresses();

		$uri = "/api/services/change/" . $this->sut->service->id;

		$response = $this->json(
			'PUT',
			$uri,
			['serviceActive' => false],
			['Authorization' => 'Bearer ' . $this->sut->token]
		);

		$response->assertStatus(204);

		$uri = '/api/establishments/' . $this->sut->establishment->uuid;

		$response = $this->json(
			'GET',
			$uri,
			[],
			['Authorization' => 'Bearer ' . $this->sut->token]
		);

		$response->assertStatus(200);

		$body = json_decode($response->getContent(), true);

		$this->assertIsArray($body);
		$this->assertArrayHasKey('services', $body);
		$this->assertIsArray($body['services']);
		$this->assertSame(0, sizeof($body['services']));

		$uri = "/api/services/change/" . $this->sut->service->id;

		$response = $this->json(
			'PUT',
			$uri,
			['serviceActive' => true],
			['Authorization' => 'Bearer ' . $this->sut->token]
		);

		$response->assertStatus(204);

		Artisan::call('cache:clear');

		$uri = '/api/establishments/' . $this->sut->establishment->uuid;

		$response = $this->json(
			'GET',
			$uri,
			[],
			['Authorization' => 'Bearer ' . $this->sut->token]
		);

		$response->assertStatus(200);

		$body = json_decode($response->getContent(), true);

		$this->assertIsArray($body);
		$this->assertArrayHasKey('services', $body);
		$this->assertIsArray($body['services']);
		$this->assertSame(1, sizeof($body['services']));
	}
}
