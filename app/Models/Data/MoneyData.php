<?php

namespace App\Models\Data;

use App\Exceptions\BadFormattedMoneyDataException;

class MoneyData
{
	public function __construct(
		public int $integerValue,
		public int $decimalValue
	) {
		if ($integerValue < 0 && $decimalValue < 0) {
			throw new BadFormattedMoneyDataException(
				BadFormattedMoneyDataException::IntegerPartAndDecimalPartAreLesserThanZero
			);
		}

		if ($integerValue === 0 && $decimalValue < -99) {
			throw new BadFormattedMoneyDataException(
				BadFormattedMoneyDataException::DecimalPartLesserThanMinusNinetyNine
			);
		}

		if ($integerValue !== 0 && $decimalValue < 0) {
			throw new BadFormattedMoneyDataException(
				BadFormattedMoneyDataException::IntegerPartIsDifferentThanZeroAndDecimalPartAreLesserThanZero
			);
		}

		if ($decimalValue > 99) {
			throw new BadFormattedMoneyDataException(
				BadFormattedMoneyDataException::DecimalPartGreaterThanNinetyNine
			);
		}
	}

	public function checkIfIsEqualsToZero(): bool
	{
		return $this->integerValue == 0 && $this->decimalValue == 0;
	}

	public function checkIfIsGreaterThanZero(): bool
	{
		if ($this->checkIfIsEqualsToZero()) {
			return false;
		}

		return $this->integerValue > 0 || ($this->integerValue == 0 && $this->decimalValue > 0);
	}

	public function checkIfIsLesserThanZero(): bool
	{
		if ($this->checkIfIsEqualsToZero() || $this->checkIfIsGreaterThanZero()) {
			return false;
		}

		return $this->integerValue < 0 || ($this->integerValue == 0 && $this->decimalValue < 0);
	}

	private function resolvesParams($integer, $decimal)
	{
		if ($integer < 0 && $decimal !== 0) {
			return [$integer, $decimal * -1];
		}

		return [$integer, $decimal];
	}

	private function resolvesDecimalValue()
	{
		$integer = $this->integerValue;
		$decimal = $this->decimalValue;

		$howManyHundreds = intdiv($decimal, 100);

		$integer += $howManyHundreds;
		$decimal -= $howManyHundreds * 100;

		if ($decimal < 0 && $integer > 0) {
			$integer -= 1;
			$decimal += 100;
		}

		$this->integerValue = $integer;
		$this->decimalValue = $decimal;
	}

	public function sum(
		int $integerValue,
		int $decimalValue,
	): void {
		[$integerValue, $decimalValue] = $this->resolvesParams($integerValue, $decimalValue);

		$this->integerValue += $integerValue;

		$this->decimalValue += $decimalValue;

		$this->resolvesDecimalValue();
	}

	public function subtract(
		int $integerValue,
		int $decimalValue,
	): void {
		[$integerValue, $decimalValue] = $this->resolvesParams($integerValue, $decimalValue);

		$this->integerValue -= $integerValue;
		$this->decimalValue -= $decimalValue;

		$this->resolvesDecimalValue();
	}

	public function multiplicate(
		int $integerValue,
	): void {
		$this->integerValue *= $integerValue;
		$this->decimalValue *= $integerValue;

		$this->resolvesDecimalValue();
	}

	public static function sumValues(MoneyData $firstValue, MoneyData $secondValue): MoneyData
	{
		$firstValue->sum($secondValue->integerValue, $secondValue->decimalValue);

		return $firstValue;
	}

	public static function subtractValues(MoneyData $firstValue, MoneyData $secondValue): MoneyData
	{
		$firstValue->subtract($secondValue->integerValue, $secondValue->decimalValue);

		return $firstValue;
	}

	public function getStringValue(): string
	{
		$sign = $this->integerValue === 0 && $this->decimalValue < 0 ? '-' : '';

		$integerPart = $this->integerValue;
		$decimalPart = (abs($this->decimalValue) > 9 ? '' : '0') . abs($this->decimalValue);

		return $sign . $integerPart . '.' . $decimalPart;
	}
}
