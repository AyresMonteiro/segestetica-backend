<?php

namespace App\Jobs;

use App\Http\Handlers\MailHandler;
use App\Models\Data\EmailViewData;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\App;

class SendConfirmationMail implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(protected EmailViewData $data)
    {
    }

    public $tries = 3;



    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle(MailHandler $mailHandler)
    {
        $data = $this->data;

        App::setLocale($data->locale);

        $emailsToSendData = [
            $data->contactData,
        ];

        $mailHandler->sendView(
            $data->viewName,
            $data->viewData,
            $data->mailTitle,
            $emailsToSendData,
        );
    }
}
