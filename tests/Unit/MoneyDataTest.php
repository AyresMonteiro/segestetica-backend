<?php

namespace Tests\Unit;

use App\Exceptions\BadFormattedMoneyDataException;
use App\Models\Data\MoneyData;
use PHPUnit\Framework\TestCase;

class MoneyDataTest extends TestCase
{
    public static function makeSut($integerValue, $decimalValue): MoneyData
    {
        return new MoneyData($integerValue, $decimalValue);
    }

    public function test_assertsIfBadFormattedMoneyDataExceptionIsThrownWhenIntegerValueAndDecimalValueAreLesserThanZero()
    {
        $this->expectException(BadFormattedMoneyDataException::class);

        self::makeSut(-10, -1);
    }

    public function test_assertsIfBadFormattedMoneyDataExceptionIsThrownWhenIntegerValueIsEqualToZeroAndDecimalValueIsLesserThanNinetyNine()
    {
        $this->expectException(BadFormattedMoneyDataException::class);

        self::makeSut(0, -100);
    }

    public function test_assertsIfBadFormattedMoneyDataExceptionIsThrownWhenIntegerValueIsDifferentThanZeroAndDecimalValueIsLesserThanZero()
    {
        $this->expectException(BadFormattedMoneyDataException::class);

        self::makeSut(23, -99);
    }

    public function test_assertsIfBadFormattedMoneyDataExceptionIsThrownWhenDecimalValueAreGreaterThanNinetyNine()
    {
        $this->expectException(BadFormattedMoneyDataException::class);

        self::makeSut(10, 100);
    }

    public function test_assertsIfCorrectlySumPositiveValues()
    {
        $sut = self::makeSut(10, 12);

        $sut->sum(1, 92);

        $this->assertEquals("12.04", $sut->getStringValue());
    }

    public function test_assertsIfCorrectlySumPositiveValuesToNegativeValuesGreaterThanMinusOne()
    {
        $sut = self::makeSut(0, -98);

        $sut->sum(1, 92);

        $this->assertEquals("0.94", $sut->getStringValue());
    }

    public function test_assertsIfCorrectlySumNegativeValues()
    {
        $sut = self::makeSut(10, 12);

        $sut->sum(-1, 92);

        $this->assertEquals("8.20", $sut->getStringValue());
    }

    public function test_assertsIfCorrectlySumNegativeValuesGreaterThanMinusOne()
    {
        $sut = self::makeSut(10, 12);

        $sut->sum(0, -12);

        $this->assertEquals("10.00", $sut->getStringValue());
    }

    public function test_assertsIfCorrectlySumNegativeValuesToNegativeValuesGreaterThanMinusOne()
    {
        $sut = self::makeSut(0, -98);

        $sut->sum(0, -2);

        $this->assertEquals("-1.00", $sut->getStringValue());
    }

    public function test_assertsIfCorrectlySubtractPositiveValues()
    {
        $sut = self::makeSut(10, 12);

        $sut->subtract(1, 92);

        $this->assertEquals("8.20", $sut->getStringValue());
    }

    public function test_assertsIfCorrectlySubtractNegativeValues()
    {
        $sut = self::makeSut(10, 12);

        $sut->subtract(-1, 92);

        $this->assertEquals("12.04", $sut->getStringValue());
    }

    public function test_assertsIfCorrectlyMultiplicatePositiveIntegerValues()
    {
        $sut = self::makeSut(10, 25);

        $sut->multiplicate(5);

        $this->assertEquals("51.25", $sut->getStringValue());
    }

    public function test_assertsIfCorrectlyMultiplicateNegativeIntegerValues()
    {
        $sut = self::makeSut(10, 25);

        $sut->multiplicate(-5);

        $this->assertEquals("-51.25", $sut->getStringValue());
    }

    public function test_assertsIfCorrectlyStaticSumPositiveValues()
    {
        $sut = MoneyData::sumValues(self::makeSut(10, 12), self::makeSut(9, 8));

        $this->assertEquals("19.20", $sut->getStringValue());
    }

    public function test_assertsIfCorrectlyStaticSumNegativeValues()
    {
        $sut = MoneyData::sumValues(self::makeSut(10, 12), self::makeSut(-9, 12));

        $this->assertEquals("1.00", $sut->getStringValue());
    }

    public function test_assertsIfCorrectlyStaticSubtractPositiveValues()
    {
        $sut = MoneyData::subtractValues(self::makeSut(10, 12), self::makeSut(9, 8));

        $this->assertEquals("1.04", $sut->getStringValue());
    }

    public function test_assertsIfCorrectlyStaticSubtractNegativeValues()
    {
        $sut = MoneyData::subtractValues(self::makeSut(10, 12), self::makeSut(-9, 12));

        $this->assertEquals("19.24", $sut->getStringValue());
    }

    public function test_assertsIfCorrectlyParsesNegativeValuesGreaterThanMinusOne()
    {
        $sut = self::makeSut(0, 0);

        $sut->subtract(0, 77);

        $this->assertEquals("-0.77", $sut->getStringValue());
    }

    public function test_assertsIfZeroIsEqualsToZero()
    {
        $sut = self::makeSut(0, 0);

        $this->assertEquals(true, $sut->checkIfIsEqualsToZero());
    }

    public function test_assertsIfNegativeValuesGreaterThanMinusOneAreDifferentThanZero()
    {
        $sut = self::makeSut(0, -23);

        $this->assertEquals(false, $sut->checkIfIsEqualsToZero());
    }

    public function test_assertsIfPositiveValuesLesserThanOneAreDifferentThanZero()
    {
        $sut = self::makeSut(0, 29);

        $this->assertEquals(false, $sut->checkIfIsEqualsToZero());
    }

    public function test_assertsIfPositiveNumberIsGreaterThanZero()
    {
        $sut = self::makeSut(12, 23);

        $this->assertEquals(true, $sut->checkIfIsGreaterThanZero());
    }

    public function test_assertsIfNegativeNumberIsNotGreaterThanZero()
    {
        $sut = self::makeSut(-12, 23);

        $this->assertEquals(false, $sut->checkIfIsGreaterThanZero());
    }

    public function test_assertsIfNegativeNumberIsLesserThanZero()
    {
        $sut = self::makeSut(-12, 23);

        $this->assertEquals(true, $sut->checkIfIsLesserThanZero());
    }

    public function test_assertsIfPositiveNumberIsNotLesserThanZero()
    {
        $sut = self::makeSut(12, 23);

        $this->assertEquals(false, $sut->checkIfIsLesserThanZero());
    }
}
