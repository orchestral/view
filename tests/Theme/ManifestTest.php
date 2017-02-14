<?php

namespace Orchestra\View\TestCase\Theme;

use Mockery as m;
use PHPUnit\Framework\TestCase;
use Orchestra\View\Theme\Manifest;

class ManifestTest extends TestCase
{
    /**
     * Teardown the test environment.
     */
    protected function tearDown()
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
            ->shouldReceive('get')->once()->with('/var/orchestra/themes/default/theme.json')->andReturn('{"name":"foobar"}');

        $stub = new Manifest($files, '/var/orchestra/themes/default');

        $this->assertNull($stub->foobar);
        $this->assertEquals('foobar', $stub->name);
        $this->assertEquals('foobar', $stub->get('name'));
        $this->assertFalse(isset($stub->hello));
        $this->assertTrue(is_array($stub->autoload));

        $this->assertEquals('/var/orchestra/themes/default', $stub->path);

        $this->assertInstanceOf('\Illuminate\Support\Fluent', $stub->items());
        $this->assertEquals('foobar', $stub->items()->get('name'));
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
