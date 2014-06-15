<?php namespace Orchestra\View\Console;

use Orchestra\Memory\Provider as Memory;

class StatusCommand extends BaseCommand
{
    /**
     * Memory instance.
     *
     * @var \Orchestra\Memory\Provider
     */
    protected $memory;

    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'theme';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'List activated theme for Orchestra Platform';

    /**
     * Construct a new status command.
     *
     * @param  \Orchestra\Memory\Provider $memory
     */
    public function __construct(Memory $memory)
    {
        $this->memory = $memory;
    }

    /**
     * Execute the console command.
     *
     * @return void
     */
    public function fire()
    {
        $header  = array('Type', 'Theme Name');
        $content = array(
            array('Frontend', $this->memory->get('site.theme.frontend')),
            array('Backend', $this->memory->get('site.theme.backend')),
        );

        $this->table($header, $content);
    }
}
