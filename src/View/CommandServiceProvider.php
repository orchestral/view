<?php namespace Orchestra\View;

use Illuminate\Support\ServiceProvider;
use Orchestra\View\Console\DetectCommand;

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
            return new DetectCommand($app['orchestra.memory']);
        });

        $this->commands(array(
            'orchestra.view.command.detect',
        ));
    }
}
