<?php

namespace App\Providers;

use App\Services\LibraryService;
use App\Services\VideoService;
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
        $this->app->bind(LibraryService::class, function($app) {
            return new LibraryService($app->make(VideoService::class));
        });
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        view()->composer(['*'], function($view) {
            require_once base_path('app/helpers.php');
        });
    }
}
