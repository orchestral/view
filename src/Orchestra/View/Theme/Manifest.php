<?php namespace Orchestra\View\Theme;

use RuntimeException;
use Illuminate\Filesystem\Filesystem;

class Manifest {

	/**
	 * Application instance.
	 *
	 * @var \Illuminate\Filesystem\Filesystem
	 */
	protected $files = null;
	
	/**
	 * Theme configuration.
	 *
	 * @var Object
	 */
	protected $items;

	/**
	 * Load the theme.
	 *
	 * @access public
	 * @param  \Illuminate\Filesystem\Filesystem    $files
	 * @param  string                               $path
	 * @return void
	 * @throws \RuntimeException
	 */
	public function __construct(Filesystem $files, $path)
	{
		$path        = rtrim($path, '/');
		$this->files = $files;

		if ($files->exists($manifest = "{$path}/theme.json"))
		{
			$this->items = json_decode($files->get($manifest));

			if (is_null($this->items))
			{
				// json_decode couldn't parse, throw an exception.
				throw new RuntimeException(
					"Theme [{$path}]: cannot decode theme.json file"
				);
			}

			$this->items->path = $path;
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
