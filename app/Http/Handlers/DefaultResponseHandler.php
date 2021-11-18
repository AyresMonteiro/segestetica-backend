<?php

namespace App\Http\Handlers;

class DefaultResponseHandler
{
  public static function defaultResponse()
  {
    return response('', 204);
  }

  public static function customResponse($data = [], $statusCode = 200)
  {
    return response()->json($data, $statusCode);
  }
}
