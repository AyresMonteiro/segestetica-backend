<?php

namespace Tests\Unit;

use App\Rules\UnicodeName;
use PHPUnit\Framework\TestCase;

class UnicodeNameRuleTest extends TestCase
{
	public function makeSut(): UnicodeName
	{
		return new UnicodeName();
	}

	public function test_shouldPassASimpleName(): void
	{
		$sut = $this->makeSut();

		$this->assertEquals(true, $sut->passes('string', 'David Bowie'));
	}

	public function test_shouldPassANameWithApostrophe(): void
	{
		$sut = $this->makeSut();

		$this->assertEquals(true, $sut->passes('string', 'Dylan O\'Brien'));
	}

	public function test_shouldPassANameWithDot(): void
	{
		$sut = $this->makeSut();

		$this->assertEquals(true, $sut->passes('string', 'T. S. Elliot'));
	}

	public function test_shouldPassABrazilianName(): void
	{
		$sut = $this->makeSut();

		$this->assertEquals(true, $sut->passes('string', 'Adamastor Pitágoras'));
	}

	public function test_shouldPassAKatakanaName(): void
	{
		$sut = $this->makeSut();

		$this->assertEquals(true, $sut->passes('string', 'シロエ'));
	}

	public function test_shouldPassAKanjiName(): void
	{
		$sut = $this->makeSut();

		$this->assertEquals(true, $sut->passes('string', '森羅 日下部'));
	}

	public function test_shouldNotPassStringWithNumber(): void
	{
		$sut = $this->makeSut();

		$this->assertEquals(false, $sut->passes('string', 'Sei lá maluco 123'));
	}

	public function test_shouldNotPassStringWithDollarSign(): void
	{
		$sut = $this->makeSut();

		$this->assertEquals(false, $sut->passes('string', '$variavel'));
	}

	public function test_shouldNotPassStringWithSemicolon(): void
	{
		$sut = $this->makeSut();

		$this->assertEquals(false, $sut->passes('string', 'por fim;'));
	}

	public function test_shouldNotPassStringWithParenthesis(): void
	{
		$sut = $this->makeSut();

		$this->assertEquals(false, $sut->passes('string', '(i don\'t know btw)'));
	}

	public function test_shouldNotPassStringWithComma(): void
	{
		$sut = $this->makeSut();

		$this->assertEquals(false, $sut->passes('string', 'that\'s all, i think'));
	}
}
