<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Models\Barang;
use App\Observers\BarangObserver;

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
        // Register Barang Observer for image optimization
        Barang::observe(BarangObserver::class);
    }
}
