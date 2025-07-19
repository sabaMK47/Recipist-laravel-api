<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\URL;
use App\Models\Sanctum\PersonalAccessToken;
use Laravel\Sanctum\Sanctum;
use App\Services\ElasticsearchService;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
         $this->app->singleton(ElasticsearchService::class, function () {
        return new ElasticsearchService();
    });
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {

            Sanctum::usePersonalAccessTokenModel(PersonalAccessToken::class);





    }
}
