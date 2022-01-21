<?php

namespace App\Http\Handlers;

class LogHandler
{
  public static function getDataAsJSONString(mixed $data): String
  {
    return json_encode($data, JSON_PRETTY_PRINT);
  }

  public static function jsonError($data)
  {
    error_log(self::getDataAsJSONString($data));
  }
}
