<?php namespace Orchestra\View\Console;

use Illuminate\Support\Fluent;
use Orchestra\View\Theme\Finder;

class DetectCommand extends BaseCommand
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
    public function fire()
    {
        $memory   = $this->laravel['orchestra.memory'];

        $themes   = $this->finder->detect();
        $frontend = $memory->get('site.theme.frontend');
        $backend  = $memory->get('site.theme.backend');

        $header  = array('ID', 'Theme Name', 'Frontend', 'Backend');
        $content = array();

        foreach ($themes as $id => $theme) {
            $content[] = array(
                $id,
                $theme->name,
                $this->getThemeStatus('frontend', $theme, ($id == $frontend)),
                $this->getThemeStatus('backend', $theme, ($id == $backend)),
            );
        }

        $this->table($header, $content);
    }

    /**
     * Get theme status.
     *
     * @param  string                       $type
     * @param  \Illuminate\Support\Fluent   $theme
     * @param  bool                         $active
     * @return string
     */
    protected function getThemeStatus($type, Fluent $theme, $active = false)
    {
        if ($active === true) {
            return "   ✓";
        }

        if (! empty($theme->type) && ! in_array($type, $theme->type)) {
            return "   ✗";
        }

        return "";
    }
}
