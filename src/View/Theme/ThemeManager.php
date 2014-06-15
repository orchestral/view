<?php namespace Orchestra\View\Theme;

use Illuminate\Support\Manager;

class ThemeManager extends Manager
{
    /**
     * Create an instance of the orchestra theme driver.
     *
     * @return Container
     */
    protected function createOrchestraDriver()
    {
        return new Container($this->app);
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
