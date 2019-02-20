<?php

namespace Orchestra\View\Theme;

use Illuminate\Support\Collection;
use Illuminate\Contracts\Container\Container;
use Orchestra\Contracts\Theme\Finder as FinderContract;

class Finder implements FinderContract
{
    /**
     * Application instance.
     *
     * @var \Illuminate\Contracts\Container\Container
     */
    protected $app;

    /**
     * Construct a new finder.
     *
     * @param  \Illuminate\Contracts\Container\Container  $app
     */
    public function __construct(Container $app)
    {
        $this->app = $app;
    }

    /**
     * Detect available themes.
     *
     * @throws \RuntimeException
     *
     * @return \Illuminate\Support\Collection
     */
    public function detect(): Collection
    {
        $themes = new Collection();
        $file = $this->app->make('files');
        $path = \rtrim($this->app['path.public'], '/').'/themes/';

        $folders = $file->directories($path);

        foreach ($folders as $folder) {
            $name = $this->parseThemeNameFromPath($folder);
            $themes[$name] = new Manifest($file, \rtrim($folder, '/').'/');
        }

        return $themes;
    }

    /**
     * Get folder name from full path.
     *
     * @param  string   $path
     *
     * @return string
     */
    protected function parseThemeNameFromPath(string $path): string
    {
        $path = \str_replace(['\\', '/'], DIRECTORY_SEPARATOR, $path);
        $path = \explode(DIRECTORY_SEPARATOR, $path);

        return \array_pop($path);
    }
}
