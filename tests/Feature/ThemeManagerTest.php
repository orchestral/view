<?php

namespace Orchestra\View\Tests\Feature;

use Illuminate\Support\Collection;
use Mockery as m;
use Orchestra\Support\Facades\Theme;

class ThemeManagerTest extends TestCase
{
    /** @test */
    public function it_have_the_expected_signature()
    {
        $manager = $this->app['orchestra.theme'];

        $this->assertInstanceOf('\Orchestra\View\Theme\ThemeManager', $manager);
        $this->assertInstanceOf('\Orchestra\View\Theme\Theme', $manager->driver());
    }

    /** @test */
    public function it_can_detect_themes()
    {
        $finder = m::mock('\Orchestra\View\Theme\Finder');
        $finder->shouldReceive('detect')->once()->andReturn(new Collection('foo'));

        $this->app->instance('orchestra.theme.finder', $finder);

        $this->assertEquals(['foo'], Theme::detect()->all());
    }
}
