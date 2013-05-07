<?php namespace Orchestra\View;

use Illuminate\Support\ServiceProvider;

class ViewServiceProvider extends ServiceProvider {

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
		$this->app['view.finder'] = $this->app->share(function($app)
		{
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
		$this->app['orchestra.theme'] = $this->app->share(function($app)
		{
			return new Theme\Environment;
		});
	}
}
