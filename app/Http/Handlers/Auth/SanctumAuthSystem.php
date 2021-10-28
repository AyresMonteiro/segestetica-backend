<?php

namespace App\Http\Handlers\Auth;

use App\Exceptions\GenericAppException;
use Laravel\Sanctum\PersonalAccessToken;

class SanctumAuthSystem implements IAuthSystem
{
  private PersonalAccessToken|null $pat;

  private function isTokenPresent(array $data)
  {
    if (!isset($data['token'])) {
      throw new GenericAppException([__("sanctum.token_not_sent")], 400);
    }
  }

  private function getDataFromDB(string $token)
  {
    $this->pat = PersonalAccessToken::findToken($token);

    if (!isset($this->pat)) {
      throw new GenericAppException([__('sanctum.invalid_token')], 403);
    }

    return $this->pat->toArray();
  }

  public function getSecurityData(array $data)
  {
    $this->isTokenPresent($data);

    return $this->getDataFromDB($data['token']);
  }

  public function getModelFromToken(array $securityData, $model)
  {
    $uuid = $securityData['tokenable_id'];

    return $model::where('uuid', '=', $uuid)->first();
  }

  public function can(array $securityData, $permission)
  {
    if (!is_string($permission)) return false;

    if (!is_array($securityData['abilities'])) return false;

    if (!in_array($permission, $securityData['abilities'])) return false;

    return true;
  }

  public function removeAccess(array $data)
  {
    if (!isset($this->pat)) {
      $this->getSecurityData($data);
    }

    $this->pat->delete();
  }

  public function eraseCache()
  {
    $this->pat = null;
  }
}
