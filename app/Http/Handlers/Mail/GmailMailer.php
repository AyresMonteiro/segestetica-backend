<?php

namespace App\Http\Handlers\Mail;

use League\OAuth2\Client\Provider\Google;
use PHPMailer\PHPMailer\OAuth;
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;

class GmailMailer implements IMailer
{
  private $mail;

  public function __construct()
  {
    $this->mail = new PHPMailer();

    // Setting: Use SMTP
    $this->mail->isSMTP();

    // Setting: Log errors
    //    -> DEBUG_OFF: disable
    //    -> DEBUG_CLIENT: client errors only
    //    -> DEBUG_SERVER: both client and server errors
    $this->mail->SMTPDebug = SMTP::DEBUG_OFF;

    $this->mail->Host = env('MAIL_HOST');

    $this->mail->Port = env('MAIL_PORT');

    // Setting: Security protocol
    //  -> If using SMTPS: ssl
    //  -> If using STARTTLS: tls
    $this->mail->SMTPSecure = env('MAIL_ENCRYPTION');

    // Setting: Use authentication
    $this->mail->SMTPAuth = true;

    // Setting: Set Authentication Type
    $this->mail->AuthType = 'XOAUTH2';

    $clientId = env('GOOGLE_OAUTH_CLIENT_ID');
    $clientSecret = env('GOOGLE_OAUTH_CLIENT_SECRET');
    $refreshToken = env('GOOGLE_OAUTH_REFRESH_TOKEN');
    $email = env('MAIL_FROM_ADDRESS');
    $name = env('MAIL_FROM_NAME');

    $provider = new Google([
      'clientId' => $clientId,
      'clientSecret' => $clientSecret
    ]);

    $this->mail->setOAuth(new OAuth([
      'provider' => $provider,
      'clientId' => $clientId,
      'clientSecret' => $clientSecret,
      'refreshToken' => $refreshToken,
      'userName' => $email,
    ]));

    $this->mail->setFrom($email, $name);
  }

  public function getMailer()
  {
    return $this->mail;
  }
}
