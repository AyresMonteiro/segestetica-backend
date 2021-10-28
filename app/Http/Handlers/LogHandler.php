<?php

namespace App\Http\Handlers;

class LogHandler
{
  public static function jsonError($data)
  {
    error_log(json_encode($data, JSON_PRETTY_PRINT));
  }
}
