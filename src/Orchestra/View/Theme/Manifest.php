<?php namespace Orchestra\View\Theme;

use RuntimeException;

class Manifest {

	/**
	 * Application instance.
	 *
	 * @var Illuminate\Foundation\Application
	 */
	protected $app = null;
	
	/**
	 * Theme configuration.
	 *
	 * @var array
	 */
	protected $items = array();

	/**
	 * Load the theme.
	 *
	 * @access public
	 * @param  string   $path
	 * @return void
	 */
	public function __construct($app, $path)
	{
		$this->app = $app;

		if ($app['files']->exist($manifest = "{$path}/theme.json"))
		{
			$this->items = json_decode($app['files']->get($manifest));

			if (is_null($this->items))
			{
				// json_decode couldn't parse, throw an exception
				throw new RuntimeException(
					"Theme [{$path}]: cannot decode theme.json file"
				);
			}
		}
	}

	/**
	 * Magic method to get items by key.
	 */
	public function __get($key)
	{
		if ( ! isset($this->items->{$key})) return null;

		return $this->items->{$key};
	}

	/**
	 * Magic Method to check isset by key.
	 */
	public function __isset($key)
	{
		return isset($this->items->{$key});
	}
}
