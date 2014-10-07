<?php namespace Orchestra\View;

use Orchestra\View\Theme\Finder;
use Orchestra\View\Theme\ThemeManager;
use Illuminate\Support\ServiceProvider;
use Orchestra\View\Theme\Finder;
use Orchestra\View\Theme\ThemeManager;

class ViewServiceProvider extends ServiceProvider
{
    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        $this->registerViewFinder();
        $this->registerTheme();
    }

    /**
     * Register the service provider for view finder.
     *
     * @return void
     */
    protected function registerViewFinder()
    {
        $this->app->bindShared('view.finder', function ($app) {
            $paths = $app['config']['view.paths'];

            return new FileViewFinder($app['files'], $paths);
        });
    }

    /**
     * Register the service provider for theme.
     *
     * @return void
     */
    protected function registerTheme()
    {
        $this->app->bindShared('orchestra.theme', function ($app) {
            return new ThemeManager($app);
        });

        $this->app->bindShared('orchestra.theme.finder', function ($app) {
            return new Finder($app);
        });
    }

    /**
     * Bootstrap the application events.
     *
     * @return void
     */
    public function boot()
    {
        $this->bootCurrentTheme();
        $this->bootThemeResolver();
    }

    /**
     * Boot current theme selection.
     *
     * @return void
     */
    protected function bootCurrentTheme()
    {
        $app    = $this->app;
        $memory = $app['orchestra.memory']->makeOrFallback();

        // By default, we should consider all request to use "frontend"
        // theme and only convert to use "backend" routing when certain
        // event is fired.
        $app['orchestra.theme']->setTheme($memory->get('site.theme.frontend'));

        $app['events']->listen('orchestra.started: admin', function () use ($app, $memory) {
            $app['orchestra.theme']->setTheme($memory->get('site.theme.backend'));
        });
    }

    /**
     * Boot theme resolver.
     *
     * @return void
     */
    protected function bootThemeResolver()
    {
        $app = $this->app;

        // The theme is only booted when the first view is being composed.
        // This would prevent multiple theme being booted in the same
        // request.
        $app->resolving('view', function () use ($app) {
            $app['orchestra.theme']->resolving();
        });

        $app['events']->listen('composing: *', function () use ($app) {
            $app['orchestra.theme']->boot();
        });
    }
}
