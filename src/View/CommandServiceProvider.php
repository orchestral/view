<?php namespace Orchestra\View;

use Illuminate\Support\ServiceProvider;
use Orchestra\View\Console\DetectCommand;
use Orchestra\View\Console\ActivateCommand;

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
        $this->app->bindShared('orchestra.view.command.detect', function ($app) {
            $finder = $app['orchestra.theme.finder'];

            return new DetectCommand($finder);
        });

        $this->app->bindShared('orchestra.view.command.activate', function ($app) {
            $finder = $app['orchestra.theme.finder'];

            return new ActivateCommand($finder);
        });

        $this->commands([
            'orchestra.view.command.detect',
            'orchestra.view.command.activate',
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
            'orchestra.view.command.detect',
            'orchestra.view.command.activate',
        ];
    }
}
