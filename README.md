Orchestra Platform View Component
==============

`Orchestra\View` is Orchestra Platform approach to deliver themeable application that support extensions. The functionality evolves by modifying how `Illuminate\View\ViewFileFinder` would resolve which file, which would first look into the current active theme folder, before resolving it cascading-ly.

This would allow extension (or even packages) to have it's own set of view styling while developer can maintain a standardise overall design through out the project using a theme.

[![Latest Stable Version](https://poser.pugx.org/orchestra/view/v/stable.png)](https://packagist.org/packages/orchestra/view) 
[![Total Downloads](https://poser.pugx.org/orchestra/view/downloads.png)](https://packagist.org/packages/orchestra/view) 
[![Build Status](https://travis-ci.org/orchestral/view.png?branch=2.1)](https://travis-ci.org/orchestral/view) 
[![Coverage Status](https://coveralls.io/repos/orchestral/view/badge.png?branch=2.1)](https://coveralls.io/r/orchestral/view?branch=2.1) 
[![Scrutinizer Quality Score](https://scrutinizer-ci.com/g/orchestral/view/badges/quality-score.png?s=2c779ead7d25dc51d1b1dd0a31447e5939d21793)](https://scrutinizer-ci.com/g/orchestral/view/) 

## Quick Installation

To install through composer, simply put the following in your `composer.json` file:

```json
{
	"require": {
		"orchestra/view": "2.1.*@dev"
	}
}
```

Next add the service provider in `app/config/app.php`.

```php
'providers' => array(

	// ...

	'Orchestra\View\DecoratorServiceProvider',
	'Orchestra\View\ViewServiceProvider',
	'Orchestra\Memory\MemoryServiceProvider',
),
```

## Resources

* [Documentation](http://orchestraplatform.com/docs/latest/components/view)
* [Change Log](http://orchestraplatform.com/docs/latest/components/view/changes#v2-1)
