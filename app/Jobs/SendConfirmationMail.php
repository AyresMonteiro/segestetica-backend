<?php

namespace App\Jobs;

use App\Exceptions\GenericAppException;
use App\Http\Handlers\Mail\EmailData;
use App\Http\Handlers\MailHandler;
use App\Http\Helpers\GenericHelper;
use App\Models\{Establishment, User};
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class SendConfirmationMail implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * 
     */
    protected $mailModel;


    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(User|Establishment $model)
    {
        $this->mailModel = $model;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle(MailHandler $mailHandler)
    {
        if ($this->mailModel instanceof Establishment) {
            $data = self::generateDataFromEstablishment($this->mailModel);
        } else {
            throw new GenericAppException([__('messages.system_error')], 500);
        }

        $mailHandler->sendView(
            $data['emailType'],
            [
                'url' => $data['url'],
            ],
            __('messages.mail_confirmation_title'),
            [
                $data['emailData'],
            ],
        );
    }

    public static function generateDataFromEstablishment(Establishment $establishment)
    {
        $token = $establishment->createToken(
            'confirm-token',
            ['establishment:confirm-mail'],
        )->plainTextToken;

        $url = "http://" . env('APP_URL') . "/api/establishments/confirm?token=" . urlencode($token);

        $emailData = new EmailData($establishment->name, $establishment->email);

        $emailType = 'confirm_establishment_mail';

        return [
            'token' => $token,
            'url' => $url,
            'emailData' => $emailData,
            'emailType' => $emailType,
        ];
    }
}
