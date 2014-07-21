<?php namespace Orchestra\View\Theme;

use RuntimeException;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Support\Fluent;

/**
 * @property null|array $type
 * @property array $autoload
 */
class Manifest
{
    /**
     * Application instance.
     *
     * @var \Illuminate\Filesystem\Filesystem
     */
    protected $files;

    /**
     * Theme configuration.
     *
     * @var Object
     */
    protected $items;

    /**
     * Default manifest options.
     *
     * @var array
     */
    protected $manifestOptions = array(
        'name'        => null,
        'uid'         => null,
        'description' => null,
        'author'      => null,
        'autoload'    => array(),
        'type'        => array(),
    );

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
            $jsonable = json_decode($files->get($manifest), true);

            if (is_null($jsonable)) {
                // json_decode couldn't parse, throw an exception.
                throw new RuntimeException(
                    "Theme [{$path}]: cannot decode theme.json file"
                );
            }

            $this->items       = new Fluent($this->generateManifestConfig($jsonable));
            $this->items->uid  = $this->parseThemeNameFromPath($path);
            $this->items->path = $path;
        }
    }

    /**
     * Generate a proper manifest configuration for the theme. This
     * would allow other part of the application to use this configuration
     * to migrate, load service provider as well as preload some
     * configuration.
     *
     * @param  array    $jsonable
     * @return array
     */
    protected function generateManifestConfig(array $jsonable)
    {
        $manifest = array();

        // Assign extension manifest option or provide the default value.
        foreach ($this->manifestOptions as $key => $default) {
            $manifest["{$key}"] = array_get($jsonable, $key, $default);
        }

        return $manifest;
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
