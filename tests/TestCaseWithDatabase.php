<?php

namespace Tests;

use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;

class TestCaseWithDatabase extends TestCase
{
	public function setUp(): void
	{
		parent::setUp();

		// DB::statement('create database segestetica;');

		Artisan::call('migrate');
	}

	public function tearDown(): void
	{
		parent::tearDown();

		// DB::statement('drop database segestetica;');
	}
}
