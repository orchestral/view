<?php

namespace Orchestra\View\Theme;

use Illuminate\Support\Manager;
use Illuminate\Support\Collection;
use Orchestra\Contracts\Theme\Theme as ThemeContract;

class ThemeManager extends Manager
{
    /**
     * Create an instance of the orchestra theme driver.
     *
     * @return \Orchestra\Contracts\Theme\Theme
     */
    protected function createOrchestraDriver(): ThemeContract
    {
        $theme = new Theme(
            $this->app, $this->app->make('events'), $this->app->make('files')
        );

        return $theme->initiate();
    }

    /**
     * {@inheritdoc}
     */
    public function getDefaultDriver()
    {
        return 'orchestra';
    }

    /**
     * Detect available themes.
     *
     * @return \Illuminat\Support\Collection
     */
    public function detect(): Collection
    {
        return $this->app->make('orchestra.theme.finder')->detect();
    }
}
