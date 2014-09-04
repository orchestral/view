<?php namespace Orchestra\View\TestCase\Theme;

use Illuminate\Container\Container;
use Mockery as m;
use Orchestra\View\Theme\Finder;

class FinderTest extends \PHPUnit_Framework_TestCase
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
    public function setUp()
    {
        $this->app = new Container;
    }

    /**
     * Teardown the test environment.
     */
    public function tearDown()
    {
        unset($this->app);
        m::close();
    }

    /**
     * Test Orchestra\Theme\Finder::detect() method.
     *
     * @test
     */
    public function testDetectMethod()
    {
        $app = $this->app;
        $app['path.public'] = '/var/orchestra/public/';
        $app['files'] = $file = m::mock('\Illuminate\Filesystem\Filesystem');

        $file->shouldReceive('directories')->once()
                ->with('/var/orchestra/public/themes/')->andReturn(array(
                    '/var/orchestra/public/themes/a',
                    '/var/orchestra/public/themes/b',
                ))
            ->shouldReceive('exists')->once()
                ->with('/var/orchestra/public/themes/a/theme.json')->andReturn(true)
            ->shouldReceive('exists')->once()
                ->with('/var/orchestra/public/themes/b/theme.json')->andReturn(false)
            ->shouldReceive('get')->once()
                ->with('/var/orchestra/public/themes/a/theme.json')->andReturn('{"name": "foo"}');

        $stub   = new Finder($app);
        $themes = $stub->detect();

        $this->assertInstanceOf('\Orchestra\View\Theme\Manifest', $themes['a']);
        $this->assertEquals('/var/orchestra/public/themes/a', $themes['a']->path);
    }
}
