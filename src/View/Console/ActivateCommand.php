<?php namespace Orchestra\View\Console;

use Orchestra\Support\Str;
use Orchestra\View\Theme\Finder;
use Symfony\Component\Console\Input\InputArgument;

class ActivateCommand extends BaseCommand
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
        $id    = Str::lower($this->argument('id'));

        $themes = $this->finder->detect();

        if (! in_array($group, array('frontend', 'backend'))) {
            throw new \InvalidArgumentException("Invalid theme name [{$group}], should either be 'frontend' or 'backend'.");
        }

        if (! isset($themes[$id])) {
            throw new \InvalidArgumentException("Invalid Theme ID [{$id}].");
        }

        $this->laravel['orchestra.memory']->set("site.theme.{$group}", $themes[$id]->name);
        $this->info("Theme [{$themes[$id]->name}] activated on group [{$group}].");
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
            array('id', InputArgument::REQUIRED, 'Theme ID.'),
        );
    }
}
