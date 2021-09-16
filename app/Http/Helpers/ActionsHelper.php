<?php

namespace App\Http\Helpers;

class ActionsHelper
{
  public static function generateDeleteModelAction($model)
  {
    return function () use ($model) {
      $model->delete();
    };
  }

  public static function generateDeleteImageAction($path)
  {
    return function () use ($path) {
      GenericHelper::handleDeleteImage($path);
    };
  }
}
