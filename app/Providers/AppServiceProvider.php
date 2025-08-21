<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Validation\Rules\Password;
use Illuminate\Support\Facades\URL;

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
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Force HTTPS for production and SIT environments
        if (config('app.env') === 'production' || config('app.env') === 'sit') {
            URL::forceScheme('https');
        }

        Password::defaults(function () {
            return Password::min(8)
                ->letters()
                ->numbers();
        });
    }
}
