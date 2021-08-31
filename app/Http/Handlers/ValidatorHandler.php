<?php

namespace App\Http\Handlers;

use Illuminate\Contracts\Validation\Validator;

class ValidatorHandler
{
  public static function validate(Validator $validator)
  {
    $arrayOfMessages = [];

    $failed = $validator->fails();

    if (!$failed) return [];

    $messages = $validator->getMessageBag()->getMessages();

    foreach ($messages as $messageArray) {
      foreach ($messageArray as $message) {
        if (!in_array($message, $arrayOfMessages, true)) {
          array_push($arrayOfMessages, $message);
        }
      }
    }

    return $arrayOfMessages;
  }
}
