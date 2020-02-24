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
     */
    public function macro(string $name, callable $macro): void
    {
        $this->macros[$name] = $macro;
    }

    /**
     * Render the macro.
     *
     * @param  mixed  $parameters
     *
     * @throws \BadMethodCallException
     *
     * @return string
     */
    public function render(string $name, ...$parameters)
    {
        if (! isset($this->macros[$name])) {
            throw new BadMethodCallException("Method [$name] does not exist.");
        }

        return $this->macros[$name](...$parameters);
    }

    /**
     * Dynamically handle calls to custom macros.
     *
     * @return mixed
     */
    public function __call(string $method, array $parameters)
    {
        \array_unshift($parameters, $method);

        return $this->render($method, ...$parameters);
    }
}
