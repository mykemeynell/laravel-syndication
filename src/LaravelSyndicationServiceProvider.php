<?php

namespace LaravelSyndication;

use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;
use LaravelSyndication\Console\Commands\MakeFeedCommand;

class LaravelSyndicationServiceProvider extends ServiceProvider
{
    public function boot()
    {
        $this->loadRoutesFrom(
            realpath(__DIR__ . '/../routes/syndication.php')
        );

        $this->loadViewsFrom(
            realpath(__DIR__ . '/../views/'), 'syndication'
        );

        $this->publishes([
            __DIR__ . '/../config/syndication.php' => config_path('syndication.php')
        ], 'laravel-syndication');

        $this->commands([
            MakeFeedCommand::class
        ]);
    }

    public function register()
    {
        $this->app->bind('syndicate', function () {
            return new LaravelSyndication;
        });
    }
}
