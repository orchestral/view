<?php namespace Orchestra\View\Theme;

use Illuminate\Support\Manager;

class ThemeManager extends Manager
{
    /**
     * Create an instance of the orchestra theme driver.
     *
     * @return \Orchestra\Contracts\View\Theme\Theme
     */
    protected function createOrchestraDriver()
    {
        $theme = new Theme($this->app, $this->app['events'], $this->app['files']);

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
        return $this->app['orchestra.theme.finder']->detect();
    }
}
