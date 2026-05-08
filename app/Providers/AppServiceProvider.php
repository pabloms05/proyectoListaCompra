<?php

namespace App\Providers;
use App\Models\Lista;
use App\Policies\ListaPolicy;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\URL;

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
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Forzar HTTPS en producción
        if ($this->app->environment('production')) {
            URL::forceScheme('https');
        }
        
        // Registrar policy
        Gate::policy(Lista::class, ListaPolicy::class);
    }
}
