<?php namespace Orchestra\View\Tests;

use Mockery as m;
use Orchestra\View\FileViewFinder;

class FileViewFinderTest extends \PHPUnit_Framework_TestCase {

	/**
	 * Filesystem instance.
	 *
	 * @var Illuminate\Filesystem\Filesystem
	 */
	private $files = null;

	/**
	 * Setup the test environment.
	 */
	public function setUp()
	{
		$this->files = m::mock('\Illuminate\Filesystem\Filesystem');
	}

	/**
	 * Teardown the test environment.
	 */
	public function tearDown()
	{
		unset($this->files);
		m::close();
	}
	
	/**
	 * Test Orchestra\View\FileViewFinder::findNamedPathView() method.
	 *
	 * @test
	 */
	public function testFindNamedPathViewMethod()
	{
		$files = $this->files;
		$stub  = new FileViewFinder($files, array('/path/theme/views', '/path/app/views'), array('php'));

		$files->shouldReceive('exists')->once()->with('/path/theme/views/packages/foo/bar/hello.php')->andReturn(false)
			->shouldReceive('exists')->once()->with('/path/app/views/packages/foo/bar/hello.php')->andReturn(false)
			->shouldReceive('exists')->once()->with('/path/vendor/foo/bar/views/hello.php')->andReturn(true);
		
		$stub->addNamespace('foo/bar', '/path/vendor/foo/bar/views');
		$this->assertEquals('/path/vendor/foo/bar/views/hello.php', $stub->find("foo/bar::hello"));
	}
}
