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
		$this->app['view.finder'] = $this->app->share(function($app)
		{
			$paths = $app['config']['view.paths'];

			return new FileViewFinder($app['files'], $paths);
		});
	}
}
