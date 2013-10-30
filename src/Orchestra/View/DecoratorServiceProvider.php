<?php namespace Orchestra\View;

use Illuminate\Support\ServiceProvider;
use Illuminate\Foundation\AliasLoader;

class DecoratorServiceProvider extends ServiceProvider
{
    /**
     * Indicates if loading of the provider is deferred.
     *
     * @var boolean
     */
    protected $defer = true;

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bindShared('orchestra.decorator', function () {
            return new Decorator;
        });

        $this->app->booting(function () {
            $loader = AliasLoader::getInstance();
            $loader->alias('Orchestra\Decorator', 'Orchestra\Support\Facades\Decorator');
        });
    }

    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides()
    {
        return array('orchestra.decorator');
    }
}
