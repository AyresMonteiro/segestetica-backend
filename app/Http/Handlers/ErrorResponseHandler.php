<?php

namespace App\Http\Handlers;

class ErrorResponseHandler
{
  public static function defaultError()
  {
    return response(['errors' => [__('messages.system_error')]], 500);
  }

  public static function customError($errors = [], $statusCode = 500)
  {
    return response()->json(['errors' => $errors], $statusCode);
  }
}
