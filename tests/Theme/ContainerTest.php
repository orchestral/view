<?php namespace Orchestra\View\TestCase\Theme;

use Illuminate\Events\Dispatcher;
use Mockery as m;
use Orchestra\View\Theme\Container;

class ContainerTest extends \PHPUnit_Framework_TestCase
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
        $this->app = new \Illuminate\Container\Container;

        $this->app['path.public'] = '/var/orchestra/public';
        $this->app['path.base'] = '/var/orchestra';
        $this->app['request'] = $request = m::mock('Request');

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
        $app = $this->app;
        $app['view.finder'] = $finder = m::mock('\Orchestra\View\FileViewFinder');
        $app['files'] = $files = m::mock('\Illuminate\Filesystem\Filesystem');
        $app['events'] = $events = m::mock('\Illuminate\Events\Dispatcher', array($app));

        $stub = new Container($app, $events, $files);

        $finder->shouldReceive('getPaths')->once()->andReturn(array('/var/orchestra/app/views'))
            ->shouldReceive('setPaths')->once()
                ->with(array('/var/orchestra/resources/themes/default', '/var/orchestra/public/themes/default', '/var/orchestra/app/views'))
                ->andReturnNull();
        $files->shouldReceive('isDirectory')->once()->with('/var/orchestra/public/themes/default')->andReturn(true)
            ->shouldReceive('isDirectory')->once()->with('/var/orchestra/resources/themes/default')->andReturn(true)
            ->shouldReceive('exists')->once()->with('/var/orchestra/public/themes/default/theme.json')->andReturn(true)
            ->shouldReceive('get')->once()->with('/var/orchestra/public/themes/default/theme.json')
            ->andReturn('{"autoload":["start.php"]}')
            ->shouldReceive('requireOnce')->once()->with('/var/orchestra/public/themes/default/start.php')
            ->andReturnNull();
        $events->shouldReceive('fire')->once()->with('orchestra.theme.resolving', array($stub, $app))->andReturnNull()
            ->shouldReceive('fire')->once()->with('orchestra.theme.set: foo')->andReturnNull()
            ->shouldReceive('fire')->once()->with('orchestra.theme.unset: foo')->andReturnNull()
            ->shouldReceive('fire')->once()->with('orchestra.theme.set: default')->andReturnNull()
            ->shouldReceive('fire')->once()->with('orchestra.theme.boot: default')->andReturnNull();

        $stub->initiate();

        $stub->setTheme('foo');

        $this->assertEquals('foo', $stub->getTheme());

        $stub->setTheme('default');

        $this->assertEquals('default', $stub->getTheme());

        $this->assertTrue($stub->boot());

        $this->assertEquals("http://localhost/themes/default/foo", $stub->to('foo'));
        $this->assertEquals("/themes/default/foo", $stub->asset('foo'));

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
        $app = $this->app;
        $app['view.finder'] = $finder = m::mock('\Orchestra\View\FileViewFinder');
        $app['events'] = $events = new Dispatcher($app);
        $app['files'] = $files = m::mock('\Illuminate\Filesystem\Filesystem');

        $stub = new Container($app, $events, $files);

        $files->shouldReceive('exists')->once()->with('/var/orchestra/public/themes/default/theme.json')->andReturn(false)
            ->shouldReceive('isDirectory')->once()->with('/var/orchestra/public/themes/default')->andReturn(false)
            ->shouldReceive('isDirectory')->once()->with('/var/orchestra/resources/themes/default')->andReturn(false);

        $stub->initiate();

        $stub->setTheme('default');

        $this->assertTrue($stub->boot());
    }
}
