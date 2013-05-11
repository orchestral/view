<?php namespace Orchestra\View\Theme;

class Container {
	
	/**
	 * Application instance.
	 *
	 * @var Illuminate\Foundation\Application
	 */
	protected $app = null;

	/**
	 * Booted indicator.
	 *
	 * @var boolean
	 */
	protected $booted = false;
	
	/**
	 * Theme name.
	 *
	 * @var string
	 */
	protected $theme = null;

	/**
	 * Filesystem path of theme.
	 *
	 * @var string
	 */
	protected $path = null;

	/**
	 * URL path of Theme
	 *
	 * @var string
	 */
	protected $absoluteUrl = null;

	/**
	 * Relative URL path of Theme
	 *
	 * @var string
	 */
	protected $relativeUrl = null;

	/**
	 * Start Theme Engine, this should be called from Orchestra\Core::start()
	 * or whenever we need to overwrite current active theme per request.
	 *
	 * @static
	 * @access public
	 * @param  Illuminate\Foundation\Application    $app
	 * @param  string                               $name
	 * @return void
	 */
	public function __construct($app)
	{
		$this->app  = $app;
		$baseUrl    = $app['url']->to('/');
		$this->path = $app['path.public'].'/themes';

		// Register relative and absolute URL for theme usage.
		$this->absoluteUrl = rtrim($baseUrl, '/').'/themes';
		$this->relativeUrl = str_replace($baseUrl, '/', $this->absoluteUrl);
	}

	/**
	 * Set the theme, this would also load the theme manifest.
	 *
	 * @access public	
	 * @param  string   $theme
	 * @return void
	 */
	public function setTheme($theme)
	{
		$this->theme = $theme;
		$viewFinder  = $this->app['view.finder'];

		$paths = array_merge(array($this->getThemePath()), $viewFinder->getPaths());
		$viewFinder->setPaths($paths);
	}

	/**
	 * Get the theme.
	 *
	 * @access public
	 * @return string
	 */
	public function getTheme()
	{
		return $this->theme;
	}

	/**
	 * Boot the theme by autoloading all the relevant files.
	 *
	 * @access public
	 * @return void
	 */
	public function boot()
	{
		if ($this->booted) return;

		$this->booted = true;

		$themePath = $this->getThemePath();
		$manifest  = null;

		if ($this->app['files']->isDirectory($themePath))
		{
			$manifest = new Manifest($this->app, $themePath);
		}

		// There might be situation where Orchestra Platform was unable 
		// to get theme information, we should only assume there a valid
		// theme when manifest is actually an instance of 
		// Orchestra\View\Theme\Manifest
		if ( ! $manifest instanceof Manifest) return null;

		// Loop and include all file which was mark as autoloaded.
		if (isset($manifest->autoload) and is_array($manifest->autoload))
		{
			foreach ($manifest->autoload as $file)
			{
				$file = ltrim($file, '/');
				$this->app['files']->requireOnce("{$themePath}/{$file}");
			}
		}
	}

	/**
	 * Get theme path.
	 *
	 * @access public
	 * @return string
	 */
	public function getThemePath()
	{
		return "{$this->path}/{$this->theme}";
	}

	/**
	 * URL helper for the theme.
	 *
	 * @access public
	 * @param  string   $url
	 * @return string
	 */
	public function to($url = '')
	{
		return "{$this->absoluteUrl}/{$this->theme}/{$url}";
	}

	/**
	 * Relative URL helper for Theme
	 *
	 * @access public
	 * @param  string   $url
	 * @return string
	 */
	public function asset($url = '')
	{
		return "{$this->relativeUrl}/{$this->theme}/{$url}";
	}
}
