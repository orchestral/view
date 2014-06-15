<?php namespace Orchestra\View\Console;

use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

abstract class BaseCommand extends Command
{
    /**
     * Execute the console command.
     *
     * @param  \Symfony\Component\Console\Input\InputInterface  $input
     * @param  \Symfony\Component\Console\Output\OutputInterface  $output
     * @return mixed
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $result = $this->fire();

        $this->finish();

        return $result;
    }

    /**
     * Execute the console command.
     *
     * @return void
     */
    abstract public function fire();

    /**
     * Finish the console command.
     *
     * @return void
     */
    protected function finish()
    {
        // Save any changes to orchestra/memory
        $this->laravel['orchestra.memory']->finish();
    }
}
