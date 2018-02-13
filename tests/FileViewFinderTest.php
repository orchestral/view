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
    protected function setUp(): void
    {
        $this->files = m::mock('\Illuminate\Filesystem\Filesystem');
    }

    /**
     * Teardown the test environment.
     */
    protected function tearDown(): void
    {
        unset($this->files);
        m::close();
    }

    /** @test */
    public function it_can_find_namespaced_view()
    {
        $files = $this->files;
        $stub = new FileViewFinder($files, ['/path/theme/views', '/path/app/views'], ['php']);

        $files->shouldReceive('exists')->once()->with('/path/theme/views/packages/foo/bar/hello.php')->andReturn(false)
            ->shouldReceive('exists')->once()->with('/path/app/views/packages/foo/bar/hello.php')->andReturn(false)
            ->shouldReceive('exists')->once()->with('/path/vendor/foo/bar/views/hello.php')->andReturn(true);

        $stub->addNamespace('foo/bar', '/path/vendor/foo/bar/views');
        $this->assertEquals('/path/vendor/foo/bar/views/hello.php', $stub->find('foo/bar::hello'));
    }

    /** @test */
    public function it_can_set_custom_paths_for_view()
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
