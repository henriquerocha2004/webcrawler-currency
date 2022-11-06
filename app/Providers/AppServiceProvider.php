<?php

namespace App\Providers;

use App\Repository\WikipediaRepository;
use App\Services\CurrencyInformationService;
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
        $this->app->singleton(CurrencyInformationService::class, function ($app) {
            return new CurrencyInformationService(
                $app->make(WikipediaRepository::class)
            );
        });
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}
