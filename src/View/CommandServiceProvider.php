<?php namespace Orchestra\View;

use Illuminate\Support\ServiceProvider;
use Orchestra\View\Console\DetectCommand;
use Orchestra\View\Console\ThemeCommand;

class CommandServiceProvider extends ServiceProvider
{
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

            return new ThemeCommand($finder);
        });

        $this->commands(array(
            'orchestra.view.command.detect',
            'orchestra.view.command.activate',
        ));
    }
}
