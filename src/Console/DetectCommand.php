<?php

namespace Orchestra\View\Console;

use Orchestra\View\Theme\Finder;
use Orchestra\View\Theme\Manifest;

class DetectCommand extends Command
{
    /**
     * Theme finder instance.
     *
     * @var \Orchestra\View\Theme\Finder
     */
    protected $finder;

    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'theme:detect';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Detect available themes in the application.';

    /**
     * Construct a new status command.
     *
     * @param  \Orchestra\View\Theme\Finder $finder
     */
    public function __construct(Finder $finder)
    {
        $this->finder = $finder;

        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return void
     */
    public function handle()
    {
        $memory = $this->laravel['orchestra.memory'];

        $themes = $this->finder->detect();
        $frontend = $memory->get('site.theme.frontend');
        $backend = $memory->get('site.theme.backend');

        $header = ['ID', 'Theme Name', 'Frontend', 'Backend'];

        $this->table($header, $themes->map(function ($theme, $id) use ($backend, $frontend) {
            return [
                $id,
                $theme->get('name'),
                $this->getThemeStatus('frontend', $theme, ($id == $frontend)),
                $this->getThemeStatus('backend', $theme, ($id == $backend)),
            ];
        })->all());
    }

    /**
     * Get theme status.
     *
     * @param  string  $type
     * @param  \Orchestra\View\Theme\Manifest  $theme
     * @param  bool  $active
     *
     * @return string
     */
    protected function getThemeStatus(string $type, Manifest $theme, bool $active = false): string
    {
        if ($active === true) {
            return '   ✓';
        }

        $group = $theme->get('type');

        if (! empty($group) && ! in_array($type, $group)) {
            return '   ✗';
        }

        return '';
    }
}
