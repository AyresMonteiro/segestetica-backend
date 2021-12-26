<?php

namespace App\Models\Data;

class EmailData
{
  public function __construct(
    public string $name,
    public string $emailAddress
  ) {
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
