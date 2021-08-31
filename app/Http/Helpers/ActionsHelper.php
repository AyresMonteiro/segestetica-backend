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
}
