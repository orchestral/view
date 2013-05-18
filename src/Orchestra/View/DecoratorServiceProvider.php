<?php namespace Orchestra\View;

use Illuminate\Support\ServiceProvider;
use Illuminate\Foundation\AliasLoader;

class DecoratorServiceProvider extends ServiceProvider {

	/**
	 * Indicates if loading of the provider is deferred.
	 *
	 * @var bool
	 */
	protected $defer = true;
	
	/**
	 * Register the service provider.
	 *
	 * @return void
	 */
	public function register()
	{
		$this->app['orchestra.decorator'] = $this->app->share(function($app)
		{
			return new Decorator;
		});

		$this->app->booting(function()
		{
			$loader = AliasLoader::getInstance();
			$loader->alias('Orchestra\Decorator', 'Orchestra\Support\Facades\Decorator');
		});
	}

	/**
	 * Get the services provided by the provider.
	 *
	 * @return array
	 */
	public function provides()
	{
		return array('orchestra.decorator');
	}
}
