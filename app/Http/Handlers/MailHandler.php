<?php

namespace App\Http\Handlers;

use App\Exceptions\GenericAppException;
use App\Models\Data\EmailData;
use Illuminate\Mail\Markdown;
use PHPMailer\PHPMailer\PHPMailer;

class MailHandler
{
  private PHPMailer $mail;

  public function __construct(PHPMailer $mailer)
  {
    $this->mail = $mailer;
  }

  public function handleMailData(EmailData $data)
  {
    $this->mail->addAddress($data->getEmailAddress(), $data->getName());
  }

  public function send($subject, $body, array $to)
  {
    if (!isset($this->mail)) {
      throw new GenericAppException([__('messages.mail_not_set')]);
    }

    foreach ($to as $data) {
      $this->handleMailData($data);
    }

    $this->mail->Subject = $subject;

    $this->mail->CharSet = PHPMailer::CHARSET_UTF8;

    $this->mail->Body = $body;

    if (!$this->mail->send()) {
      throw new GenericAppException([__('messages.mail_error', [
        'error' => $this->mail->ErrorInfo
      ])]);
    }
  }

  public function sendHTML($subject, $body, array $to)
  {
    $this->mail->isHTML();

    $this->send($subject, $body, $to);
  }

  public function sendView($viewName, array $data, $subject, array $to)
  {
    $markdown = new Markdown(view(), config('mail.markdown'));

    $renderedView = $markdown->render($viewName, $data);

    $this->sendHTML($subject, $renderedView, $to);
  }
}
