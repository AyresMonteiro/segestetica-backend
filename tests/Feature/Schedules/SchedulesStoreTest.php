<?php

namespace Tests\Feature\Schedules;

use App\Models\Establishment;
use App\Models\Neighborhood;
use App\Models\Street;
use App\Models\User;
use App\Utils\TranslatedAttributeName;
use Database\Factories\EstablishmentFactory;
use Database\Factories\NeighborhoodFactory;
use Database\Factories\ServiceFactory;
use Database\Factories\StreetFactory;
use Database\Factories\UserFactory;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Artisan;
use Tests\TestCaseWithDatabase;

class SchedulesStoreTest extends TestCaseWithDatabase
{
	use RefreshDatabase;

	protected $locale = 'pt-BR';

	public ?SUT $sut = null;
	public ?array $serviceData = null;

	public function generateAddresses(): void
	{
		Artisan::call('db:seed');

		$neighborhood = new Neighborhood((new NeighborhoodFactory())->definition());

		$neighborhood->save();

		$street = new Street((new StreetFactory())->definition());

		$street->save();
	}

	public function getURI($uuid): String
	{
		return '/api/schedules/' . $uuid;
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

		$this->serviceData = (new ServiceFactory())->definitionForAPI();

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
		$uri = $this->getURI($this->sut->establishment->uuid);

		$reponse = $this->json(
			'POST',
			$uri,
			[],
			[]
		);

		$reponse->assertStatus(401);
	}

	public function test_AssertsIfNotReturnsAuthErrorWithEstablishmentCredentials(): void
	{
		$uri = $this->getURI($this->sut->establishment->uuid);

		$auth = 'Bearer ' . $this->sut->token;

		$response = $this->json('POST', $uri, [], [
			'Authorization' => $auth
		]);

		$response->assertStatus(400);
	}

	public function test_AssertsIfReturnsAuthErrorWithClientCredentials(): void
	{
		$this->generateAddresses();

		$user = new User((new UserFactory())->definition());

		$user->save();

		$token = $user->createToken(
			User::GENERAL_TOKEN_NAME,
			[User::GENERAL_ABILITY]
		)->plainTextToken;

		$uri = $this->getURI($this->sut->establishment->uuid);

		$auth = 'Bearer ' . $token;

		$response = $this->json('POST', $uri, [], [
			'Authorization' => $auth
		]);

		$response->assertStatus(403);
	}

	public function test_AssertsIfReturnsErrorWithoutMaxServices(): void
	{
		$uri = $this->getURI($this->sut->establishment->uuid);

		$auth = 'Bearer ' . $this->sut->token;

		$response = $this->json('POST', $uri, [], [
			'Authorization' => $auth
		]);

		$response->assertStatus(400);

		$body = json_decode($response->getContent(), true);

		$attribute = TranslatedAttributeName::get('validation.attributes.maxServices');

		$message = __('validation.required', [
			'attribute' => $attribute,
		]);

		$this->assertIsArray($body);
		$this->assertArrayHasKey('errors', $body);
		$this->assertIsArray($body['errors']);
		$this->assertContains($message, $body['errors']);
	}

	public function test_AssertsIfReturnsErrorIfMaxServicesIsEmpty(): void
	{
		$uri = $this->getURI($this->sut->establishment->uuid);

		$auth = 'Bearer ' . $this->sut->token;

		$response = $this->json('POST', $uri, [
			'scheduleMaxServices' => ''
		], [
			'Authorization' => $auth
		]);

		$response->assertStatus(400);

		$body = json_decode($response->getContent(), true);

		$attribute = TranslatedAttributeName::get('validation.attributes.maxServices');

		$message = __('validation.required', [
			'attribute' => $attribute,
		]);

		$this->assertIsArray($body);
		$this->assertArrayHasKey('errors', $body);
		$this->assertIsArray($body['errors']);
		$this->assertContains($message, $body['errors']);
	}

	public function test_AssertsIfReturnsErrorIfMaxServicesIsNotAnInteger(): void
	{
		$uri = $this->getURI($this->sut->establishment->uuid);

		$auth = 'Bearer ' . $this->sut->token;

		$response = $this->json('POST', $uri, [
			'scheduleMaxServices' => 'abc'
		], [
			'Authorization' => $auth
		]);

		$response->assertStatus(400);

		$body = json_decode($response->getContent(), true);

		$attribute = TranslatedAttributeName::get('validation.attributes.maxServices');

		$message = __('validation.integer', [
			'attribute' => $attribute,
		]);

		$this->assertIsArray($body);
		$this->assertArrayHasKey('errors', $body);
		$this->assertIsArray($body['errors']);
		$this->assertContains($message, $body['errors']);
	}

	public function test_AssertsIfNotReturnsErrorIfMaxServicesIsOk(): void
	{
		$uri = $this->getURI($this->sut->establishment->uuid);

		$auth = 'Bearer ' . $this->sut->token;

		$response = $this->json('POST', $uri, [
			'scheduleMaxServices' => '1'
		], [
			'Authorization' => $auth
		]);

		$response->assertStatus(400);

		$body = json_decode($response->getContent(), true);

		$attribute = TranslatedAttributeName::get('validation.attributes.maxServices');

		$this->assertIsArray($body);
		$this->assertArrayHasKey('errors', $body);
		$this->assertIsArray($body['errors']);

		foreach ($body['errors'] as $error) {
			$this->assertStringNotContainsString($attribute, $error);
		}
	}

	public function test_AssertsIfReturnsErrorWithoutTime(): void
	{
		$uri = $this->getURI($this->sut->establishment->uuid);

		$auth = 'Bearer ' . $this->sut->token;

		$response = $this->json('POST', $uri, [], [
			'Authorization' => $auth
		]);

		$response->assertStatus(400);

		$body = json_decode($response->getContent(), true);

		$attribute = TranslatedAttributeName::get('validation.attributes.time');

		$message = __('validation.required', [
			'attribute' => $attribute,
		]);

		$this->assertIsArray($body);
		$this->assertArrayHasKey('errors', $body);
		$this->assertIsArray($body['errors']);
		$this->assertContains($message, $body['errors']);
	}

	public function test_AssertsIfReturnsErrorIfTimeIsEmpty(): void
	{
		$uri = $this->getURI($this->sut->establishment->uuid);

		$auth = 'Bearer ' . $this->sut->token;

		$response = $this->json('POST', $uri, [
			'scheduleTime' => ''
		], [
			'Authorization' => $auth
		]);

		$response->assertStatus(400);

		$body = json_decode($response->getContent(), true);

		$attribute = TranslatedAttributeName::get('validation.attributes.time');

		$message = __('validation.required', [
			'attribute' => $attribute,
		]);

		$this->assertIsArray($body);
		$this->assertArrayHasKey('errors', $body);
		$this->assertIsArray($body['errors']);
		$this->assertContains($message, $body['errors']);
	}

	public function test_AssertsIfReturnsErrorIfTimeIsNotInHourMinutFormat(): void
	{
		$uri = $this->getURI($this->sut->establishment->uuid);

		$auth = 'Bearer ' . $this->sut->token;

		$response = $this->json('POST', $uri, [
			'scheduleTime' => 1234
		], [
			'Authorization' => $auth
		]);

		$response->assertStatus(400);

		$body = json_decode($response->getContent(), true);

		$attribute = TranslatedAttributeName::get('validation.attributes.time');

		$format = "H:i";

		$message = __('validation.date_format', [
			'attribute' => $attribute,
			'format' => $format,
		]);

		$this->assertIsArray($body);
		$this->assertArrayHasKey('errors', $body);
		$this->assertIsArray($body['errors']);
		$this->assertContains($message, $body['errors']);
	}

	public function test_AssertsIfNotReturnsErrorIfTimeIsOk(): void
	{
		$uri = $this->getURI($this->sut->establishment->uuid);

		$auth = 'Bearer ' . $this->sut->token;

		$response = $this->json('POST', $uri, [
			'scheduleTime' => '12:30'
		], [
			'Authorization' => $auth
		]);

		$response->assertStatus(400);

		$body = json_decode($response->getContent(), true);

		$attribute = TranslatedAttributeName::get('validation.attributes.time');

		$this->assertIsArray($body);
		$this->assertArrayHasKey('errors', $body);
		$this->assertIsArray($body['errors']);

		foreach ($body['errors'] as $error) {
			$this->assertStringNotContainsString($attribute, $error);
		}
	}

	public function test_AssertsIfReturnsErrorWithoutDuration(): void
	{
		$uri = $this->getURI($this->sut->establishment->uuid);

		$auth = 'Bearer ' . $this->sut->token;

		$response = $this->json('POST', $uri, [], [
			'Authorization' => $auth
		]);

		$response->assertStatus(400);

		$body = json_decode($response->getContent(), true);

		$attribute = TranslatedAttributeName::get('validation.attributes.duration');

		$message = __('validation.required', [
			'attribute' => $attribute,
		]);

		$this->assertIsArray($body);
		$this->assertArrayHasKey('errors', $body);
		$this->assertIsArray($body['errors']);
		$this->assertContains($message, $body['errors']);
	}

	public function test_AssertsIfReturnsErrorIfDurationIsEmpty(): void
	{
		$uri = $this->getURI($this->sut->establishment->uuid);

		$auth = 'Bearer ' . $this->sut->token;

		$response = $this->json('POST', $uri, [
			'scheduleDuration' => ''
		], [
			'Authorization' => $auth
		]);

		$response->assertStatus(400);

		$body = json_decode($response->getContent(), true);

		$attribute = TranslatedAttributeName::get('validation.attributes.duration');

		$message = __('validation.required', [
			'attribute' => $attribute,
		]);

		$this->assertIsArray($body);
		$this->assertArrayHasKey('errors', $body);
		$this->assertIsArray($body['errors']);
		$this->assertContains($message, $body['errors']);
	}

	public function test_AssertsIfReturnsErrorIfDurationIsNotAnInteger(): void
	{
		$uri = $this->getURI($this->sut->establishment->uuid);

		$auth = 'Bearer ' . $this->sut->token;

		$response = $this->json('POST', $uri, [
			'scheduleDuration' => 'abcde'
		], [
			'Authorization' => $auth
		]);

		$response->assertStatus(400);

		$body = json_decode($response->getContent(), true);

		$attribute = TranslatedAttributeName::get('validation.attributes.duration');

		$message = __('validation.integer', [
			'attribute' => $attribute,
		]);

		$this->assertIsArray($body);
		$this->assertArrayHasKey('errors', $body);
		$this->assertIsArray($body['errors']);
		$this->assertContains($message, $body['errors']);
	}

	public function test_AssertsIfNotReturnsErrorIfDurationIsOk(): void
	{
		$uri = $this->getURI($this->sut->establishment->uuid);

		$auth = 'Bearer ' . $this->sut->token;

		$response = $this->json('POST', $uri, [
			'scheduleDuration' => 500
		], [
			'Authorization' => $auth
		]);

		$response->assertStatus(400);

		$body = json_decode($response->getContent(), true);

		$attribute = TranslatedAttributeName::get('validation.attributes.duration');

		$this->assertIsArray($body);
		$this->assertArrayHasKey('errors', $body);
		$this->assertIsArray($body['errors']);

		foreach ($body['errors'] as $error) {
			$this->assertStringNotContainsString($attribute, $error);
		}
	}
}
