<?php namespace Orchestra\View\Theme;

use RuntimeException;
use Illuminate\Filesystem\Filesystem;

class Manifest
{
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
     * @param  \Illuminate\Filesystem\Filesystem    $files
     * @param  string                               $path
     * @throws \RuntimeException
     */
    public function __construct(Filesystem $files, $path)
    {
        $path        = rtrim($path, '/');
        $this->files = $files;

        if ($files->exists($manifest = "{$path}/theme.json")) {
            $this->items = json_decode($files->get($manifest));

            if (is_null($this->items)) {
                // json_decode couldn't parse, throw an exception.
                throw new RuntimeException(
                    "Theme [{$path}]: cannot decode theme.json file"
                );
            }

            $this->items->uid  = $this->parseThemeNameFromPath($path);
            $this->items->path = $path;
        }
    }

    /**
     * Get theme name from path.
     *
     * @param  string   $path
     * @return string
     */
    protected function parseThemeNameFromPath($path)
    {
        $path = str_replace('\\', DIRECTORY_SEPARATOR, $path);
        $path = explode(DIRECTORY_SEPARATOR, $path);

        return array_pop($path);
    }

    /**
     * Magic method to get items by key.
     *
     * @param  string   $key
     * @return mixed
     */
    public function __get($key)
    {
        if (! isset($this->items->{$key})) {
            return null;
        }

        return $this->items->{$key};
    }

    /**
     * Magic Method to check isset by key.
     *
     * @param  string   $key
     * @return boolean
     */
    public function __isset($key)
    {
        return isset($this->items->{$key});
    }
}
