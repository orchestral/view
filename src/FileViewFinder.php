<?php

namespace Orchestra\View;

use Illuminate\Support\Collection;
use Illuminate\View\FileViewFinder as LaravelViewFinder;

class FileViewFinder extends LaravelViewFinder
{
    const HINT_PATH_DELIMITER = '::';

    /**
     * {@inheritdoc}
     */
    protected function findNamespacedView($name)
    {
        list($namespace, $view) = $this->parseNamespaceSegments($name);

        // Prepend global view paths to namespace hints path. This would
        // allow theme to take priority if such view exist.

        return $this->findInPaths($view, Collection::make($this->paths)->map(function ($path) use ($namespace) {
            return "{$path}/packages/{$namespace}";
        })->merge($this->hints[$namespace])->all());
    }

    /**
     * Set the active view paths.
     *
     * @param  array  $paths
     *
     * @return array
     */
    public function setPaths(array $paths)
    {
        $this->paths = $paths;
    }
}
