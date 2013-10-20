<?php namespace Orchestra\View\Theme;

use Illuminate\Container\Container as Application;

class Container
{
    /**
     * Application instance.
     *
     * @var \Illuminate\Container\Container
     */
    protected $app = null;

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
     * Filesystem path of theme.
     *
     * @var string
     */
    protected $path = null;

    /**
     * URL path of theme.
     *
     * @var string
     */
    protected $absoluteUrl = null;

    /**
     * Relative URL path of theme.
     *
     * @var string
     */
    protected $relativeUrl = null;

    /**
     * Start theme engine, this should be called from application booted
     * or whenever we need to overwrite current active theme per request.
     *
     * @param  \Illuminate\Container\Container  $app
     * @param  string                           $name
     */
    public function __construct(Application $app)
    {
        $this->app  = $app;
        $baseUrl    = $app['request']->root();
        $this->path = $app['path.public'].'/themes';

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
        $paths      = $viewFinder->getPaths();

        if (! is_null($this->theme)) {
            if ($paths[0] === $this->getThemePath()) {
                array_shift($paths);
            }

            $this->app['events']->fire("orchestra.theme.unset: {$this->theme}");
        }

        $this->theme = $theme;
        $this->app['events']->fire("orchestra.theme.set: {$this->theme}");

        $paths = array_merge(array($this->getThemePath()), $paths);
        $viewFinder->setPaths($paths);
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
     * Boot the theme by autoloading all the relevant files.
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
        $manifest  = null;

        // There might be situation where Orchestra Platform was unable
        // to get theme information, we should only assume there a valid
        // theme when manifest is actually an instance of
        // Orchestra\View\Theme\Manifest.
        if (! $this->app['files']->isDirectory($themePath)) {
            return false;
        }

        $manifest = new Manifest($this->app['files'], $themePath);

        // Loop and include all file which was mark as autoloaded.
        if (isset($manifest->autoload) and is_array($manifest->autoload)) {
            foreach ($manifest->autoload as $file) {
                $file = ltrim($file, '/');
                $this->app['files']->requireOnce("{$themePath}/{$file}");
            }
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
}
