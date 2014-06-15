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
            $memory = $app['orchestra.memory']->driver();
            $finder = $app['orchestra.theme.finder'];

            return new DetectCommand($memory, $finder);
        });

        $this->commands(array(
            'orchestra.view.command.detect',
        ));
    }
}
