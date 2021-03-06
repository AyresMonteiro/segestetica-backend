<?php

namespace App\Http\Handlers\Mail;

use PHPMailer\PHPMailer\PHPMailer;

interface IMailer
{
  /**
   * Get mailer instance
   * 
   * @return PHPMailer
   */
  public static function getMailer(): PHPMailer;
}
