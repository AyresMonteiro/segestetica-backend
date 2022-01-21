<?php

namespace Tests\Unit;

use Carbon\Carbon;
use PHPUnit\Framework\TestCase;

class CarbonDataTest extends TestCase
{
	/**
	 * A basic test example.
	 *
	 * @return void
	 */
	public function testCustomCarbonDates()
	{
		$date = Carbon::parse('2022-01', '-3');

		$date->setTime('3', 2);
		// $date->setTimezone('-3');

		$this->assertSame('2022-01-01T03:02:00-0300', $date->format(DATE_ISO8601));
		$this->assertSame('2022-01-01', $date->toDateString());
	}
}
