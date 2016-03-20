<?php

namespace Orchestra\View\Theme;

use Illuminate\Support\Manager;

class ThemeManager extends Manager
{
    /**
     * Create an instance of the orchestra theme driver.
     *
     * @return \Orchestra\Contracts\Theme\Theme
     */
    protected function createOrchestraDriver()
    {
        $theme = new Theme(
            $this->app,
            $this->app->make('events'),
            $this->app->make('files')
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
     * @return array
     */
    public function detect()
    {
        return $this->app->make('orchestra.theme.finder')->detect();
    }
}
