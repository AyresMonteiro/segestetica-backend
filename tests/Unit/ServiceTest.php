<?php

namespace Tests\Unit;

use App\Models\Service;
use PHPUnit\Framework\TestCase;

class ServiceTest extends TestCase
{
	private static function makeSut(array $data = []): Service
	{
		return new Service($data);
	}

	public function test_assertsIfServiceReturnsCorrectStringValue()
	{
		$sut = self::makeSut([
			'integerValue' => 19,
			'fractionalValue' => 7,
		]);


		$this->assertEquals("19.07", $sut->value);
	}

	public function test_assertsIfServiceMoneyInfoIsNullWhenModelHasNoData()
	{
		$sut = self::makeSut();

		$this->assertEquals(null, $sut->getMoneyInfo());
	}

	public function test_assertsIfServiceMoneyInfoIsNotNullWhenModelHasData()
	{
		$sut = self::makeSut([
			'integerValue' => 19,
			'fractionalValue' => 7,
		]);

		$this->assertNotEquals(null, $sut->getMoneyInfo());
	}

	public function test_assertsIfServiceMoneyInfoIsAnInstanceOfMoneyDataClass()
	{
		$sut = self::makeSut([
			'integerValue' => 19,
			'fractionalValue' => 7,
		]);

		$this->assertEquals(MoneyData::class, $sut->getMoneyInfo()::class);
	}
}
