<?php

namespace App\Utils;

class TranslatedAttributeName
{
	public static function get($attributeKey): String
	{
		$attribute = __($attributeKey);

		if ($attribute === $attributeKey) {
			$params = explode('.', $attributeKey);
			$attribute = $params[sizeof($params) - 1];
		}

		return $attribute;
	}
}
