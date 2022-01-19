<?php

namespace Tests\Unit;

use App\Rules\UnicodeText;
use PHPUnit\Framework\TestCase;

class UnicodeTextRuleTest extends TestCase
{
	public function makeSut(): UnicodeText
	{
		return new UnicodeText();
	}

	public function test_shouldPassSimpleText(): void
	{
		$sut = $this->makeSut();

		$this->assertEquals(true, $sut->passes('string', 'Hello world'));
	}

	public function test_shouldPassTextWithApostrophe(): void
	{
		$sut = $this->makeSut();

		$this->assertEquals(true, $sut->passes('string', 'That\'s it'));
	}

	public function test_shouldPassTextWithDot(): void
	{
		$sut = $this->makeSut();

		$this->assertEquals(true, $sut->passes('string', 'Hi. Hello'));
	}

	public function test_shouldPassTextWithLatinCharacters(): void
	{
		$sut = $this->makeSut();

		$this->assertEquals(true, $sut->passes('string', 'E aí'));
	}

	public function test_shouldPassAKatakanaText(): void
	{
		$sut = $this->makeSut();

		$this->assertEquals(true, $sut->passes('string', 'シロエ'));
	}

	public function test_shouldPassAKanjiText(): void
	{
		$sut = $this->makeSut();

		$this->assertEquals(true, $sut->passes('string', '森羅 日下部'));
	}

	public function test_shouldPassTextWithNumber(): void
	{
		$sut = $this->makeSut();

		$this->assertEquals(true, $sut->passes('string', 'Sei lá maluco 123'));
	}

	public function test_shouldPassTextWithDollarSign(): void
	{
		$sut = $this->makeSut();

		$this->assertEquals(true, $sut->passes('string', '$variavel'));
	}

	public function test_shouldPassTextWithSemicolon(): void
	{
		$sut = $this->makeSut();

		$this->assertEquals(true, $sut->passes('string', 'por fim;'));
	}

	public function test_shouldPassTextWithParenthesis(): void
	{
		$sut = $this->makeSut();

		$this->assertEquals(true, $sut->passes('string', '(i know btw)'));
	}

	public function test_shouldPassTextWithComma(): void
	{
		$sut = $this->makeSut();

		$this->assertEquals(true, $sut->passes('string', 'that\'s all, i think'));
	}

	public function test_shouldPassTextWithColon(): void
	{
		$sut = $this->makeSut();

		$this->assertEquals(true, $sut->passes('string', 'that\'s all: i drink it'));
	}

	public function test_shouldNotPassTextWithEmoji(): void
	{
		$sut = $this->makeSut();

		$this->assertEquals(false, $sut->passes('string', '😋'));
	}
}
