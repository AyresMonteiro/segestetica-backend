<?php

namespace Tests\Feature\Services;

use App\Http\Handlers\LogHandler;
use App\Models\{
	Establishment,
	EstablishmentService,
	Neighborhood,
	Street,
};
use App\Utils\TranslatedAttributeName;
use Database\Factories\{
	EstablishmentFactory,
	NeighborhoodFactory,
	StreetFactory
};
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Artisan;
use Tests\TestCaseWithDatabase;

class ServicesStoreTest extends TestCaseWithDatabase
{
	use RefreshDatabase;

	protected $locale = 'pt-BR';

	public $sut = null;
	public static $seeded = false;

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

	public function test_AssertsIfReturnsErrorWithoutAuth(): void
	{
		$response = $this->post('/api/services');

		$response->assertStatus(401);
	}

	public function test_AssertsIfReturnsErrorWithoutData(): void
	{
		$response = $this->post('/api/services', [], [
			'Authorization' => 'Bearer ' . $this->sut->token
		]);

		$response->assertStatus(400);
	}

	public function test_AssertsIfReturnsErrorWithoutName(): void
	{
		$response = $this->post('/api/services', [], [
			'Authorization' => 'Bearer ' . $this->sut->token
		]);

		$response->assertStatus(400);

		$body = json_decode($response->getContent(), true);

		$attribute = TranslatedAttributeName::get('validation.attributes.name');
		$values = TranslatedAttributeName::getAll(['validation.attributes.id']);

		$message = __('validation.required_without', [
			'attribute' => $attribute,
			'values' => $values
		]);

		$this->assertContains($message, $body['errors']);
	}

	public function test_AssertsIfReturnsErrorWhenNameIsNotAString(): void
	{
		$response = $this->json(
			'POST',
			'/api/services',
			['serviceName' => 87236812],
			['Authorization' => 'Bearer ' . $this->sut->token]
		);

		$response->assertStatus(400);

		$body = json_decode($response->getContent(), true);

		$attribute = TranslatedAttributeName::get('validation.attributes.name');

		$message = __('validation.string', ['attribute' => $attribute]);

		$this->assertContains($message, $body['errors']);
	}

	public function test_AssertsIfReturnsErrorWhenNameIsEmpty(): void
	{
		$response = $this->json(
			'POST',
			'/api/services',
			['serviceName' => ''],
			['Authorization' => 'Bearer ' . $this->sut->token]
		);

		$response->assertStatus(400);

		$body = json_decode($response->getContent(), true);

		$attribute = TranslatedAttributeName::get('validation.attributes.name');

		$values = TranslatedAttributeName::getAll(['validation.attributes.id']);

		$message = __('validation.required_without', [
			'attribute' => $attribute,
			'values' => $values
		]);

		$this->assertContains($message, $body['errors']);
	}

	public function test_AssertsIfReturnsErrorWhenNameIsNotAValidUnicodeName(): void
	{
		$response = $this->json(
			'POST',
			'/api/services',
			['serviceName' => "Tetudinho 13"],
			['Authorization' => 'Bearer ' . $this->sut->token]
		);

		$response->assertStatus(400);

		$body = json_decode($response->getContent(), true);

		$attribute = TranslatedAttributeName::get('validation.attributes.name');

		$message = __('validation.unicode_name', ['attribute' => $attribute]);

		$this->assertContains($message, $body['errors']);
	}

	public function test_AssertsIfNotReturnsErrorWhenNameIsAValidUnicodeName(): void
	{
		$response = $this->json(
			'POST',
			'/api/services',
			['serviceName' => "João da Silva"],
			['Authorization' => 'Bearer ' . $this->sut->token]
		);

		$response->assertStatus(400);

		$body = json_decode($response->getContent(), true);

		$attribute = TranslatedAttributeName::get('validation.attributes.name');

		$attribute = '"' . $attribute . '"';

		foreach ($body['errors'] as $erro) {
			$this->assertStringNotContainsString($attribute, $erro);
		}
	}

	public function test_AssertsIfReturnsErrorWithoutDescription(): void
	{
		$response = $this->post('/api/services', [], [
			'Authorization' => 'Bearer ' . $this->sut->token
		]);

		$response->assertStatus(400);

		$body = json_decode($response->getContent(), true);

		$attribute = TranslatedAttributeName::get('validation.attributes.description');

		$values = TranslatedAttributeName::getAll(['validation.attributes.id']);

		$message = __('validation.required_without', [
			'attribute' => $attribute,
			'values' => $values
		]);

		$this->assertContains($message, $body['errors']);
	}

	public function test_AssertsIfReturnsErrorWhenDescriptionIsEmpty(): void
	{
		$response = $this->json(
			'POST',
			'/api/services',
			['serviceDescription' => ''],
			['Authorization' => 'Bearer ' . $this->sut->token]
		);

		$response->assertStatus(400);

		$body = json_decode($response->getContent(), true);

		$attribute = TranslatedAttributeName::get('validation.attributes.description');

		$values = TranslatedAttributeName::getAll(['validation.attributes.id']);

		$message = __('validation.required_without', [
			'attribute' => $attribute,
			'values' => $values
		]);

		$this->assertContains($message, $body['errors']);
	}

	public function test_AssertsIfReturnsErrorWhenDescriptionIsNotAValidUnicodeText(): void
	{
		$response = $this->json(
			'POST',
			'/api/services',
			['serviceDescription' => "Tetudinho: número 8. 😋"],
			['Authorization' => 'Bearer ' . $this->sut->token]
		);

		$response->assertStatus(400);

		$body = json_decode($response->getContent(), true);

		$attribute = TranslatedAttributeName::get('validation.attributes.description');

		$message = __('validation.unicode_text', ['attribute' => $attribute]);

		$this->assertContains($message, $body['errors']);
	}

	public function test_AssertsIfNotReturnsErrorWhenDescriptionIsAValidUnicodeText(): void
	{
		$response = $this->json(
			'POST',
			'/api/services',
			['serviceDescription' => "Tetudinho: número 8."],
			['Authorization' => 'Bearer ' . $this->sut->token]
		);

		$response->assertStatus(400);

		$body = json_decode($response->getContent(), true);

		$attribute = TranslatedAttributeName::get('validation.attributes.description');

		$attribute = '"' . $attribute . '"';

		foreach ($body['errors'] as $erro) {
			$this->assertStringNotContainsString($attribute, $erro);
		}
	}

	public function test_AssertsIfReturnsErrorWithoutIntegerValue(): void
	{
		$response = $this->post('/api/services', [], [
			'Authorization' => 'Bearer ' . $this->sut->token
		]);

		$response->assertStatus(400);

		$body = json_decode($response->getContent(), true);

		$attribute = TranslatedAttributeName::get('validation.attributes.integerValue');

		$values = TranslatedAttributeName::getAll(['validation.attributes.id']);

		$message = __('validation.required_without', [
			'attribute' => $attribute,
			'values' => $values
		]);

		$this->assertContains($message, $body['errors']);
	}

	public function test_AssertsIfReturnsErrorWhenIntegerValueIsEmpty(): void
	{
		$response = $this->json(
			'POST',
			'/api/services',
			['serviceIntegerValue' => ''],
			['Authorization' => 'Bearer ' . $this->sut->token]
		);

		$response->assertStatus(400);

		$body = json_decode($response->getContent(), true);

		$attribute = TranslatedAttributeName::get('validation.attributes.integerValue');

		$values = TranslatedAttributeName::getAll(['validation.attributes.id']);

		$message = __('validation.required_without', [
			'attribute' => $attribute,
			'values' => $values
		]);

		$this->assertContains($message, $body['errors']);
	}

	public function test_AssertsIfReturnsErrorWhenIntegerValueIsNotAnInteger(): void
	{
		$response = $this->json(
			'POST',
			'/api/services',
			['serviceIntegerValue' => 'seila lek'],
			['Authorization' => 'Bearer ' . $this->sut->token]
		);

		$response->assertStatus(400);

		$body = json_decode($response->getContent(), true);

		$attribute = TranslatedAttributeName::get('validation.attributes.integerValue');

		$message = __('validation.integer', ['attribute' => $attribute]);

		$this->assertContains($message, $body['errors']);
	}

	public function test_AssertsIfNotReturnsErrorWhenIntegerValueIsAInteger(): void
	{
		$response = $this->json(
			'POST',
			'/api/services',
			['serviceIntegerValue' => '110'],
			['Authorization' => 'Bearer ' . $this->sut->token]
		);

		$response->assertStatus(400);

		$body = json_decode($response->getContent(), true);

		$attribute = TranslatedAttributeName::get('validation.attributes.integerValue');

		$attribute = '"' . $attribute . '"';

		foreach ($body['errors'] as $erro) {
			$this->assertStringNotContainsString($attribute, $erro);
		}
	}

	public function test_AssertsIfReturnsErrorWithoutFractionalValue(): void
	{
		$response = $this->post('/api/services', [], [
			'Authorization' => 'Bearer ' . $this->sut->token
		]);

		$response->assertStatus(400);

		$body = json_decode($response->getContent(), true);

		$attribute = TranslatedAttributeName::get('validation.attributes.fractionalValue');

		$values = TranslatedAttributeName::getAll(['validation.attributes.id']);

		$message = __('validation.required_without', [
			'attribute' => $attribute,
			'values' => $values
		]);

		$this->assertContains($message, $body['errors']);
	}

	public function test_AssertsIfReturnsErrorWhenFractionalValueIsEmpty(): void
	{
		$response = $this->json(
			'POST',
			'/api/services',
			['serviceFractionalValue' => ''],
			['Authorization' => 'Bearer ' . $this->sut->token]
		);

		$response->assertStatus(400);

		$body = json_decode($response->getContent(), true);

		$attribute = TranslatedAttributeName::get('validation.attributes.fractionalValue');

		$values = TranslatedAttributeName::getAll(['validation.attributes.id']);

		$message = __('validation.required_without', [
			'attribute' => $attribute,
			'values' => $values
		]);

		$this->assertContains($message, $body['errors']);
	}

	public function test_AssertsIfReturnsErrorWhenFractionalValueIsNotAnInteger(): void
	{
		$response = $this->json(
			'POST',
			'/api/services',
			['serviceFractionalValue' => 'seila lek'],
			['Authorization' => 'Bearer ' . $this->sut->token]
		);

		$response->assertStatus(400);

		$body = json_decode($response->getContent(), true);

		$attribute = TranslatedAttributeName::get('validation.attributes.fractionalValue');

		$message = __('validation.integer', ['attribute' => $attribute]);

		$this->assertContains($message, $body['errors']);
	}

	public function test_AssertsIfNotReturnsErrorWhenFractionalValueIsAInteger(): void
	{
		$response = $this->json(
			'POST',
			'/api/services',
			['serviceFractionalValue' => '110'],
			['Authorization' => 'Bearer ' . $this->sut->token]
		);

		$response->assertStatus(400);

		$body = json_decode($response->getContent(), true);

		$attribute = TranslatedAttributeName::get('validation.attributes.fractionalValue');

		$attribute = '"' . $attribute . '"';

		foreach ($body['errors'] as $erro) {
			$this->assertStringNotContainsString($attribute, $erro);
		}
	}

	public function test_AssertsIfReturnsErrorWhenIdIsEmpty(): void
	{
		$response = $this->json(
			'POST',
			'/api/services',
			['serviceId' => ''],
			['Authorization' => 'Bearer ' . $this->sut->token]
		);

		$response->assertStatus(400);

		$body = json_decode($response->getContent(), true);

		$attribute = TranslatedAttributeName::get('validation.attributes.id');

		$values = TranslatedAttributeName::getAll([
			'validation.attributes.name',
			'validation.attributes.description',
			'validation.attributes.integerValue',
			'validation.attributes.fractionalValue',
		]);

		$message = __('validation.required_without', [
			'attribute' => $attribute,
			'values' => $values
		]);

		$this->assertContains($message, $body['errors']);
	}

	public function test_AssertsIfReturnsErrorWhenIdIsNotAnInteger(): void
	{
		$response = $this->json(
			'POST',
			'/api/services',
			['serviceId' => 'abc'],
			['Authorization' => 'Bearer ' . $this->sut->token]
		);

		$response->assertStatus(400);

		$body = json_decode($response->getContent(), true);

		$attribute = TranslatedAttributeName::get('validation.attributes.id');

		$message = __('validation.integer', [
			'attribute' => $attribute,
		]);

		$this->assertContains($message, $body['errors']);
	}

	public function test_AssertsIfCanCreateServiceWhenSendId(): void
	{
		$response = $this->json(
			'POST',
			'/api/services',
			SUT::CREATE_SERVICE_CORRECT_DATA_1,
			['Authorization' => 'Bearer ' . $this->sut->token]
		);

		$response->assertStatus(201);

		$body = json_decode($response->getContent(), true);

		$correctValue =
			SUT::CREATE_SERVICE_CORRECT_DATA_1['serviceIntegerValue'] .
			"." .
			SUT::CREATE_SERVICE_CORRECT_DATA_1['serviceFractionalValue'];

		$this->assertSame($correctValue, $body['value']);

		EstablishmentService::truncate();

		$response = $this->json(
			'POST',
			'/api/services',
			['serviceId' => 1],
			['Authorization' => 'Bearer ' . $this->sut->token]
		);

		$response->assertStatus(201);

		$body = json_decode($response->getContent(), true);

		$correctValue =
			SUT::CREATE_SERVICE_CORRECT_DATA_1['serviceIntegerValue'] .
			"." .
			SUT::CREATE_SERVICE_CORRECT_DATA_1['serviceFractionalValue'];

		$this->assertSame($correctValue, $body['value']);
	}

	public function test_AssertsIfCannotCreateServiceWhenIdDoesntExists(): void
	{
		$response = $this->json(
			'POST',
			'/api/services',
			SUT::CREATE_SERVICE_CORRECT_DATA_1,
			['Authorization' => 'Bearer ' . $this->sut->token]
		);

		$response->assertStatus(201);

		$body = json_decode($response->getContent(), true);

		$correctValue =
			SUT::CREATE_SERVICE_CORRECT_DATA_1['serviceIntegerValue'] .
			"." .
			SUT::CREATE_SERVICE_CORRECT_DATA_1['serviceFractionalValue'];

		$this->assertSame($correctValue, $body['value']);

		EstablishmentService::truncate();

		$response = $this->json(
			'POST',
			'/api/services',
			['serviceId' => 2],
			['Authorization' => 'Bearer ' . $this->sut->token]
		);

		$response->assertStatus(404);

		$body = json_decode($response->getContent(), true);

		$message = __('messages.not_found');

		$this->assertContains($message, $body['errors']);
	}

	public function test_AssertsIfCanCreateServiceWhenDataIsOk(): void
	{
		$response = $this->json(
			'POST',
			'/api/services',
			SUT::CREATE_SERVICE_CORRECT_DATA_1,
			['Authorization' => 'Bearer ' . $this->sut->token]
		);

		$response->assertStatus(201);

		$body = json_decode($response->getContent(), true);

		$correctValue =
			SUT::CREATE_SERVICE_CORRECT_DATA_1['serviceIntegerValue'] .
			"." .
			SUT::CREATE_SERVICE_CORRECT_DATA_1['serviceFractionalValue'];

		$this->assertSame($correctValue, $body['value']);
	}

	public function test_AssertsIfServiceIsAssociatedWithEstablishmentAfterItsCreation(): void
	{
		$this->generateAddresses();

		$response = $this->json(
			'POST',
			'/api/services',
			SUT::CREATE_SERVICE_CORRECT_DATA_1,
			['Authorization' => 'Bearer ' . $this->sut->token]
		);

		$response->assertStatus(201);

		$service = json_decode($response->getContent(), true);

		$correctValue =
			SUT::CREATE_SERVICE_CORRECT_DATA_1['serviceIntegerValue'] .
			"." .
			SUT::CREATE_SERVICE_CORRECT_DATA_1['serviceFractionalValue'];

		$this->assertSame($correctValue, $service['value']);

		$response = $this->json(
			'GET',
			'/api/establishments/' . $this->sut->establishment->uuid,
			[],
			['Authorization' => 'Bearer ' . $this->sut->token]
		);

		$response->assertStatus(200);

		$body = json_decode($response->getContent(), true);

		$this->assertIsArray($body);
		$this->assertArrayHasKey('services', $body);
		$this->assertIsArray($body['services']);
		$this->assertSame(1, sizeof($body['services']));
		$this->assertSame($service['id'], $body['services'][0]['id']);
		$this->assertSame($service['created_at'], $body['services'][0]['created_at']);
		$this->assertSame($service['updated_at'], $body['services'][0]['updated_at']);
		$this->assertSame($service['name'], $body['services'][0]['name']);
		$this->assertSame($service['description'], $body['services'][0]['description']);
		$this->assertSame($service['value'], $body['services'][0]['value']);
	}
}