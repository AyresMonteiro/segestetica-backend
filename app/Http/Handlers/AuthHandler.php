<?php

namespace App\Http\Handlers;

use App\Exceptions\GenericAppException;
use App\Http\Handlers\Auth\IAuthSystem;
use App\Models\Data\BearerData;
use Illuminate\Http\Request;

class AuthHandler
{
  private IAuthSystem $authSystem;

  private $cacheSecurityData;

  public function __construct(IAuthSystem $authSystem)
  {
    $this->authSystem = $authSystem;
  }

  public function eraseCache()
  {
    $this->cacheSecurityData = null;
    $this->authSystem->eraseCache();
  }

  public function getSecurityData(array $data): array
  {
    return $this->handleSecurityDataCache($data);
  }

  private function handleSecurityDataCache(array $data)
  {
    if (!isset($this->cacheSecurityData)) {
      $this->cacheSecurityData = $this->authSystem->getSecurityData($data);
    }

    return $this->cacheSecurityData;
  }

  public function checkPermission(array $data, $permission)
  {
    $securityData = $this->handleSecurityDataCache($data);

    $can = $this->authSystem->can($securityData, $permission);

    if (!$can) {
      throw new GenericAppException([__('messages.can_not')], 403);
    }
  }

  /**
   * Get model data by token.
   */
  public function getModelFromToken(array $data, $model)
  {
    $securityData = $this->handleSecurityDataCache($data);

    return $this->authSystem->getModelFromToken($securityData, $model);
  }

  public function removeAccess(array $data)
  {
    $this->authSystem->removeAccess($data);
  }

  public function getBearerData(Request $req): BearerData
  {
    $authorization = $req->headers->get('Authorization');

    if (!isset($authorization) || gettype($authorization) != 'string') {
      throw new GenericAppException([__('messages.auth.no_authorization')], 401);
    }

    return new BearerData($authorization);
  }
}
