<?php namespace Orchestra\View;

use Illuminate\Support\ServiceProvider;
use Orchestra\View\Console\DetectCommand;
use Orchestra\View\Console\ActivateCommand;
use Orchestra\View\Console\OptimizeCommand;

class CommandServiceProvider extends ServiceProvider
{
    /**
     * Indicates if loading of the provider is deferred.
     *
     * @var bool
     */
    protected $defer = true;

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton('orchestra.view.command.activate', function ($app) {
            $finder = $app['orchestra.theme.finder'];

            return new ActivateCommand($finder);
        });

        $this->app->singleton('orchestra.view.command.detect', function ($app) {
            $finder = $app['orchestra.theme.finder'];

            return new DetectCommand($finder);
        });

        $this->app->singleton('orchestra.view.command.optimize', function ($app) {
            return new OptimizeCommand();
        });

        $this->commands([
            'orchestra.view.command.activate',
            'orchestra.view.command.detect',
            'orchestra.view.command.optimize',
        ]);
    }

    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides()
    {
        return [
            'orchestra.view.command.activate',
            'orchestra.view.command.detect',
            'orchestra.view.command.optimize',
        ];
    }
}
