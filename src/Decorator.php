<?php namespace Orchestra\View;

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
     * @param  array   $data
     *
     * @return string
     *
     * @throws \BadMethodCallException
     */
    public function render($name, $data = null)
    {
        if (! isset($this->macros[$name])) {
            throw new BadMethodCallException("Method [$name] does not exist.");
        }

        $args = func_get_args();

        array_shift($args);

        return call_user_func_array($this->macros[$name], $args);
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

        return call_user_func_array([$this, 'render'], $parameters);
    }
}
