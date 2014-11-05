<?php namespace Orchestra\View\Theme;

use Illuminate\Support\Collection;
use Illuminate\Contracts\Container\Container as ContainerContract;

class Finder
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
    public function __construct(ContainerContract $app)
    {
        $this->app = $app;
    }

    /**
     * Detect available themes.
     *
     * @return \Illuminate\Support\Collection
     * @throws \RuntimeException
     */
    public function detect()
    {
        $themes = new Collection();
        $file   = $this->app['files'];
        $path   = rtrim($this->app['path.public'], '/').'/themes/';

        $folders = $file->directories($path);

        foreach ($folders as $folder) {
            $name = $this->parseThemeNameFromPath($folder);
            $themes[$name] = new Manifest($file, rtrim($folder, '/').'/');
        }

        return $themes;
    }

    /**
     * Get folder name from full path.
     *
     * @param  string   $path
     * @return string
     */
    protected function parseThemeNameFromPath($path)
    {
        $path = str_replace(['\\', '/'], DIRECTORY_SEPARATOR, $path);
        $path = explode(DIRECTORY_SEPARATOR, $path);

        return array_pop($path);
    }
}
