<?php

namespace Orchestra\View\TestCase;

use Mockery as m;
use PHPUnit\Framework\TestCase;
use Orchestra\View\FileViewFinder;

class FileViewFinderTest extends TestCase
{
    /**
     * Filesystem instance.
     *
     * @var Illuminate\Filesystem\Filesystem
     */
    private $files = null;

    /**
     * Setup the test environment.
     */
    protected function setUp()
    {
        $this->files = m::mock('\Illuminate\Filesystem\Filesystem');
    }

    /**
     * Teardown the test environment.
     */
    protected function tearDown()
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
        $stub = new FileViewFinder($files, ['/path/theme/views', '/path/app/views'], ['php']);

        $files->shouldReceive('exists')->once()->with('/path/theme/views/packages/foo/bar/hello.php')->andReturn(false)
            ->shouldReceive('exists')->once()->with('/path/app/views/packages/foo/bar/hello.php')->andReturn(false)
            ->shouldReceive('exists')->once()->with('/path/vendor/foo/bar/views/hello.php')->andReturn(true);

        $stub->addNamespace('foo/bar', '/path/vendor/foo/bar/views');
        $this->assertEquals('/path/vendor/foo/bar/views/hello.php', $stub->find('foo/bar::hello'));
    }

    /**
     * Test Orchestra\View\FileViewFinder::setPaths() method.
     *
     * @test
     */
    public function testSetPathsMethod()
    {
        $files = $this->files;
        $stub = new FileViewFinder($files, ['/path/theme/views', '/path/app/views'], ['php']);

        $refl = new \ReflectionObject($stub);
        $paths = $refl->getProperty('paths');
        $paths->setAccessible(true);

        $expected = ['/path/orchestra/views'];
        $stub->setPaths($expected);

        $this->assertEquals($expected, $paths->getValue($stub));
    }
}
