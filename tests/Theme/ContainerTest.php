<?php namespace Orchestra\View\Tests\Theme;

use Mockery as m;
use Orchestra\View\Theme\Container;

class ContainerTest extends \PHPUnit_Framework_TestCase {

	/**
	 * Application instance.
	 *
	 * @var Illuminate\Foundation\Application
	 */
	private $app;

	/**
	 * Setup the test environment.
	 */
	public function setUp()
	{
		$this->app = new \Illuminate\Container\Container;

		$this->app['path.public'] = '/var/orchestra/public';
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
		$app['view.finder'] = $finder = m::mock('ViewFinder');
		$app['files'] = $files = m::mock('\Illuminate\Filesystem\Filesystem');

		$finder->shouldReceive('getPaths')->once()->andReturn(array('/var/orchestra/app/views'))
			->shouldReceive('setPaths')->once()->with(array(
				'/var/orchestra/public/themes/default',
				'/var/orchestra/app/views'
			))->andReturn(null);
		$files->shouldReceive('isDirectory')->once()->with('/var/orchestra/public/themes/default')->andReturn(true)
			->shouldReceive('exists')->once()->with('/var/orchestra/public/themes/default/theme.json')->andReturn(true)
			->shouldReceive('get')->once()->with('/var/orchestra/public/themes/default/theme.json')->andReturn('{"autoload":["start.php"]}')
			->shouldReceive('requireOnce')->once()->with('/var/orchestra/public/themes/default/start.php')->andReturn(null);

		$stub = new Container($app);
		$stub->setTheme('default');

		$this->assertEquals('default', $stub->getTheme());

		$stub->boot();

		$this->assertEquals("http://localhost/themes/default/foo", $stub->to('foo'));
		$this->assertEquals("/themes/default/foo", $stub->asset('foo'));

	}
}
