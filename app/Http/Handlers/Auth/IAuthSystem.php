<?php

namespace App\Http\Handlers\Auth;

interface IAuthSystem
{
  /**
   * Returns security data with credentials.
   * 
   * @return array
   */
  public function getSecurityData(array $data);

  /**
   * Get model from token. 
   */
  public function getModelFromToken(array $securityData, $model);

  /**
   * Check if credentials can do something.
   * 
   * @return bool
   */
  public function can(array $securityData, $permission);

  /**
   * Erases possible cache.
   */
  public function eraseCache();

  /**
   * Remove access.
   */
  public function removeAccess(array $data);
}
