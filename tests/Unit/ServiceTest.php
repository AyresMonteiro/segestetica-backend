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
}
