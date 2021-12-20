<?php

namespace OSSTools\AutoTranslate;

use Illuminate\Support\ServiceProvider;
use OSSTools\AutoTranslate\Commands\AllCommand;
use OSSTools\AutoTranslate\Commands\MissingCommand;
use OSSTools\AutoTranslate\Translators\TranslatorInterface;
use OSSTools\Langman\Manager;

class AutoTranslateServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     */
    public function boot()
    {
        if ($this->app->runningInConsole()) {
            $this->publishes([
                __DIR__.'/../config/config.php' => config_path('auto-translate.php'),
            ], 'config');

            // Registering package commands.
            $this->commands([
                AllCommand::class,
                MissingCommand::class,
            ]);
        }
    }

    /**
     * Register the application services.
     */
    public function register()
    {
        // Automatically apply the package configuration
        $this->mergeConfigFrom(__DIR__.'/../config/config.php', 'auto-translate');

        $this->app->bind(TranslatorInterface::class, config('auto-translate.translator'));

        // Register the main class to use with the facade
        $this->app->singleton('auto-translate', function () {
            config([
                'langman.path' => config('auto-translate.path'),
            ]);

            return new AutoTranslate(app(Manager::class), app(TranslatorInterface::class));
        });
    }
}
