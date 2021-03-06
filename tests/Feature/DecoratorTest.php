<?php

namespace Orchestra\View\Tests\Feature;

use Orchestra\Support\Facades\Decorator;

class DecoratorTest extends TestCase
{
    /**
     * Get package aliases.
     *
     * @param  \Illuminate\Foundation\Application  $app
     */
    protected function getPackageAliases($app): array
    {
        return [
            'Decorator' => Decorator::class,
        ];
    }

    /**
     * Get package providers.
     *
     * @param  \Illuminate\Foundation\Application  $app
     */
    protected function getPackageProviders($app): array
    {
        return [
            \Orchestra\View\DecoratorServiceProvider::class,
        ];
    }

    /** @test */
    public function it_can_add_and_use_macros()
    {
        Decorator::macro('foo', function () {
            return 'foo';
        });

        $this->assertEquals('foo', Decorator::foo());
    }

    /** @test */
    public function it_throws_exception_when_calling_unknown_macros()
    {
        $this->withoutExceptionHandling();

        $this->expectException('BadMethodCallException');

        Decorator::foobar();
    }
}
