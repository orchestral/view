<?php namespace Orchestra\View;

use Illuminate\Support\ServiceProvider;
use Orchestra\View\Console\StatusCommand;

class CommandServiceProvider extends ServiceProvider
{
    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bindShared('orchestra.view.command.status', function ($app) {
            return new StatusCommand($app['orchestra.memory']);
        });

        $this->commands(array(
            'orchestra.view.command.status',
        ));
    }
}
