<?php

namespace App\Providers;

use App\Models\Food;
use App\Repositories\Interface\NutritionalRepository;
use App\Repositories\Out\NutirtionalRepositorySql;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind(NutritionalRepository::class, NutirtionalRepositorySql::class);

    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
