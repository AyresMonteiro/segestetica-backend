<?php

namespace App\Http\Helpers;

use App\Exceptions\GenericAppException;
use App\Http\Handlers\{
  AuthHandler,
  FileHandler,
  MailHandler,
  ValidatorHandler
};
use App\Http\Handlers\Auth\SanctumAuthSystem;
use App\Http\Handlers\Mail\GmailMailer;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Validator as FacadesValidator;

class GenericHelper
{
  public const UUIDRegex = "/^.{8}-.{4}-.{4}-.{4}-.{12}$/";
  public const GreaterThanRegex = "/^(.*)_greater_than$/";
  public const LesserThanRegex = "/^(.*)_lesser_than$/";
  public const DifferentThanRegex = "/^(.*)_different_than$/";
  public const SearchRegex = "/^(.*)_search$/";

  public static function getDefaultAuthHandler()
  {
    $defaultAuthSystem = new SanctumAuthSystem();

    return new AuthHandler($defaultAuthSystem);
  }

  public static function getDefaultMailHandler()
  {
    $defaultMailer = GmailMailer::getMailer();

    return new MailHandler($defaultMailer);
  }

  public static function validate(Validator $validator)
  {
    $errors = ValidatorHandler::validate($validator);

    if (sizeof($errors) > 0) {
      throw new GenericAppException($errors, 400);
    }
  }

  public static function generateIdMessages(string $paramName = 'uuid')
  {
    return [
      $paramName . '.required' => __('messages.send_id'),
      $paramName . '.regex' => __('messages.send_valid_id'),
      $paramName . '.integer' => __('messages.send_valid_id'),
    ];
  }

  public static function validateUUID($uuid, array $messages, $paramName = 'uuid')
  {
    if (sizeof($messages) === 0) {
      $messages = self::generateIdMessages('uuid');
    }

    $validator = FacadesValidator::make(
      [$paramName => $uuid],
      [$paramName => ['required', 'regex:' . self::UUIDRegex]],
      $messages
    );

    self::validate($validator);
  }

  public static function validateId($id, array $messages, $paramName = 'id')
  {
    $validator = FacadesValidator::make(
      [$paramName => $id],
      [$paramName => ['required', 'integer']],
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

  public static function isDifferentValue(string $key)
  {
    return preg_match(self::DifferentThanRegex, $key);
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

  public static function getDifferentValues(array $data)
  {
    $differentValues = [];

    foreach ($data as $key => $value) {
      if (self::isDifferentValue($key)) {
        $differentValues[$key] = $value;
      }
    }

    return $differentValues;
  }

  public static function getFixedValues(array $data)
  {
    $fixedValues = [];

    foreach ($data as $key => $value) {
      if (!self::isCompareValue($key) && !self::isSearchValue($key) && !self::isDifferentValue($key)) {
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

    throw new GenericAppException([__('messages.search_value_validation_fail'), 500]);
  }

  public static function handleDifferentValue(
    Builder $builderInstance,
    $differentValueKey,
    $differentValue
  ) {
    if (preg_match(self::DifferentThanRegex, $differentValueKey)) {
      $attributeName = preg_replace(self::DifferentThanRegex, "$1", $differentValueKey);
      return $builderInstance->where($attributeName, "!=", $differentValue);
    }

    throw new GenericAppException([__('messages.different_value_validation_fail'), 500]);
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

    throw new GenericAppException([__('messages.compare_value_validation_fail'), 500]);
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

  public static function handleDifferentValues(
    Builder $builderInstance,
    array $differentValues
  ) {
    foreach ($differentValues as $compareKey => $differentValue) {
      $builderInstance = self::handleDifferentValue($builderInstance, $compareKey, $differentValue);
    }

    return $builderInstance;
  }

  public static function generateUUIDString()
  {
    return Str::uuid()->toString();
  }

  public static function handleUploadImage($image, $dir = 'images')
  {
    return FileHandler::saveAs($image, $dir, self::generateUUIDString() . '.' . $image->extension());
  }

  public static function handleDeleteImage($filepath)
  {
    return FileHandler::delete($filepath);
  }
}
