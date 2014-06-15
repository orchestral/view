<?php namespace Orchestra\View\Console;

use Orchestra\Support\Str;
use Symfony\Component\Console\Input\InputArgument;

class ThemeCommand extends BaseCommand
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
    protected $name = 'theme:activate';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Set active themes in the application.';

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
        $group = Str::lower($this->argument('group'));
        $name  = Str::lower($this->argument('name'));

        $themes = $this->finder->detect();

        if (! in_array($group, array('frontend', 'backend'))) {
            throw new \InvalidArgumentException("Invalid theme name [{$group}], should either be 'frontend' or 'backend'.");
        }

        if (! isset($themes[$name])) {
            throw new \InvalidArgumentException("Invalid theme name [{$name}].");
        }

        $this->laravel['orchestra.memory']->set("site.theme.{$group}", $name);
        $this->info("Theme [{$name}] activated on {$group}.");
    }

    /**
     * Get the console command arguments.
     *
     * @return array
     */
    protected function getArguments()
    {
        return array(
            array('group', InputArgument::REQUIRED, 'Either frontend or backend.'),
            array('name', InputArgument::REQUIRED, 'Theme name.'),
        );
    }
}
