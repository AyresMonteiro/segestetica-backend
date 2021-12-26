<?php

namespace App\Models\Data;

class EmailData
{
  public string $name;
  public string $emailAddress;

  public function __construct($name, $emailAddress)
  {
    $this->name = $name;
    $this->emailAddress = $emailAddress;
  }

  public function getName()
  {
    return $this->name;
  }

  public function getEmailAddress()
  {
    return $this->emailAddress;
  }

  public function getArray()
  {
    return [
      $this->name,
      $this->emailAddress
    ];
  }
}
