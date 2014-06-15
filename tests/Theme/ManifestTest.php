<?php namespace Orchestra\View\TestCase\Theme;

use Mockery as m;
use Orchestra\View\Theme\Manifest;

class ManifestTest extends \PHPUnit_Framework_TestCase
{
    /**
     * Teardown the test environment.
     */
    public function tearDown()
    {
        m::close();
    }

    /**
     * Test Orchestra\View\Theme\Manifest.
     *
     * @test
     */
    public function testManifest()
    {
        $files = m::mock('\Illuminate\Filesystem\Filesystem');

        $files->shouldReceive('exists')->once()->with('/var/orchestra/themes/default/theme.json')->andReturn(true)
            ->shouldReceive('get')->once()->with('/var/orchestra/themes/default/theme.json')->andReturn('{"foo":"bar"}');

        $stub = new Manifest($files, '/var/orchestra/themes/default');

        $this->assertNull($stub->foobar);
        $this->assertEquals('bar', $stub->foo);
        $this->assertFalse(isset($stub->foobar));
        $this->assertTrue(isset($stub->foo));
        $this->assertEquals('/var/orchestra/themes/default', $stub->path);
    }

    /**
     * Test Orchestra\View\Theme\Manifest throws an exception.
     *
     * @expectedException \RuntimeException
     */
    public function testManifestThrowsException()
    {
        $files = m::mock('\Illuminate\Filesystem\Filesystem');

        $files->shouldReceive('exists')->once()->with('/var/orchestra/themes/default/theme.json')->andReturn(true)
            ->shouldReceive('get')->once()->with('/var/orchestra/themes/default/theme.json')->andReturn('{"foo}');

        new Manifest($files, '/var/orchestra/themes/default');
    }
}
