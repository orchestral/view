<?php

namespace Orchestra\View\TestCase;

use Orchestra\View\Decorator;

class DecoratorTest extends \PHPUnit_Framework_TestCase
{
    /**
     * Test add and using macros.
     *
     * @test
     */
    public function testAddAndUsingMacros()
    {
        $stub = new Decorator();

        $stub->macro('foo', function () {
            return 'foo';
        });

        $this->assertEquals('foo', $stub->foo());
    }

    /**
     * Test calling undefined macros throws an exception.
     *
     * @expectedException \BadMethodCallException
     */
    public function testCallingUndefinedMacrosThrowsException()
    {
        with(new Decorator())->foobar();
    }
}
