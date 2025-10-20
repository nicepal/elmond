<?php

namespace App\Providers;

use App\Services\LessonService;
use Illuminate\Support\ServiceProvider;
use Illuminate\Contracts\Foundation\Application;

class LessonServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        $this->app->singleton(LessonService::class, function (Application $app) {
            return new LessonService();
        });
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        //
    }
}
