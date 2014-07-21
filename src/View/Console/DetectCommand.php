<?php namespace Orchestra\View\Console;

use Orchestra\View\Theme\Finder;
use Orchestra\View\Theme\Manifest;

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
     * @param  string                           $type
     * @param  \Orchestra\View\Theme\Manifest   $theme
     * @param  bool                             $active
     * @return string
     */
    protected function getThemeStatus($type, Manifest $theme, $active = false)
    {
        if ($active === true) {
            return "   ✓";
        }

        if (! empty($theme->type) && ! in_array($type, $theme->type)) {
            return "   ✗";
        }

        return "";
    }

    /**
     * Table generator.
     *
     * @param  array   $header
     * @param  array   $content
     * @return void
     */
    public function table($header, $content)
    {
        $table = $this->getHelperSet()->get('table');
        $table->setHeaders($header)->setRows($content);
        $table->render($this->getOutput());
    }
}
