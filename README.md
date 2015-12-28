View Component for Orchestra Platform
==============

[![Join the chat at https://gitter.im/orchestral/platform/components](https://badges.gitter.im/Join%20Chat.svg)](https://gitter.im/orchestral/platform/components?utm_source=badge&utm_medium=badge&utm_campaign=pr-badge&utm_content=badge)

View Component is Orchestra Platform approach to deliver themeable application that support extensions. The functionality evolves by modifying how `Illuminate\View\ViewFileFinder` would resolve which file, which would first look into the current active theme folder, before resolving it cascading-ly.

This would allow extension (or even packages) to have it's own set of view styling while developer can maintain a standardise overall design through out the project using a theme.

[![Latest Stable Version](https://img.shields.io/github/release/orchestral/view.svg?style=flat-square)](https://packagist.org/packages/orchestra/view)
[![Total Downloads](https://img.shields.io/packagist/dt/orchestra/view.svg?style=flat-square)](https://packagist.org/packages/orchestra/view)
[![MIT License](https://img.shields.io/packagist/l/orchestra/view.svg?style=flat-square)](https://packagist.org/packages/orchestra/view)
[![Build Status](https://img.shields.io/travis/orchestral/view/master.svg?style=flat-square)](https://travis-ci.org/orchestral/view)
[![Coverage Status](https://img.shields.io/coveralls/orchestral/view/master.svg?style=flat-square)](https://coveralls.io/r/orchestral/view?branch=master)
[![Scrutinizer Quality Score](https://img.shields.io/scrutinizer/g/orchestral/view/master.svg?style=flat-square)](https://scrutinizer-ci.com/g/orchestral/view/)

## Version Compatibility

Laravel    | View
:----------|:----------
 4.0.x     | 2.0.x
 4.1.x     | 2.1.x
 4.2.x     | 2.2.x
 5.0.x     | 3.0.x
 5.1.x     | 3.1.x
 5.2.x     | 3.2.x
 5.3.x     | 3.3.x@dev

## Installation

To install through composer, simply put the following in your `composer.json` file:

```json
{
    "require": {
        "orchestra/view": "~3.0"
    }
}
```

And then run `composer install` from the terminal.

### Quick Installation

Above installation can also be simplify by using the following command:

    composer require "orchestra/view=~3.0"

## Configuration

Next add the service provider in `config/app.php`.

```php
'providers' => [

    // ...

    Orchestra\View\DecoratorServiceProvider::class,
    Orchestra\View\ViewServiceProvider::class,
    Orchestra\Memory\MemoryServiceProvider::class,
],
```

## Resources

* [Documentation](http://orchestraplatform.com/docs/latest/components/view)
* [Change Log](http://orchestraplatform.com/docs/latest/components/view/changes#v3-3)
