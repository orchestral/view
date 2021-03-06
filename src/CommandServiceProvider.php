<?php

namespace Orchestra\View;

use Illuminate\Contracts\Container\Container;
use Orchestra\Support\Providers\CommandServiceProvider as ServiceProvider;
use Orchestra\View\Console\ActivateCommand;
use Orchestra\View\Console\DetectCommand;
use Orchestra\View\Console\OptimizeCommand;

class CommandServiceProvider extends ServiceProvider
{
    /**
     * The commands to be registered.
     *
     * @var array
     */
    protected $commands = [
        'Activate' => 'orchestra.view.command.activate',
        'Detect' => 'orchestra.view.command.detect',
        'Optimize' => 'orchestra.view.command.optimize',
    ];

    /**
     * Register the service provider.
     */
    public function registerActivateCommand(): void
    {
        $this->app->singleton('orchestra.view.command.activate', static function (Container $app) {
            return new ActivateCommand($app->make('orchestra.theme.finder'));
        });
    }

    /**
     * Register the service provider.
     */
    public function registerDetectCommand(): void
    {
        $this->app->singleton('orchestra.view.command.detect', static function (Container $app) {
            return new DetectCommand($app->make('orchestra.theme.finder'));
        });
    }

    /**
     * Register the service provider.
     */
    public function registerOptimizeCommand(): void
    {
        $this->app->singleton('orchestra.view.command.optimize', static function () {
            return new OptimizeCommand();
        });
    }
}
