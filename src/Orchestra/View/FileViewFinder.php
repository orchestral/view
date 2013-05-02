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
}
