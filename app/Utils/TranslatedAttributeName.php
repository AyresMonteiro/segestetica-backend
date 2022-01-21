<?php

namespace App\Utils;

class TranslatedAttributeName
{
	public static function get(String $attributeKey): String
	{
		$attribute = __($attributeKey);

		if ($attribute === $attributeKey) {
			$params = explode('.', $attributeKey);
			$attribute = $params[sizeof($params) - 1];
			$parts = preg_split('/(?=[A-Z])/', $attribute);

			foreach ($parts as &$part) {
				$part = strtolower($part);
			}

			$attribute = join(' ', $parts);
		}

		return $attribute;
	}

	public static function getAll(array $attributeKeys): String
	{
		$values = "";

		foreach ($attributeKeys as $attributeKey) {
			$values .= self::get($attributeKey) . " / ";
		}

		return substr($values, 0, -3);
	}
}
