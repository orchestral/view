<?php namespace Orchestra\View\Console;

use Symfony\Component\Console\Input\InputArgument;

abstract class ThemeCommand extends BaseCommand
{
    /**
     * Get the console command arguments.
     *
     * @return array
     */
    protected function getArguments()
    {
        return array(
            array('type', InputArgument::REQUIRED, 'Either frontend or backend.'),
        );
    }
}