<?php namespace Orchestra\View\Theme;

use Illuminate\Container\Container as Application;
use Orchestra\Support\Collection;

class Finder
{
    /**
     * Application instance.
     *
     * @var \Illuminate\Container\Container
     */
    protected $app;

    /**
     * Construct a new finder.
     *
     * @param  \Illuminate\Container\Container  $app
     */
    public function __construct(Application $app)
    {
        $this->app = $app;
    }

    /**
     * Detect available themes.
     *
     * @param  null|string  $type
     * @return \Orchestra\Support\Collection
     * @throws \RuntimeException
     */
    public function detect($type = null)
    {
        $themes = new Collection();
        $file   = $this->app['files'];
        $path   = rtrim($this->app['path.public'], '/').'/themes/';

        $folders = $file->directories($path);

        foreach ($folders as $folder) {
            $name = $this->parseThemeNameFromPath($folder);
            $manifest = new Manifest($file, rtrim($folder, '/').'/');

            if (is_null($type) || empty($manifest->type) || in_array($type, $manifest->type)) {
                $themes[$name] = $manifest;
            }

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
        $path = str_replace(array('\\', '/'), DIRECTORY_SEPARATOR, $path);
        $path = explode(DIRECTORY_SEPARATOR, $path);

        return array_pop($path);
    }
}
