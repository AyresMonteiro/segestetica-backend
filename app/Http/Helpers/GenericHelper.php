<?php

namespace App\Http\Helpers;

use App\Exceptions\GenericAppException;
use App\Http\Handlers\ErrorResponseHandler;
use App\Http\Handlers\ValidatorHandler;
use Exception;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator as FacadesValidator;

class GenericHelper
{
  public const UUIDRegex = "/^.{8}-.{4}-.{4}-.{4}-.{12}$/";
  public const GreaterThanRegex = "/^(.*)_greater_than$/";
  public const LesserThanRegex = "/^(.*)_lesser_than$/";
  public const SearchRegex = "/^(.*)_search$/";

  public static function genericTryCatchFactory($clojure)
  {
    return function (Request $req) use ($clojure) {
      try {
        return $clojure($req);
      } catch (GenericAppException $e) {
        $e->performActions();

        return ErrorResponseHandler::customError($e->getCustomErrors(), $e->getStatusCode());
      } catch (Exception $e) {
        return ErrorResponseHandler::defaultError();
      }
    };
  }

  public static function validate(Validator $validator)
  {
    $errors = ValidatorHandler::validate($validator);

    if (sizeof($errors) > 0) {
      throw new GenericAppException($errors, 400);
    }
  }

  public static function validateUUID($uuid, $messages = [
    'uuid.required' => 'Envie um identificador!',
    'uuid.regex' => 'Envie um identificador válido!',
  ], $paramName = 'uuid')
  {
    $validator = FacadesValidator::make(
      [$paramName => $uuid],
      [$paramName => ['required', 'regex:' . self::UUIDRegex]],
      $messages
    );

    self::validate($validator);
  }

  public static function isCompareValue(string $key)
  {
    $isCompareValue = preg_match(self::GreaterThanRegex, $key);
    $isCompareValue = $isCompareValue ? true : preg_match(self::LesserThanRegex, $key);

    return $isCompareValue;
  }

  public static function isSearchValue(string $key)
  {
    return preg_match(self::SearchRegex, $key);
  }

  public static function getCompareValues(array $data)
  {
    $compareValues = [];

    foreach ($data as $key => $value) {
      if (self::isCompareValue($key)) {
        $compareValues[$key] = $value;
      }
    }

    return $compareValues;
  }

  public static function getSearchValues(array $data)
  {
    $searchValues = [];

    foreach ($data as $key => $value) {
      if (self::isSearchValue($key)) {
        $searchValues[$key] = $value;
      }
    }

    return $searchValues;
  }

  public static function getFixedValues(array $data)
  {
    $fixedValues = [];

    foreach ($data as $key => $value) {
      if (!self::isCompareValue($key) && !self::isSearchValue($key)) {
        $fixedValues[$key] = $value;
      }
    }

    return $fixedValues;
  }

  public static function handleGreaterValue(
    Builder $builderInstance,
    $attributeName,
    $value
  ) {
    return $builderInstance->where($attributeName, ">", $value);
  }

  public static function handleLesserValue(
    Builder $builderInstance,
    $attributeName,
    $value
  ) {
    return $builderInstance->where($attributeName, "<", $value);
  }

  public static function handleSearchValue(
    Builder $builderInstance,
    $searchKey,
    $searchValue
  ) {
    if (preg_match(self::SearchRegex, $searchKey)) {
      $attributeName = preg_replace(self::SearchRegex, "$1", $searchKey);
      return $builderInstance->where($attributeName, "like", '%' . $searchValue . '%');
    }

    throw new GenericAppException(['Your search value validation has failed.', 500]);
  }

  public static function handleCompareValue(
    Builder $builderInstance,
    $compareKey,
    $compareValue
  ) {
    if (preg_match(self::GreaterThanRegex, $compareKey)) {
      $attributeName = preg_replace(self::GreaterThanRegex, "$1", $compareKey);
      return self::handleGreaterValue($builderInstance, $attributeName, $compareValue);
    }

    if (preg_match(self::LesserThanRegex, $compareKey)) {
      $attributeName = preg_replace(self::LesserThanRegex, "$1", $compareKey);
      return self::handleLesserValue($builderInstance, $attributeName, $compareValue);
    }

    throw new GenericAppException(['Your compare value validation has failed.', 500]);
  }

  public static function handleCompareValues(
    Builder $builderInstance,
    array $compareValues
  ) {
    foreach ($compareValues as $compareKey => $compareValue) {
      $builderInstance = self::handleCompareValue($builderInstance, $compareKey, $compareValue);
    }

    return $builderInstance;
  }

  public static function handleSearchValues(
    Builder $builderInstance,
    array $searchValues
  ) {
    foreach ($searchValues as $compareKey => $searchValue) {
      $builderInstance = self::handleSearchValue($builderInstance, $compareKey, $searchValue);
    }

    return $builderInstance;
  }
}
