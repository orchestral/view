<?php

namespace Orchestra\View\TestCase\Theme;

use Mockery as m;
use PHPUnit\Framework\TestCase;
use Illuminate\Support\Collection;
use Illuminate\Container\Container;
use Orchestra\View\Theme\ThemeManager;

class ThemeManagerTest extends TestCase
{
    /**
     * Application instance.
     *
     * @var \Illuminate\Container\Container
     */
    private $app;

    /**
     * Setup the test environment.
     */
    protected function setUp(): void
    {
        $this->app = new Container();
        $this->app['request'] = $request = m::mock('\Illuminate\Http\Request');
        $this->app['events'] = m::mock('\Illuminate\Contracts\Events\Dispatcher');
        $this->app['files'] = m::mock('\Illuminate\Filesystem\Filesystem');
        $this->app['path.base'] = '/var/orchestra';
        $this->app['path.public'] = '/var/orchestra/public';

        $request->shouldReceive('root')->andReturn('http://localhost/');
    }

    /**
     * Teardown the test environment.
     */
    protected function tearDown()
    {
        unset($this->app);
        m::close();
    }

    /** @test */
    public function it_have_the_expected_signature()
    {
        $app = $this->app;
        $stub = new ThemeManager($app);
        $this->assertInstanceOf('\Orchestra\View\Theme\Theme', $stub->driver());
    }

    /** @test */
    public function it_can_detect_themes()
    {
        $app = $this->app;
        $app['orchestra.theme.finder'] = $finder = m::mock('\Orchestra\View\Theme\Finder');

        $finder->shouldReceive('detect')->once()->andReturn(new Collection('foo'));

        $stub = new ThemeManager($app);
        $this->assertEquals(['foo'], $stub->detect()->all());
    }
}
