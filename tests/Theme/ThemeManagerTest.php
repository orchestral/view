<?php namespace Orchestra\View\TestCase\Theme;

use Mockery as m;
use Illuminate\Container\Container;
use Orchestra\View\Theme\ThemeManager;

class ThemeManagerTest extends \PHPUnit_Framework_TestCase
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
    public function setUp()
    {
        $this->app = new Container;
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
    public function tearDown()
    {
        unset($this->app);
        m::close();
    }

    /**
     * Test contructing Orchestra\View\Theme\ThemeManager.
     *
     * @test
     */
    public function testConstructMethod()
    {
        $app  = $this->app;
        $stub = new ThemeManager($app);
        $this->assertInstanceOf('\Orchestra\View\Theme\Container', $stub->driver());
    }

    /**
     * Test Orchestra\View\Theme\ThemeManager::detect() method.
     *
     * @test
     */
    public function testDetectMethod()
    {
        $app  = $this->app;
        $app['orchestra.theme.finder'] = $finder = m::mock('\Orchestra\View\Theme\Finder');

        $finder->shouldReceive('detect')->once()->andReturn('foo');

        $stub = new ThemeManager($app);
        $this->assertEquals('foo', $stub->detect());
    }
}
