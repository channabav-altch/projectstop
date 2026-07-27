<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\URL; // <-- នាំចូល URL facade

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        // បង្ខំឱ្យប្រើ HTTPS នៅពេលរันលើ Production (Railway)
        if (config('app.env') === 'production') {
            URL::forceScheme('https');
        }
    }
}
