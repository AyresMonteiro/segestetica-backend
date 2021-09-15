<?php

namespace App\Http\Helpers;

use App\Exceptions\GenericAppException;
use App\Http\Helpers\GenericHelper;
use App\Models\City;
use Illuminate\Http\Request;

class CityHelper
{
  public static function getStoreRequestData(Request $req)
  {
    return array_filter([
      'name' => $req->cityName,
      'stateId' => $req->cityStateId,
    ]);
  }

  public static function getUpdateRequestData(Request $req)
  {
    return array_filter([
      'name' => $req->cityName,
      'stateId' => $req->cityStateId,
    ]);
  }

  public static function getIndexRequestData(Request $req)
  {
    return array_filter([
      'name_search' => $req->cityNameSearch,
      'stateId' => $req->cityStateId,
      'created_at_greater_than' => $req->cityCreatedAtGreaterThan,
      'created_at_lesser_than' => $req->cityCreatedAtLesserThan,
      'updated_at_greater_than' => $req->cityUpdatedAtGreaterThan,
      'updated_at_lesser_than' => $req->cityUpdatedAtLesserThan,
    ]);
  }

  public static function handleStoreRequest(array $data)
  {
    GenericHelper::validate(City::getStoreRequestValidator($data));

    $city = self::saveCity($data);

    return $city;
  }

  public static function handleUpdateRequest(array $findData, array $updateData)
  {
    GenericHelper::validate(City::getUpdateRequestValidator($updateData));

    $city = self::updateCity($findData, $updateData);

    return $city;
  }

  public static function handleDeleteRequest(array $findData)
  {
    self::deleteCity($findData);
  }

  public static function getTreatedQuery(array $data)
  {
    GenericHelper::validate(City::getQueryValidator($data));

    $fixedValues = GenericHelper::getFixedValues($data);
    $searchValues = GenericHelper::getSearchValues($data);
    $compareValues = GenericHelper::getCompareValues($data);

    $builder = City::where($fixedValues);
    $builder = GenericHelper::handleSearchValues($builder, $searchValues);
    $builder = GenericHelper::handleCompareValues($builder, $compareValues);

    return $builder;
  }

  public static function getCity(array $data, bool $enableNotFoundError = true)
  {
    $builder = self::getTreatedQuery($data);

    $city = $builder->first();

    if ($enableNotFoundError && !isset($city)) {
      throw new GenericAppException([__('messages.not_found_error')], 404);
    }

    return $city;
  }

  public static function getCities(array $data, bool $enableNotFoundError = false)
  {
    $builder = self::getTreatedQuery($data);

    $cities = $builder->get();

    if ($enableNotFoundError && sizeof($cities) === 0) {
      throw new GenericAppException([__('messages.not_found_error')], 404);
    }

    return $cities;
  }

  public static function deleteCity(array $data)
  {
    $city = self::getCity($data);

    if (!$city->delete()) {
      throw new GenericAppException([__('messages.delete_error')], 500);
    };
  }

  protected static function saveCity(array $data)
  {
    GenericHelper::validate(City::getStoreValidator($data));

    $city = new City($data);
    $city->save();

    return $city;
  }

  protected static function updateCity(array $findData, array $updateData)
  {
    GenericHelper::validate(City::getUpdateValidator($updateData));

    $city = self::getCity($findData);

    $city->fill($updateData);

    if (sizeof($city->getDirty()) === 0) {
      throw new GenericAppException([__('messages.up_to_date_error')], 400);
    }

    $city->save();

    return $city;
  }
}
