<?php

namespace b3nl\LSetup\Providers;

use b3nl\LSetup\Jobs\ChangeFile;
use b3nl\LSetup\Console\Commands\EnvConfiguration;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Boots the service provider.
     * @return void
     */
    public function boot()
    {
        $this->commands(['command.laravel-setup.configure,env']);
    } // function

    /**
     * Register the service provider.
     * @return void
     */
    public function register()
    {
        $this->app->singleton('command.laravel-setup.configure,env', EnvConfiguration::class);

        $this->app->bind('laravel-setup.job.change-env-file', function ($app) {
            return new ChangeFile();
        });
    } // function

    /**
     * Get the services provided by the provider.
     * @return array
     */
    public function provides()
    {
        return ['command.laravel-setup.configure.env'];
    } // function
}
