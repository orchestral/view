<?php namespace Orchestra\View;

class FileViewFinder extends \Illuminate\View\FileViewFinder {

	/**
	 * File view finder cache paths.
	 *
	 * @var array
	 */
	protected $caches = array();

	/**
	 * Get the fully qualified location of the view.
	 *
	 * @access public
	 * @param  string   $name
	 * @return string
	 */
	public function find($name)
	{
		if ( ! isset($this->caches[$name])) 
		{
			$this->caches[$name] = parent::find($name);
		}

		return $this->caches[$name];
	}

	/**
	 * Get the path to a template with a named path.
	 *
	 * @access protected
	 * @param  string   $name
	 * @return string
	 */
	protected function findNamedPathView($name)
	{
		list($namespace, $view) = $this->getNamespaceSegments($name);

		$generatePath = function ($path) use ($namespace) {
			return "{$path}/packages/{$namespace}";
		};

		$paths = array_map($generatePath, $this->paths);

		return $this->findInPaths($view, array_merge($paths, $this->hints[$namespace]));
	}

	/**
	 * Set the active view paths.
	 *
	 * @access public
	 * @return array
	 */
	public function setPaths($paths)
	{
		$this->paths = $paths;
	}
}
