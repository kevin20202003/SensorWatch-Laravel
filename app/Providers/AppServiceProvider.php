<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\URL; // <-- 1. Importa el Facade URL

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        // 2. Fuerza esquema HTTPS en producción/Render
        if ($this->app->environment('production') || config('app.env') === 'production') {
            URL::forceScheme('https');
        }
    }
}