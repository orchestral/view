<?php namespace Orchestra\View\Theme;

use Illuminate\Support\Manager;

class ThemeManager extends Manager {

	/**
	 * Create an instance of the orchestra theme driver.
	 *
	 * @return \Orchestra\View\Theme\Container
	 */
	protected function createOrchestraDriver()
	{
		return new Container($this->app);
	}

	/**
	 * Get the default authentication driver name.
	 *
	 * @return string
	 */
	protected function getDefaultDriver()
	{
		return 'orchestra';
	}
}
