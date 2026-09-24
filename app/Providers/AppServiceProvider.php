<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Route;
use App\Models\Tecnico\OrdenTrabajo;
use App\Models\Tecnico\ConsumoMaterial;

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
        // Route Model Bindings
        Route::model('ordene', OrdenTrabajo::class);
        Route::model('consumo', ConsumoMaterial::class);
    }
}
