<?php namespace Orchestra\View;

class FileViewFinder extends \Illuminate\View\FileViewFinder {

	/**
	 * Get the path to a template with a named path.
	 *
	 * @param  string  $name
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
	 * Find the given view in the list of paths.
	 *
	 * @param  string  $name
	 * @param  array   $paths
	 * @return string
	 */
	protected function findInPaths($name, $paths)
	{
		if (starts_with('path: ', $name)) return substr($name, 6);

		return parent::findInPaths($name, $paths);
	}

	/**
	 * Set the active view paths.
	 *
	 * @return array
	 */
	public function setPaths($paths)
	{
		$this->paths = $paths;
	}
}
