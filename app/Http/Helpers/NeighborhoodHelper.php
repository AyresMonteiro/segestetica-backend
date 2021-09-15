<?php

namespace App\Http\Helpers;

use App\Exceptions\GenericAppException;
use App\Http\Helpers\GenericHelper;
use App\Models\Neighborhood;
use Illuminate\Http\Request;

class NeighborhoodHelper
{
  public static function getStoreRequestData(Request $req)
  {
    return array_filter([
      'name' => $req->neighborhoodName,
      'cityId' => $req->neighborhoodCityId,
    ]);
  }

  public static function getUpdateRequestData(Request $req)
  {
    return array_filter([
      'name' => $req->neighborhoodName,
      'cityId' => $req->neighborhoodCityId,
    ]);
  }

  public static function getIndexRequestData(Request $req)
  {
    return array_filter([
      'name_search' => $req->neighborhoodNameSearch,
      'cityId' => $req->neighborhoodCityId,
      'created_at_greater_than' => $req->neighborhoodCreatedAtGreaterThan,
      'created_at_lesser_than' => $req->neighborhoodCreatedAtLesserThan,
      'updated_at_greater_than' => $req->neighborhoodUpdatedAtGreaterThan,
      'updated_at_lesser_than' => $req->neighborhoodUpdatedAtLesserThan,
    ]);
  }

  public static function handleStoreRequest(array $data)
  {
    GenericHelper::validate(Neighborhood::getStoreRequestValidator($data));

    $neighborhood = self::saveNeighborhood($data);

    return $neighborhood;
  }

  public static function handleUpdateRequest(array $findData, array $updateData)
  {
    GenericHelper::validate(Neighborhood::getUpdateRequestValidator($updateData));

    $neighborhood = self::updateNeighborhood($findData, $updateData);

    return $neighborhood;
  }

  public static function handleDeleteRequest(array $findData)
  {
    self::deleteNeighborhood($findData);
  }

  public static function getTreatedQuery(array $data)
  {
    GenericHelper::validate(Neighborhood::getQueryValidator($data));

    $fixedValues = GenericHelper::getFixedValues($data);
    $searchValues = GenericHelper::getSearchValues($data);
    $compareValues = GenericHelper::getCompareValues($data);

    $builder = Neighborhood::where($fixedValues);
    $builder = GenericHelper::handleSearchValues($builder, $searchValues);
    $builder = GenericHelper::handleCompareValues($builder, $compareValues);

    return $builder;
  }

  public static function getNeighborhood(array $data, bool $enableNotFoundError = true)
  {
    $builder = self::getTreatedQuery($data);

    $neighborhood = $builder->first();

    if ($enableNotFoundError && !isset($neighborhood)) {
      throw new GenericAppException([__('messages.not_found_error')], 404);
    }

    return $neighborhood;
  }

  public static function getNeighborhoods(array $data, bool $enableNotFoundError = false)
  {
    $builder = self::getTreatedQuery($data);

    $neighborhoods = $builder->get();

    if ($enableNotFoundError && sizeof($neighborhoods) === 0) {
      throw new GenericAppException([__('messages.not_found_error')], 404);
    }

    return $neighborhoods;
  }

  public static function deleteNeighborhood(array $data)
  {
    $neighborhood = self::getNeighborhood($data);

    if (!$neighborhood->delete()) {
      throw new GenericAppException([__('messages.delete_error')], 500);
    };
  }

  protected static function saveNeighborhood(array $data)
  {
    GenericHelper::validate(Neighborhood::getStoreValidator($data));

    $neighborhood = new Neighborhood($data);
    $neighborhood->save();

    return $neighborhood;
  }

  protected static function updateNeighborhood(array $findData, array $updateData)
  {
    GenericHelper::validate(Neighborhood::getUpdateValidator($updateData));

    $neighborhood = self::getNeighborhood($findData);

    $neighborhood->fill($updateData);

    if (sizeof($neighborhood->getDirty()) === 0) {
      throw new GenericAppException([__('messages.up_to_date_error')], 400);
    }

    $neighborhood->save();

    return $neighborhood;
  }
}
