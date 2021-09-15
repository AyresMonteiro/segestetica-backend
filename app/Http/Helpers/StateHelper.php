<?php

namespace App\Http\Helpers;

use App\Exceptions\GenericAppException;
use App\Http\Helpers\GenericHelper;
use App\Models\State;
use Illuminate\Http\Request;

class StateHelper
{
  public static function getStoreRequestData(Request $req)
  {
    return array_filter([
      'name' => $req->stateName,
      'abbreviation' => $req->stateAbbreviation,
    ]);
  }

  public static function getUpdateRequestData(Request $req)
  {
    return array_filter([
      'name' => $req->stateName,
      'abbreviation' => $req->stateAbbreviation,
    ]);
  }

  public static function getIndexRequestData(Request $req)
  {
    return array_filter([
      'name_search' => $req->stateNameSearch,
      'abbreviation_search' => $req->stateAbbreviationSearch,
      'created_at_greater_than' => $req->stateCreatedAtGreaterThan,
      'created_at_lesser_than' => $req->stateCreatedAtLesserThan,
      'updated_at_greater_than' => $req->stateUpdatedAtGreaterThan,
      'updated_at_lesser_than' => $req->stateUpdatedAtLesserThan,
    ]);
  }

  public static function handleStoreRequest(array $data)
  {
    GenericHelper::validate(State::getStoreRequestValidator($data));

    $state = self::saveState($data);

    return $state;
  }

  public static function handleUpdateRequest(array $findData, array $updateData)
  {
    GenericHelper::validate(State::getUpdateRequestValidator($updateData));

    $state = self::updateState($findData, $updateData);

    return $state;
  }

  public static function handleDeleteRequest(array $findData)
  {
    self::deleteState($findData);
  }

  public static function getTreatedQuery(array $data)
  {
    GenericHelper::validate(State::getQueryValidator($data));

    $fixedValues = GenericHelper::getFixedValues($data);
    $searchValues = GenericHelper::getSearchValues($data);
    $compareValues = GenericHelper::getCompareValues($data);

    $builder = State::where($fixedValues);
    $builder = GenericHelper::handleSearchValues($builder, $searchValues);
    $builder = GenericHelper::handleCompareValues($builder, $compareValues);

    return $builder;
  }

  public static function getState(array $data, bool $enableNotFoundError = true)
  {
    $builder = self::getTreatedQuery($data);

    $state = $builder->first();

    if ($enableNotFoundError && !isset($state)) {
      throw new GenericAppException([__('messages.not_found_error')], 404);
    }

    return $state;
  }

  public static function getStates(array $data, bool $enableNotFoundError = false)
  {
    $builder = self::getTreatedQuery($data);

    $states = $builder->get();

    if ($enableNotFoundError && sizeof($states) === 0) {
      throw new GenericAppException([__('messages.not_found_error')], 404);
    }

    return $states;
  }

  public static function deleteState(array $data)
  {
    $state = self::getState($data);

    if (!$state->delete()) {
      throw new GenericAppException([__('messages.delete_error')], 500);
    };
  }

  protected static function saveState(array $data)
  {
    GenericHelper::validate(State::getStoreValidator($data));

    $state = new State($data);
    $state->save();

    return $state;
  }

  protected static function updateState(array $findData, array $updateData)
  {
    $state = self::getState($findData);

    GenericHelper::validate(State::getUpdateValidator($updateData));

    $state->fill($updateData);

    if (sizeof($state->getDirty()) === 0) {
      throw new GenericAppException([__('messages.up_to_date_error')], 400);
    }

    $state->save();

    return $state;
  }
}
