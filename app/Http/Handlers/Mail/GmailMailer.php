<?php

namespace App\Http\Handlers\Mail;

// ATTENTION: Sometimes you may need run "php artisan config:clear" to make
// things work properly

use League\OAuth2\Client\Provider\Google;
use PHPMailer\PHPMailer\OAuth;
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;

class GmailMailer implements IMailer
{
  private static PHPMailer $mail;

  private static function setup(): void
  {
    self::$mail = new PHPMailer();

    self::$mail->isSMTP();

    self::$mail->SMTPDebug = SMTP::DEBUG_OFF;

    self::$mail->Host = env('MAIL_HOST');

    self::$mail->Port = env('MAIL_PORT');

    self::$mail->SMTPSecure = env('MAIL_ENCRYPTION');

    self::$mail->SMTPAuth = true;

    self::$mail->AuthType = 'XOAUTH2';

    $clientId = env('GOOGLE_OAUTH_CLIENT_ID');
    $clientSecret = env('GOOGLE_OAUTH_CLIENT_SECRET');
    $refreshToken = env('GOOGLE_OAUTH_REFRESH_TOKEN');
    $email = env('MAIL_FROM_ADDRESS');
    $name = env('MAIL_FROM_NAME');

    $provider = new Google([
      'clientId' => $clientId,
      'clientSecret' => $clientSecret
    ]);

    self::$mail->setOAuth(new OAuth([
      'provider' => $provider,
      'clientId' => $clientId,
      'clientSecret' => $clientSecret,
      'refreshToken' => $refreshToken,
      'userName' => $email,
    ]));

    self::$mail->setFrom($email, $name);
  }

  public static function getMailer(): PHPMailer
  {
    if (!isset(self::$mail)) {
      self::setup();
    }

    return self::$mail;
  }
}
