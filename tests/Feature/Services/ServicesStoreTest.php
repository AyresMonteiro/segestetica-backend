<?php

namespace Tests\Feature\Services;

use App\Http\Handlers\LogHandler;
use App\Models\{
	Establishment,
};
use App\Utils\TranslatedAttributeName;
use Database\Factories\EstablishmentFactory;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCaseWithDatabase;

class ServicesStoreTest extends TestCaseWithDatabase
{
	use RefreshDatabase;

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

		$message = __('validation.required', ['attribute' => $attribute]);

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

		$message = __('validation.required', ['attribute' => $attribute]);

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

		$message = __('validation.required', ['attribute' => $attribute]);

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

		$message = __('validation.required', ['attribute' => $attribute]);

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

		$message = __('validation.required', ['attribute' => $attribute]);

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

		$message = __('validation.required', ['attribute' => $attribute]);

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

		$message = __('validation.required', ['attribute' => $attribute]);

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

		$message = __('validation.required', ['attribute' => $attribute]);

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

		foreach ($body['errors'] as $erro) {
			$this->assertStringNotContainsString($attribute, $erro);
		}
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
}
