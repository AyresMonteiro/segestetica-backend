<?php

namespace App\Providers;

use App\Http\Helpers\GenericHelper;
use App\Jobs\SendConfirmationMail;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        if (config('app.env') === 'production') {
            URL::forceScheme('https');
        }

        $this->app->bindMethod([SendConfirmationMail::class, 'handle'], function ($job, $app) {
            $mailHandler = GenericHelper::getDefaultMailHandler();

            return $job->handle($mailHandler);
        });
    }
}
