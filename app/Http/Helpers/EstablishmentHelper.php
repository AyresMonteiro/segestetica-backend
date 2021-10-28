<?php

namespace App\Http\Helpers;

use App\Exceptions\GenericAppException;
use App\Http\Handlers\FileHandler;
use App\Http\Handlers\Mail\EmailData;
use App\Http\Helpers\GenericHelper;
use App\Models\Establishment;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class EstablishmentHelper
{
  public static function getStoreRequestData(Request $req)
  {
    return array_filter([
      'name' => $req->establishmentName,
      'email' => $req->establishmentEmail,
      'photo' => $req->establishmentPhoto,
      'streetId' => $req->establishmentStreetId,
      'addressNumber' => $req->establishmentAddressNumber,
      'password' => $req->establishmentPassword,
    ]);
  }

  public static function getUpdateRequestData(Request $req)
  {
    return array_filter([
      'name' => $req->establishmentName,
      'email' => $req->establishmentEmail,
      'photo' => $req->establishmentPhoto,
      'streetId' => $req->establishmentStreetId,
      'addressNumber' => $req->establishmentAddressNumber,
      'password' => $req->establishmentPassword,
    ]);
  }

  public static function getIndexRequestData(Request $req)
  {
    $data = array_filter([
      'name_search' => $req->establishmentNameSearch,
      'streetId' => $req->establishmentStreetId,
      'created_at_greater_than' => $req->establishmentCreatedAtGreaterThan,
      'created_at_lesser_than' => $req->establishmentCreatedAtLesserThan,
      'updated_at_greater_than' => $req->establishmentUpdatedAtGreaterThan,
      'updated_at_lesser_than' => $req->establishmentUpdatedAtLesserThan,
    ]);

    $data['emailConfirmation_different_than'] = null;

    return $data;
  }

  public static function getMailConfirmationRequestData(Request $req)
  {
    return array_filter([
      'token' => $req->token,
    ]);
  }

  public static function getLoginRequestData(Request $req)
  {
    return array_filter([
      'email' => $req->email,
      'password' => $req->password,
    ]);
  }

  public static function handleStoreRequest(array $data)
  {
    GenericHelper::validate(Establishment::getStoreRequestValidator($data));

    $data['passwordHash'] = Hash::make($data['password']);
    unset($data['password']);

    if (isset($data['photo'])) {
      $data['photoUrl'] = GenericHelper::handleUploadImage($data['photo']);
      unset($data['photo']);
    }

    $data['uuid'] = GenericHelper::generateUUIDString();

    $establishment = self::saveEstablishment($data);

    return $establishment;
  }

  public static function handleUpdateRequest(array $findData, array $updateData)
  {
    GenericHelper::validate(Establishment::getUpdateRequestValidator($updateData));

    $establishment = self::updateEstablishment($findData, $updateData);

    return $establishment;
  }

  public static function handleDeleteRequest(array $findData)
  {
    self::deleteEstablishment($findData);
  }

  public static function handleLoginRequest(array $data)
  {
    GenericHelper::validate(Establishment::getLoginRequestValidator($data));

    return self::login($data['email'], $data['password']);
  }

  public static function handleMailConfirmation(array $data)
  {
    GenericHelper::validate(Establishment::getMailConfirmationValidator($data));

    $authHandler = GenericHelper::getDefaultAuthHandler();

    $authHandler->eraseCache();

    $authHandler->checkPermission(
      $data,
      'establishment:confirm-mail'
    );

    $establishment = $authHandler->getModelFromToken(
      $data,
      Establishment::class
    );

    if ($establishment->emailConfirmation != null) {
      $authHandler->removeAccess($data);
      throw new GenericAppException([__('messages.system_error')], 400);
    }

    $establishment->emailConfirmation = Carbon::now();

    $establishment->save();

    $authHandler->removeAccess($data);
  }

  public static function getTreatedQuery(array $data)
  {
    GenericHelper::validate(Establishment::getQueryValidator($data));

    $fixedValues = GenericHelper::getFixedValues($data);
    $searchValues = GenericHelper::getSearchValues($data);
    $compareValues = GenericHelper::getCompareValues($data);
    $differentValues = GenericHelper::getDifferentValues($data);

    $builder = Establishment::where($fixedValues);
    $builder = GenericHelper::handleSearchValues($builder, $searchValues);
    $builder = GenericHelper::handleCompareValues($builder, $compareValues);
    $builder = GenericHelper::handleDifferentValues($builder, $differentValues);

    return $builder;
  }

  public static function getEstablishment(array $data, bool $enableNotFoundError = true)
  {
    $builder = self::getTreatedQuery($data);

    $establishment = $builder->first();

    if ($enableNotFoundError && !isset($establishment)) {
      throw new GenericAppException([__('messages.establishment_not_found')], 404);
    }

    return $establishment;
  }

  public static function getEstablishments(array $data, bool $enableNotFoundError = false)
  {
    $builder = self::getTreatedQuery($data);

    $establishments = $builder->get();

    if ($enableNotFoundError && sizeof($establishments) === 0) {
      throw new GenericAppException([__('messages.not_found_error')], 404);
    }

    return $establishments;
  }

  public static function deleteEstablishment(array $data)
  {
    $establishment = self::getEstablishment($data);

    if (isset($establishment->photoUrl)) {
      FileHandler::delete($establishment->photoUrl);
    }

    if (!$establishment->delete()) {
      throw new GenericAppException([__('messages.delete_error')], 500);
    };
  }

  protected static function saveEstablishment(array $data)
  {
    try {
      GenericHelper::validate(Establishment::getStoreValidator($data));

      $establishment = new Establishment($data);
      $establishment->save();

      return $establishment;
    } catch (GenericAppException $e) {
      if (isset($data['photoUrl'])) {
        $e->setActionsToPerform([ActionsHelper::generateDeleteImageAction($data['photoUrl'])]);
      }

      throw $e;
    } catch (Exception $e) {
      $e = new GenericAppException([__("messages.system_error")]);

      if (isset($data['photoUrl'])) {
        $e->setActionsToPerform([ActionsHelper::generateDeleteImageAction($data['photoUrl'])]);
      }

      throw $e;
    }
  }

  protected static function updateEstablishment(array $findData, array $updateData)
  {
    $oldPhotoUrl = null;

    try {
      $establishment = self::getEstablishment($findData);

      GenericHelper::validate(Establishment::getUpdateValidator($updateData));

      if (isset($data['photo'])) {
        $data['photoUrl'] = GenericHelper::handleUploadImage($data['photo']);
        unset($data['photo']);

        $oldPhotoUrl = $establishment->photoUrl;
      }

      $establishment->fill($updateData);

      if (sizeof($establishment->getDirty()) === 0) {
        throw new GenericAppException([__('messages.up_to_date_error')], 400);
      }

      $establishment->save();

      if (isset($oldPhotoUrl)) {
        FileHandler::delete($oldPhotoUrl);
      }

      return $establishment;
    } catch (GenericAppException $e) {
      if (isset($updateData['photoUrl'])) {
        $e->setActionsToPerform([ActionsHelper::generateDeleteImageAction($updateData['photoUrl'])]);
      }

      throw $e;
    } catch (Exception $e) {
      $e = new GenericAppException([__("messages.system_error")]);

      if (isset($data['photoUrl'])) {
        $e->setActionsToPerform([ActionsHelper::generateDeleteImageAction($data['photoUrl'])]);
      }

      throw $e;
    }
  }

  protected static function login(string $email, string $password)
  {
    $establishment = self::getEstablishment([
      'email' => $email,
      'emailConfirmation_different_than' => null,
    ]);

    if (!Hash::check($password, $establishment->passwordHash)) {
      throw new GenericAppException([__('validation.password')], 401);
    }

    return $establishment->createToken(
      'general-login',
      ['establishment:general'],
    )->plainTextToken;
  }
}
