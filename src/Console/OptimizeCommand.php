<?php namespace Orchestra\View\Console;

use InvalidArgumentException;
use Illuminate\View\Engines\CompilerEngine;
use Illuminate\Console\Command as BaseCommand;

class OptimizeCommand extends BaseCommand
{
    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'theme:optimize';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Pre-cache themes views in the application.';

    /**
     * Execute the console command.
     *
     * @return void
     */
    public function handle()
    {
        $this->info('Compiling views');

        $this->compileViews();
    }

    /**
     * Compile all view files.
     *
     * @return void
     */
    protected function compileViews()
    {
        foreach ($this->laravel['view']->getFinder()->getPaths() as $path) {
            foreach ($this->laravel['files']->allFiles($path) as $file) {
                try {
                    $engine = $this->laravel['view']->getEngineFromPath($file);
                } catch (InvalidArgumentException $e) {
                    continue;
                }

                if ($engine instanceof CompilerEngine) {
                    $engine->getCompiler()->compile($file);
                }
            }
        }
    }
}
