<?php namespace Orchestra\View\Console;

use Orchestra\Memory\Provider as Memory;
use Orchestra\View\Theme\Finder;

class DetectCommand extends BaseCommand
{
    /**
     * Memory instance.
     *
     * @var \Orchestra\Memory\Provider
     */
    protected $memory;

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
     * @param  \Orchestra\Memory\Provider   $memory
     * @param  \Orchestra\View\Theme\Finder $finder
     */
    public function __construct(Memory $memory, Finder $finder)
    {
        $this->memory = $memory;
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
        $themes = $this->finder->detect();
        $frontend = $this->memory->get('site.theme.frontend');
        $backend = $this->memory->get('site.theme.backend');

        $header  = array('Theme Name', 'Frontend', 'Backend');
        $content = array();

        foreach ($themes as $id => $theme) {
            $content[] = array(
                $theme->name,
                ($id == $frontend ? "✓" : ''),
                ($id == $backend ? "✓" : ''),
            );
        }

        $this->table($header, $content);
    }
}
