<?php namespace Orchestra\View\TestCase\Theme;

use Mockery as m;
use Orchestra\View\Theme\Finder;

class FinderTest extends \PHPUnit_Framework_TestCase
{
    /**
     * Application instance.
     *
     * @var \Illuminate\Foundation\Application
     */
    private $app = null;

    /**
     * Setup the test environment.
     */
    public function setUp()
    {
        $this->app = new \Illuminate\Container\Container;
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
        $app['path.public'] = '/var/foo/public/';
        $app['files'] = $file = m::mock('\Illuminate\Filesystem\Filesystem');

        $file->shouldReceive('directories')->once()
                ->with('/var/foo/public/themes/')->andReturn(array(
                    '/var/foo/public/themes/a',
                    '/var/foo/public/themes/b',
                ))
            ->shouldReceive('exists')->once()
                ->with('/var/foo/public/themes/a/theme.json')->andReturn(true)
            ->shouldReceive('exists')->once()
                ->with('/var/foo/public/themes/b/theme.json')->andReturn(false)
            ->shouldReceive('get')->once()
                ->with('/var/foo/public/themes/a/theme.json')->andReturn('{"name": "foo"}');

        $stub   = new Finder($app);
        $themes = $stub->detect();

        $this->assertInstanceOf('\Orchestra\View\Theme\Manifest', $themes['a']);
        $this->assertEquals('/var/foo/public/themes/a', $themes['a']->path);
    }
}
