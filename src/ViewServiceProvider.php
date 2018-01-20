<?php

namespace Orchestra\View;

use Orchestra\View\Theme\Finder;
use Orchestra\View\Theme\ThemeManager;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\View\ViewServiceProvider as ServiceProvider;

class ViewServiceProvider extends ServiceProvider
{
    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        $this->registerEngineResolver();

        $this->registerViewFinder();

        $this->registerFactory();

        $this->registerTheme();
    }

    /**
     * Register the service provider for view finder.
     *
     * @return void
     */
    public function registerViewFinder(): void
    {
        $this->app->singleton('view.finder', function (Application $app) {
            $paths = $app->make('config')->get('view.paths', []);

            return new FileViewFinder($app->make('files'), $paths);
        });
    }

    /**
     * Register the service provider for theme.
     *
     * @return void
     */
    protected function registerTheme(): void
    {
        $this->app->singleton('orchestra.theme', function (Application $app) {
            return new ThemeManager($app);
        });

        $this->app->singleton('orchestra.theme.finder', function (Application $app) {
            return new Finder($app);
        });
    }
}
