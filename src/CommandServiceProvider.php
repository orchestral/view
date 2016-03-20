<?php

namespace Orchestra\View;

use Orchestra\View\Console\DetectCommand;
use Orchestra\View\Console\ActivateCommand;
use Orchestra\View\Console\OptimizeCommand;
use Illuminate\Contracts\Foundation\Application;
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
        $this->app->singleton('orchestra.view.command.activate', function (Application $app) {
            return new ActivateCommand($app->make('orchestra.theme.finder'));
        });
    }

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function registerDetectCommand()
    {
        $this->app->singleton('orchestra.view.command.detect', function (Application $app) {
            return new DetectCommand($app->make('orchestra.theme.finder'));
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
