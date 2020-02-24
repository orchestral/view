<?php

namespace Orchestra\View\Console;

use Illuminate\Console\ConfirmableTrait;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;
use InvalidArgumentException;
use Orchestra\View\Theme\Finder;
use Orchestra\View\Theme\Manifest;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputOption;

class ActivateCommand extends Command
{
    use ConfirmableTrait;

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
     * Execute the console command.
     *
     * @return void
     * @return int
     */
    public function handle(Finder $finder)
    {
        if (! $this->confirmToProceed()) {
            return 126;
        }

        $group = Str::lower($this->argument('group'));
        $id = Str::lower($this->argument('id'));

        $theme = $this->getAvailableTheme($finder, $group)->get($id);

        if ($this->validateProvidedTheme($group, $id, $theme)) {
            $this->laravel['orchestra.memory']->set("site.theme.{$group}", $theme->get('uid'));

            $this->info("Theme [{$theme->get('name')}] activated on group [{$group}].");
        }

        return 0;
    }

    /**
     * Validate provided theme.
     *
     * @param  string|int  $id
     * @param  object|null  $theme
     *
     * @throws \InvalidArgumentException
     */
    protected function validateProvidedTheme(string $group, $id, $theme): bool
    {
        if (! \in_array($group, $this->type)) {
            throw new InvalidArgumentException("Invalid theme name [{$group}], should either be 'frontend' or 'backend'.");
        }

        if (\is_null($theme)) {
            throw new InvalidArgumentException("Invalid Theme ID [{$id}], or is not available for '{$group}'.");
        }

        return true;
    }

    /**
     * Get all available theme by type.
     */
    protected function getAvailableTheme(Finder $finder, string $type): Collection
    {
        $themes = $finder->detect();

        return $themes->filter(static function (Manifest $manifest) use ($type) {
            $group = $manifest->get('type');

            if (! empty($group) && ! \in_array($type, $group)) {
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
