<?php

namespace Orchestra\View\TestCase\Unit\Theme;

use Mockery as m;
use PHPUnit\Framework\TestCase;
use Orchestra\View\Theme\Finder;
use Illuminate\Container\Container;

class FinderTest extends TestCase
{
    /**
     * Application instance.
     *
     * @var \Illuminate\Container\Container
     */
    private $app = null;

    /**
     * Setup the test environment.
     */
    protected function setUp(): void
    {
        $this->app = new Container();
    }

    /**
     * Teardown the test environment.
     */
    protected function tearDown(): void
    {
        unset($this->app);
        m::close();
    }

    /** @test */
    public function it_can_detect_themes()
    {
        $publicPath = '/var/orchestra/public/';
        $files = m::mock('\Illuminate\Filesystem\Filesystem');

        $files->shouldReceive('directories')->once()
                ->with('/var/orchestra/public/themes/')->andReturn([
                    '/var/orchestra/public/themes/a',
                    '/var/orchestra/public/themes/b',
                ])
            ->shouldReceive('exists')->once()
                ->with('/var/orchestra/public/themes/a/theme.json')->andReturn(true)
            ->shouldReceive('exists')->once()
                ->with('/var/orchestra/public/themes/b/theme.json')->andReturn(false)
            ->shouldReceive('get')->once()
                ->with('/var/orchestra/public/themes/a/theme.json')->andReturn('{"name": "foo"}');

        $stub = new Finder($files, $publicPath);
        $themes = $stub->detect();

        $this->assertInstanceOf('\Orchestra\View\Theme\Manifest', $themes['a']);
        $this->assertEquals('/var/orchestra/public/themes/a', $themes['a']->path);
    }
}
