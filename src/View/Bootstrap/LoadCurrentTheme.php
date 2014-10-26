<?php namespace Orchestra\View\Bootstrap;

use Illuminate\Contracts\Foundation\Application;

class LoadCurrentTheme
{
    /**
     * Bootstrap the given application.
     *
     * @param  \Illuminate\Contracts\Foundation\Application  $app
     * @return void
     */
    public function bootstrap(Application $app)
    {
        $this->setCurrentTheme($app);

        $this->setThemeResolver($app);
    }

    /**
     * Set current theme for request.
     *
     * @param  \Illuminate\Contracts\Foundation\Application  $app
     * @return void
     */
    protected function setCurrentTheme(Application $app)
    {
        $memory = $app['orchestra.memory']->makeOrFallback();

        // By default, we should consider all request to use "frontend"
        // theme and only convert to use "backend" routing when certain
        // event is fired.
        $app['orchestra.theme']->setTheme($memory->get('site.theme.frontend'));

        $app['events']->listen('orchestra.started: admin', function () use ($app, $memory) {
            $app['orchestra.theme']->setTheme($memory->get('site.theme.backend'));
        });

        $app['events']->listen('composing: *', function () use ($app) {
            $app['orchestra.theme']->boot();
        });
    }

    /**
     * Boot theme resolver.
     *
     * @param  \Illuminate\Contracts\Foundation\Application  $app
     * @return void
     */
    protected function setThemeResolver(Application $app)
    {
        // The theme is only booted when the first view is being composed.
        // This would prevent multiple theme being booted in the same
        // request.
        if ($app->resolved('view')) {
            $app['orchestra.theme']->resolving();
        } else {
            $app->resolving('view', function () use ($app) {
                $app['orchestra.theme']->resolving();
            });
        }
    }
}
