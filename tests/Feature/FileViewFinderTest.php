<?php

namespace Orchestra\View\TestCase\Feature;

use Illuminate\Support\Facades\View;

class FileViewFinderTest extends TestCase
{
    /** @test */
    public function it_can_find_namespaced_view()
    {
        $finder = View::getFinder();

        $finder->addNamespace('foo/bar', __DIR__.'/views');

        $this->assertInstanceOf('\Orchestra\View\FileViewFinder', $finder);
        $this->assertSame(__DIR__.'/views/hello.php', $finder->find('foo/bar::hello'));
    }

     /** @test */
    public function it_can_set_custom_paths_for_view()
    {
        $finder = View::getFinder();

        $expected = [__DIR__.'/views'];
        $finder->setPaths($expected);

        $this->assertEquals($expected, $finder->getPaths());
    }
}
