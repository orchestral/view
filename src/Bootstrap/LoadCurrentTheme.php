<?php

namespace Orchestra\View\Bootstrap;

use Orchestra\Contracts\Theme\Theme;
use Illuminate\Contracts\Foundation\Application;

class LoadCurrentTheme
{
    /**
     * Bootstrap the given application.
     *
     * @param  \Illuminate\Contracts\Foundation\Application  $app
     *
     * @return void
     */
    public function bootstrap(Application $app): void
    {
        $theme = $app->make('orchestra.theme')->driver();

        $this->setCurrentTheme($app, $theme);
        $this->setThemeResolver($app, $theme);
    }

    /**
     * Set current theme for request.
     *
     * @param  \Illuminate\Contracts\Foundation\Application  $app
     * @param  \Orchestra\Contracts\Theme\Theme  $theme
     *
     * @return void
     */
    protected function setCurrentTheme(Application $app, Theme $theme): void
    {
        $memory = $app->make('orchestra.memory')->makeOrFallback();
        $events = $app->make('events');

        // By default, we should consider all request to use "frontend"
        // theme and only convert to use "backend" routing when certain
        // event is fired.
        $theme->setTheme($memory->get('site.theme.frontend'));

        $events->listen('orchestra.started: admin', static function () use ($theme, $memory) {
            $theme->setTheme($memory->get('site.theme.backend'));
        });

        $events->listen('composing: *', static function () use ($theme) {
            $theme->boot();
        });
    }

    /**
     * Boot theme resolver.
     *
     * @param  \Illuminate\Contracts\Foundation\Application  $app
     * @param  \Orchestra\Contracts\Theme\Theme  $theme
     *
     * @return void
     */
    protected function setThemeResolver(Application $app, Theme $theme): void
    {
        // The theme is only booted when the first view is being composed.
        // This would prevent multiple theme being booted in the same
        // request.
        if ($app->resolved('view')) {
            $theme->resolving();
        } else {
            $app->resolving('view', static function () use ($theme) {
                $theme->resolving();
            });
        }
    }
}
