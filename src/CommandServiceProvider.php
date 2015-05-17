<?php namespace Orchestra\View;

use Orchestra\View\Console\DetectCommand;
use Orchestra\View\Console\ActivateCommand;
use Orchestra\View\Console\OptimizeCommand;
use Orchestra\Support\Providers\CommandServiceProvider as ServiceProvider;

class CommandServiceProvider extends ServiceProvider
{
    /**
     * The commands to be registered.
     *
     * @var array
     */
    protected $commands = [
        'Activate' => 'orchestra.view.command.activate',
        'Detect'   => 'orchestra.view.command.detect',
        'Optimize' => 'orchestra.view.command.optimize',
    ];

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function registerActivateCommand()
    {
        $this->app->singleton('orchestra.view.command.activate', function ($app) {
            $finder = $app['orchestra.theme.finder'];

            return new ActivateCommand($finder);
        });
    }

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function registerDetectCommand()
    {
        $this->app->singleton('orchestra.view.command.detect', function ($app) {
            $finder = $app['orchestra.theme.finder'];

            return new DetectCommand($finder);
        });
    }

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function registerOptimizeCommand()
    {
        $this->app->singleton('orchestra.view.command.optimize', function () {
            return new OptimizeCommand();
        });
    }
}
