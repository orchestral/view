<?php

namespace Orchestra\View\Theme;

use Illuminate\Support\Collection;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Contracts\Events\Dispatcher;
use Illuminate\Contracts\Container\Container;
use Orchestra\Contracts\Theme\Theme as ThemeContract;

class Theme implements ThemeContract
{
    /**
     * Application instance.
     *
     * @var \Illuminate\Contracts\Container\Container
     */
    protected $app;

    /**
     * The event dispatcher implementation.
     *
     * @var \Illuminate\Contracts\Events\Dispatcher
     */
    protected $dispatcher;

    /**
     * The filesystem implementation.
     *
     * @var \Illuminate\Filesystem\Filesystem
     */
    protected $filesystem;

    /**
     * Theme filesystem path.
     *
     * @var string
     */
    protected $path;

    /**
     * Theme cascading filesystem path.
     *
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
     * Boot indicator.
     *
     * @var bool
     */
    protected $booted = false;

    /**
     * Resolve indicator.
     *
     * @var bool
     */
    protected $resolved = false;

    /**
     * Theme name.
     *
     * @var string
     */
    protected $theme = null;

    /**
     * Setup a new theme container.
     *
     * @param  \Illuminate\Contracts\Container\Container  $app
     * @param  \Illuminate\Contracts\Events\Dispatcher  $dispatcher
     * @param  \Illuminate\Filesystem\Filesystem  $filesystem
     */
    public function __construct(Container $app, Dispatcher $dispatcher, Filesystem $filesystem)
    {
        $this->app = $app;
        $this->dispatcher = $dispatcher;
        $this->filesystem = $filesystem;

        $this->path = $app['path.public'].'/themes';
        $this->cascadingPath = $app['path.base'].'/resources/themes';
    }

    /**
     * Start theme engine, this should be called from application booted
     * or whenever we need to overwrite current active theme per request.
     *
     * @return $this
     */
    public function initiate()
    {
        $baseUrl = $this->app->make('request')->root();

        // Register relative and absolute URL for theme usage.
        $this->absoluteUrl = \rtrim($baseUrl, '/').'/themes';
        $this->relativeUrl = \trim(\str_replace($baseUrl, '/', $this->absoluteUrl), '/');

        return $this;
    }

    /**
     * Set the theme, this would also load the theme manifest.
     *
     * @param  string|null  $theme
     *
     * @return void
     */
    public function setTheme(?string $theme): void
    {
        if (! \is_null($this->theme)) {
            $this->resolved && $this->resetViewPaths();

            $this->dispatcher->dispatch("orchestra.theme.unset: {$this->theme}");
        }

        $this->theme = $theme;
        $this->dispatcher->dispatch("orchestra.theme.set: {$this->theme}");

        if ($this->resolved) {
            $this->resolved = false;
            $this->resolving();
        }
    }

    /**
     * Get the theme.
     *
     * @return string
     */
    public function getTheme(): string
    {
        return $this->theme;
    }

    /**
     * Boot and Load theme starter files.
     *
     * @return bool
     */
    public function boot(): bool
    {
        if ($this->booted) {
            return false;
        }

        $this->booted = true;

        $themePath = $this->getThemePath();
        $autoload = $this->getThemeAutoloadFiles($themePath);

        foreach ($autoload as $file) {
            $file = \ltrim($file, '/');
            $this->filesystem->requireOnce("{$themePath}/{$file}");
        }

        $this->dispatcher->dispatch("orchestra.theme.boot: {$this->theme}");

        return true;
    }

    /**
     * Resolving the theme.
     *
     * @return bool
     */
    public function resolving(): bool
    {
        if ($this->resolved) {
            return false;
        }

        $this->resolved = true;

        $this->dispatcher->dispatch('orchestra.theme.resolving', [$this, $this->app]);

        $this->setViewPaths();

        return true;
    }

    /**
     * Get theme path.
     *
     * @return string
     */
    public function getThemePath(): string
    {
        return "{$this->path}/{$this->theme}";
    }

    /**
     * Get cascading theme path.
     *
     * @return string
     */
    public function getCascadingThemePath(): string
    {
        return "{$this->cascadingPath}/{$this->theme}";
    }

    /**
     * Get theme paths.
     *
     * @return array
     */
    public function getThemePaths(): array
    {
        return [
            $this->getCascadingThemePath(),
            $this->getThemePath(),
        ];
    }

    /**
     * Get available theme paths.
     *
     * @return array
     */
    public function getAvailableThemePaths(): array
    {
        $filesystem = $this->filesystem;

        return Collection::make($this->getThemePaths())->filter(static function ($path) use ($filesystem) {
            return $filesystem->isDirectory($path);
        })->values()->all();
    }

    /**
     * URL helper for the theme.
     *
     * @param  string  $url
     *
     * @return string
     */
    public function to(string $url = ''): string
    {
        return "{$this->absoluteUrl}/{$this->theme}/{$url}";
    }

    /**
     * Relative URL helper for theme.
     *
     * @param  string  $url
     *
     * @return string
     */
    public function asset(string $url = ''): string
    {
        return "/{$this->relativeUrl}/{$this->theme}/{$url}";
    }

    /**
     * Get theme autoload files from manifest.
     *
     * @param  string $themePath
     *
     * @return array
     */
    protected function getThemeAutoloadFiles(string $themePath): array
    {
        $manifest = new Manifest($this->filesystem, $themePath);

        return $manifest->autoload ?? [];
    }

    /**
     * Set theme paths to view file finder paths.
     *
     * @return void
     */
    protected function setViewPaths(): void
    {
        $viewFinder = $this->app->make('view.finder');

        $themePaths = $this->getAvailableThemePaths();

        if (! empty($themePaths)) {
            $viewFinder->setPaths(\array_merge($themePaths, $viewFinder->getPaths()));
        }
    }

    /**
     * Reset theme paths to view file finder paths.
     *
     * @return void
     */
    protected function resetViewPaths(): void
    {
        $viewFinder = $this->app->make('view.finder');

        $paths = $viewFinder->getPaths();

        foreach ($this->getThemePaths() as $themePath) {
            ($paths[0] === $themePath) && \array_shift($paths);
        }

        $viewFinder->setPaths($paths);
    }
}
