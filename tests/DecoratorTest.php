<?php

namespace Orchestra\View\TestCase;

use Orchestra\View\Decorator;
use PHPUnit\Framework\TestCase;

class DecoratorTest extends TestCase
{
    /** @test */
    public function it_can_add_and_use_macros()
    {
        $stub = new Decorator();

        $stub->macro('foo', function () {
            return 'foo';
        });

        $this->assertEquals('foo', $stub->foo());
    }

    /**
     * @test
     * @expectedException \BadMethodCallException
     */
    public function it_throws_exception_when_calling_unknown_macros()
    {
        with(new Decorator())->foobar();
    }
}
