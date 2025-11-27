<?php

namespace App\Providers;

use App\Events\SurveyAnswerSubmitted;
use App\Listeners\SendNewAnswerNotification;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * The event listener mappings for the application.
     * @var array<class-string, array<int, class-string>>
     */
    protected $listen = [
        SurveyAnswerSubmitted::class => [
            SendNewAnswerNotification::class,
        ],
    ];

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
