<?php

namespace App\Models\Data;

use App\Exceptions\GenericAppException;

class BearerData
{
	public string $token;

	public function __construct(
		public string $rawBearer
	) {
		$splittedBearer = explode(' ', $rawBearer);

		if (sizeof($splittedBearer) !== 2) {
			throw new GenericAppException([__('messages.bearer.bad_format')], 401);
		}

		if ($splittedBearer[0] !== 'Bearer') {
			throw new GenericAppException([__('messages.bearer.bad_format')], 401);
		}

		$this->token = $splittedBearer[1];
	}
}
