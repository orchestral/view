<?php

namespace Orchestra\View\Theme;

use Illuminate\Filesystem\Filesystem;
use Illuminate\Support\Collection;
use Orchestra\Contracts\Theme\Finder as FinderContract;

class Finder implements FinderContract
{
    /**
     * The filesystem implementation.
     *
     * @var \Illuminate\Filesystem\Filesystem
     */
    protected $files;

    /**
     * Public path.
     *
     * @var string
     */
    protected $publicPath;

    /**
     * Construct a new finder.
     */
    public function __construct(Filesystem $files, string $publicPath)
    {
        $this->files = $files;
        $this->publicPath = $publicPath;
    }

    /**
     * Detect available themes.
     *
     * @throws \RuntimeException
     */
    public function detect(): Collection
    {
        $path = \rtrim($this->publicPath, '/').'/themes/';

        return Collection::make($this->files->directories($path))->mapWithKeys(function ($folder) {
            return [
                $this->parseThemeNameFromPath($folder) => new Manifest($this->files, \rtrim($folder, '/').'/'),
            ];
        });
    }

    /**
     * Get folder name from full path.
     */
    protected function parseThemeNameFromPath(string $path): string
    {
        $path = \str_replace(['\\', '/'], DIRECTORY_SEPARATOR, $path);
        $path = \explode(DIRECTORY_SEPARATOR, $path);

        return \array_pop($path);
    }
}
