<?php namespace Orchestra\View\Theme;

use Illuminate\Container\Container as Application;

class Finder
{
    /**
     * Application instance.
     *
     * @var \Illuminate\Container\Container
     */
    protected $app = null;

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
     * @return array
     * @throws \RuntimeException
     */
    public function detect()
    {
        $themes = array();
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
        $path = str_replace(array('\\', '/'), DIRECTORY_SEPARATOR, $path);
        $path = explode(DIRECTORY_SEPARATOR, $path);

        return array_pop($path);
    }
}
