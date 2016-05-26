<?php

namespace Orchestra\View;

use BadMethodCallException;

class Decorator
{
    /**
     * The registered custom macros.
     *
     * @var array
     */
    protected $macros = [];

    /**
     * Registers a custom macro.
     *
     * @param  string  $name
     * @param  \Closure  $macro
     *
     * @return void
     */
    public function macro($name, $macro)
    {
        $this->macros[$name] = $macro;
    }

    /**
     * Render the macro.
     *
     * @param  string  $name
     * @param  mixed  $parameters
     *
     * @return string
     *
     * @throws \BadMethodCallException
     */
    public function render($name, ...$parameters)
    {
        if (! isset($this->macros[$name])) {
            throw new BadMethodCallException("Method [$name] does not exist.");
        }

        return $this->macros[$name](...$parameters);
    }

    /**
     * Dynamically handle calls to custom macros.
     *
     * @param  string  $method
     * @param  array   $parameters
     *
     * @return mixed
     */
    public function __call($method, $parameters)
    {
        array_unshift($parameters, $method);

        return $this->render($method, ...$parameters);
    }
}
