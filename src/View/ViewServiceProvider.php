<?php namespace Orchestra\View;

use Orchestra\View\Theme\Finder;
use Orchestra\View\Theme\ThemeManager;
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
}
