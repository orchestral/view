<?php namespace Orchestra\View\TestCase\Theme;

use Illuminate\Container\Container;
use Mockery as m;
use Orchestra\View\Theme\Theme;

class ThemeTest extends \PHPUnit_Framework_TestCase
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
        $this->app = new Container();

        $this->app['path.public'] = '/var/orchestra/public';
        $this->app['path.base']   = '/var/orchestra';
        $this->app['request']     = $request     = m::mock('Request');

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
     * Test Orchestra\View\Theme\Container::setTheme() and
     * Orchestra\View\Theme\Container::getTheme() method.
     *
     * @test
     */
    public function testGetterAndSetterForTheme()
    {
        $app                = $this->app;
        $app['view.finder'] = $finder = m::mock('\Orchestra\View\FileViewFinder');
        $app['files']       = $files       = m::mock('\Illuminate\Filesystem\Filesystem');
        $app['events']      = $events      = m::mock('\Illuminate\Contracts\Events\Dispatcher');

        $defaultPath  = '/var/orchestra/resources/views';
        $themePath    = '/var/orchestra/public/themes';
        $resourcePath = '/var/orchestra/resources/themes';

        $stub = new Theme($app, $events, $files);

        $finder->shouldReceive('getPaths')->times(3)->andReturn([$defaultPath])
            ->shouldReceive('setPaths')->once()->with([$defaultPath])->andReturnNull()
            ->shouldReceive('setPaths')->once()->with(["{$resourcePath}/foo", "{$themePath}/foo", $defaultPath])->andReturnNull()
            ->shouldReceive('setPaths')->once()->with(["{$resourcePath}/default", "{$themePath}/default", $defaultPath])->andReturnNull();
        $files->shouldReceive('isDirectory')->once()->with("{$themePath}/foo")->andReturn(true)
            ->shouldReceive('isDirectory')->once()->with("{$resourcePath}/foo")->andReturn(true)
            ->shouldReceive('isDirectory')->once()->with("{$themePath}/default")->andReturn(true)
            ->shouldReceive('isDirectory')->once()->with("{$resourcePath}/default")->andReturn(true)
            ->shouldReceive('exists')->once()->with("{$themePath}/default/theme.json")->andReturn(true)
            ->shouldReceive('get')->once()->with('/var/orchestra/public/themes/default/theme.json')
            ->andReturn('{"autoload":["start.php"]}')
            ->shouldReceive('requireOnce')->once()->with('/var/orchestra/public/themes/default/start.php')
            ->andReturnNull();
        $events->shouldReceive('fire')->twice()->with('orchestra.theme.resolving', [$stub, $app])->andReturnNull()
            ->shouldReceive('fire')->once()->with('orchestra.theme.set: foo')->andReturnNull()
            ->shouldReceive('fire')->once()->with('orchestra.theme.unset: foo')->andReturnNull()
            ->shouldReceive('fire')->once()->with('orchestra.theme.set: default')->andReturnNull()
            ->shouldReceive('fire')->once()->with('orchestra.theme.boot: default')->andReturnNull();

        $stub->initiate();

        $stub->setTheme('foo');

        $this->assertEquals('foo', $stub->getTheme());

        $this->assertTrue($stub->resolving());

        $stub->setTheme('default');

        $this->assertEquals('default', $stub->getTheme());

        $this->assertTrue($stub->boot());

        $this->assertEquals("http://localhost/themes/default/hello", $stub->to('hello'));
        $this->assertEquals("/themes/default/hello", $stub->asset('hello'));

        $this->assertFalse($stub->resolving());
        $this->assertFalse($stub->boot());
    }

    /**
     * Test Orchestra\View\Theme\Container::boot() method when manifest
     * is not available.
     *
     * @test
     */
    public function testBootMethodWhenManifestIsNotAvailable()
    {
        $app                = $this->app;
        $app['view.finder'] = $finder = m::mock('\Orchestra\View\FileViewFinder');
        $app['events']      = $events      = m::mock('\Illuminate\Contracts\Events\Dispatcher');
        $app['files']       = $files       = m::mock('\Illuminate\Filesystem\Filesystem');

        $themePath    = '/var/orchestra/public/themes';
        $resourcePath = '/var/orchestra/resources/themes';

        $stub = new Theme($app, $events, $files);

        $files->shouldReceive('exists')->once()->with("{$themePath}/default/theme.json")->andReturn(false)
            ->shouldReceive('isDirectory')->once()->with("{$themePath}/default")->andReturn(false)
            ->shouldReceive('isDirectory')->once()->with("{$resourcePath}/default")->andReturn(false);

        $events->shouldReceive('fire')->once()->with('orchestra.theme.resolving', m::type('Array'))->andReturnNull()
            ->shouldReceive('fire')->once()->with('orchestra.theme.set: default')->andReturnNull()
            ->shouldReceive('fire')->once()->with('orchestra.theme.boot: default')->andReturnNull();

        $stub->initiate();

        $stub->setTheme('default');

        $this->assertTrue($stub->resolving());

        $stub->boot();
    }
}
