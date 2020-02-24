<?php

namespace Orchestra\View\Console;

use Illuminate\Console\Command as BaseCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

abstract class Command extends BaseCommand
{
    /**
     * Execute the console command.
     *
     * @return mixed
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $result = parent::execute($input, $output);

        $this->finish();

        return $result;
    }

    /**
     * Finish the console command.
     */
    protected function finish(): void
    {
        // Save any changes to orchestra/memory
        $this->laravel['orchestra.memory']->finish();
    }
}
