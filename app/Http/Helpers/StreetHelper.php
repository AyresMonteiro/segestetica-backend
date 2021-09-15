<?php

namespace App\Http\Helpers;

use App\Exceptions\GenericAppException;
use App\Http\Helpers\GenericHelper;
use App\Models\Street;
use Illuminate\Http\Request;

class StreetHelper
{
  public static function getStoreRequestData(Request $req)
  {
    return array_filter([
      'name' => $req->streetName,
      'postCode' => $req->streetPostCode,
      'neighborhoodId' => $req->streetNeighborhoodId,
    ]);
  }

  public static function getUpdateRequestData(Request $req)
  {
    return array_filter([
      'name' => $req->streetName,
      'postCode' => $req->streetPostCode,
      'neighborhoodId' => $req->streetNeighborhoodId,
    ]);
  }

  public static function getIndexRequestData(Request $req)
  {
    return array_filter([
      'name_search' => $req->streetNameSearch,
      'postCode_search' => $req->streetPostCodeSearch,
      'created_at_greater_than' => $req->streetCreatedAtGreaterThan,
      'created_at_lesser_than' => $req->streetCreatedAtLesserThan,
      'updated_at_greater_than' => $req->streetUpdatedAtGreaterThan,
      'updated_at_lesser_than' => $req->streetUpdatedAtLesserThan,
    ]);
  }

  public static function handleStoreRequest(array $data)
  {
    GenericHelper::validate(Street::getStoreRequestValidator($data));

    $street = self::saveStreet($data);

    return $street;
  }

  public static function handleUpdateRequest(array $findData, array $updateData)
  {
    GenericHelper::validate(Street::getUpdateRequestValidator($updateData));

    $street = self::updateStreet($findData, $updateData);

    return $street;
  }

  public static function handleDeleteRequest(array $findData)
  {
    self::deleteStreet($findData);
  }

  public static function getTreatedQuery(array $data)
  {
    GenericHelper::validate(Street::getQueryValidator($data));

    $fixedValues = GenericHelper::getFixedValues($data);
    $searchValues = GenericHelper::getSearchValues($data);
    $compareValues = GenericHelper::getCompareValues($data);

    $builder = Street::where($fixedValues);
    $builder = GenericHelper::handleSearchValues($builder, $searchValues);
    $builder = GenericHelper::handleCompareValues($builder, $compareValues);

    return $builder;
  }

  public static function getStreet(array $data, bool $enableNotFoundError = true)
  {
    $builder = self::getTreatedQuery($data);

    $street = $builder->first();

    if ($enableNotFoundError && !isset($street)) {
      throw new GenericAppException([__('messages.not_found_error')], 404);
    }

    return $street;
  }

  public static function getStreets(array $data, bool $enableNotFoundError = false)
  {
    $builder = self::getTreatedQuery($data);

    $streets = $builder->get();

    if ($enableNotFoundError && sizeof($streets) === 0) {
      throw new GenericAppException([__('messages.not_found_error')], 404);
    }

    return $streets;
  }

  public static function deleteStreet(array $data)
  {
    $street = self::getStreet($data);

    if (!$street->delete()) {
      throw new GenericAppException([__('messages.delete_error')], 500);
    };
  }

  protected static function saveStreet(array $data)
  {
    GenericHelper::validate(Street::getStoreValidator($data));

    $street = new Street($data);
    $street->save();

    return $street;
  }

  protected static function updateStreet(array $findData, array $updateData)
  {
    $street = self::getStreet($findData);

    GenericHelper::validate(Street::getUpdateValidator($updateData));

    $street->fill($updateData);

    if (sizeof($street->getDirty()) === 0) {
      throw new GenericAppException([__('messages.up_to_date_error')], 400);
    }

    $street->save();

    return $street;
  }
}
