<?php namespace Orchestra\View;

use BadMethodCallException;

class Decorator {
	
	/**
	 * The registered custom macros.
	 *
	 * @var array
	 */
	protected $macros = array();

	/**
	 * Registers a custom macro.
	 *
	 * @access public
	 * @param  string   $name
	 * @param  \Closure $macro
	 * @return void
	 */
	public function macro($name, $macro)
	{
		$this->macros[$name] = $macro;
	}

	/**
	 * Render the macro.
	 *
	 * @access public	
	 * @param  string   $name
	 * @param  array    $data
	 * @return mixed
	 * @throws \BadMethodCallException
	 */
	public function render($name, $data = null)
	{
		if (isset($this->macros[$name]))
		{
			return call_user_func($this->macros[$name], $data);
		}

		throw new BadMethodCallException("Method [$name] does not exist.");
	}

	/**
	 * Dynamically handle calls to custom macros.
	 *
	 * @access public
	 * @param  string   $method
	 * @param  array    $parameters
	 * @return mixed
	 */
	public function __call($method, $parameters)
	{
		array_unshift($parameters, $method);

		return call_user_func_array(array($this, 'render'), $parameters);
	}
}
