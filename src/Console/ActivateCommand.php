<?php namespace Orchestra\View\Console;

use Illuminate\Support\Str;
use InvalidArgumentException;
use Orchestra\View\Theme\Finder;
use Orchestra\View\Theme\Manifest;
use Illuminate\Console\ConfirmableTrait;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;

class ActivateCommand extends BaseCommand
{
    use ConfirmableTrait;

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
     * Available themes type.
     *
     * @var array
     */
    protected $type = ['frontend', 'backend'];

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
        if (! $this->confirmToProceed()) {
            return;
        }

        $group = Str::lower($this->argument('group'));
        $id    = Str::lower($this->argument('id'));

        $theme = $this->getAvailableTheme($group)->get($id);

        if ($this->validateProvidedTheme($group, $id, $theme)) {
            $this->laravel['orchestra.memory']->set("site.theme.{$group}", $theme->uid);

            $this->info("Theme [{$theme->name}] activated on group [{$group}].");
        }
    }

    /**
     * Validate provided theme.
     *
     * @param  string  $group
     * @param  string  $id
     * @param  object|null  $theme
     * @return bool
     * @throws \InvalidArgumentException
     */
    protected function validateProvidedTheme($group, $id, $theme)
    {
        if (! in_array($group, $this->type)) {
            throw new InvalidArgumentException("Invalid theme name [{$group}], should either be 'frontend' or 'backend'.");
        }

        if (is_null($theme)) {
            throw new InvalidArgumentException("Invalid Theme ID [{$id}], or is not available for '{$group}'.");
        }

        return true;
    }

    /**
     * Get all available theme by type.
     *
     * @param  string  $type
     * @return \Illuminate\Support\Collection
     */
    protected function getAvailableTheme($type)
    {
        $themes = $this->finder->detect();

        return $themes->filter(function (Manifest $manifest) use ($type) {
            if (! empty($manifest->type) && ! in_array($type, $manifest->type)) {
                return;
            }

            return $manifest;
        });
    }

    /**
     * Get the console command arguments.
     *
     * @return array
     */
    protected function getArguments()
    {
        return [
            ['group', InputArgument::REQUIRED, 'Either frontend or backend.'],
            ['id', InputArgument::REQUIRED, 'Theme ID.'],
        ];
    }

    /**
     * Get the console command options.
     *
     * @return array
     */
    protected function getOptions()
    {
        return [
            ['force', null, InputOption::VALUE_NONE, 'Force the operation to run when in production.'],
        ];
    }
}
