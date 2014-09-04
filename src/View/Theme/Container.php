<?php namespace Orchestra\View\Theme;

use Illuminate\Container\Container as Application;
use Orchestra\View\FileViewFinder;

class Container
{
    /**
     * Application instance.
     *
     * @var \Illuminate\Container\Container
     */
    protected $app;

    /**
     * Theme filesystem path.
     *
     * @var string
     */
    protected $path;

    /**
     * Theme cascading filesystem path.
     * @var string
     */
    protected $cascadingPath;

    /**
     * URL path of theme.
     *
     * @var string
     */
    protected $absoluteUrl;

    /**
     * Relative URL path of theme.
     *
     * @var string
     */
    protected $relativeUrl;

    /**
     * Booted indicator.
     *
     * @var boolean
     */
    protected $booted = false;

    /**
     * Theme name.
     *
     * @var string
     */
    protected $theme = null;

    /**
     * Start theme engine, this should be called from application booted
     * or whenever we need to overwrite current active theme per request.
     *
     * @param  \Illuminate\Container\Container  $app
     */
    public function __construct(Application $app)
    {
        $this->app = $app;
        $baseUrl   = $app['request']->root();

        $this->path = $app['path.public'].'/themes';
        $this->cascadingPath = $app['path.base'].'/resources/themes';

        // Register relative and absolute URL for theme usage.
        $this->absoluteUrl = rtrim($baseUrl, '/').'/themes';
        $this->relativeUrl = trim(str_replace($baseUrl, '/', $this->absoluteUrl), '/');
    }

    /**
     * Set the theme, this would also load the theme manifest.
     *
     * @param  string   $theme
     * @return void
     */
    public function setTheme($theme)
    {
        $viewFinder = $this->app['view.finder'];

        if (! is_null($this->theme)) {
            $paths = $this->getDefaultViewPaths($viewFinder);

            $this->app['events']->fire("orchestra.theme.unset: {$this->theme}");
        } else {
            $paths = $viewFinder->getPaths();
        }

        $this->theme = $theme;
        $this->app['events']->fire("orchestra.theme.set: {$this->theme}");

        $this->registerThemePaths($viewFinder, $paths);
    }

    /**
     * Get the theme.
     *
     * @return string
     */
    public function getTheme()
    {
        return $this->theme;
    }

    /**
     * Boot the theme by auto-loading all the relevant files.
     *
     * @return boolean
     */
    public function boot()
    {
        if ($this->booted) {
            return false;
        }

        $this->booted = true;

        $themePath = $this->getThemePath();

        // There might be situation where Orchestra Platform was unable
        // to get theme information, we should only assume there a valid
        // theme when manifest is actually an instance of
        // Orchestra\View\Theme\Manifest.
        if (! $this->app['files']->isDirectory($themePath)) {
            return false;
        }

        $autoload = $this->getThemeAutoloadFiles($themePath);

        foreach ($autoload as $file) {
            $file = ltrim($file, '/');
            $this->app['files']->requireOnce("{$themePath}/{$file}");
        }

        $this->app['events']->fire("orchestra.theme.boot: {$this->theme}");

        return true;
    }

    /**
     * Get theme path.
     *
     * @return string
     */
    public function getThemePath()
    {
        return "{$this->path}/{$this->theme}";
    }

    /**
     * Get cascading theme path.
     *
     * @return string
     */
    public function getCascadingThemePath()
    {
        return "{$this->cascadingPath}/{$this->theme}";
    }

    /**
     * Get theme paths.
     *
     * @return array
     */
    public function getThemePaths()
    {
        return array($this->getCascadingThemePath(), $this->getThemePath());
    }

    /**
     * URL helper for the theme.
     *
     * @param  string   $url
     * @return string
     */
    public function to($url = '')
    {
        return "{$this->absoluteUrl}/{$this->theme}/{$url}";
    }

    /**
     * Relative URL helper for theme.
     *
     * @param  string   $url
     * @return string
     */
    public function asset($url = '')
    {
        return "/{$this->relativeUrl}/{$this->theme}/{$url}";
    }

    /**
     * Get theme autoload files from manifest.
     *
     * @param  string $themePath
     * @return array
     */
    protected function getThemeAutoloadFiles($themePath)
    {
        $autoload = array();
        $manifest = new Manifest($this->app['files'], $themePath);

        if (isset($manifest->autoload) && is_array($manifest->autoload)) {
            $autoload = $manifest->autoload;
        }

        return $autoload;
    }

    /**
     * Get default view paths (excluding registered theme paths).
     *
     * @param  \Orchestra\View\FileViewFinder   $viewFinder
     * @return array
     */
    protected function getDefaultViewPaths(FileViewFinder $viewFinder)
    {
        $paths = $viewFinder->getPaths();

        foreach ($this->getThemePaths() as $themePath) {
            ($paths[0] === $themePath) && array_shift($paths);
        }

        return $paths;
    }

    /**
     * Register theme paths to view file finder paths.
     *
     * @param  \Orchestra\View\FileViewFinder   $viewFinder
     * @param  array                            $paths
     */
    protected function registerThemePaths(FileViewFinder $viewFinder, array $paths = array())
    {
        $paths = array_merge($this->getThemePaths(), $paths);

        $viewFinder->setPaths($paths);
    }
}
