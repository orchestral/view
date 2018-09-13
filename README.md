View Component for Orchestra Platform
==============

View Component is Orchestra Platform approach to deliver themeable application that support extensions. The functionality evolves by modifying how `Illuminate\View\ViewFileFinder` would resolve which file, which would first look into the current active theme folder, before resolving it cascading-ly.

This would allow extension (or even packages) to have it's own set of view styling while developer can maintain a standardise overall design through out the project using a theme.

[![Build Status](https://travis-ci.org/orchestral/view.svg?branch=3.7)](https://travis-ci.org/orchestral/view)
[![Latest Stable Version](https://poser.pugx.org/orchestra/view/version)](https://packagist.org/packages/orchestra/view)
[![Total Downloads](https://poser.pugx.org/orchestra/view/downloads)](https://packagist.org/packages/orchestra/view)
[![Latest Unstable Version](https://poser.pugx.org/orchestra/view/v/unstable)](//packagist.org/packages/orchestra/view)
[![License](https://poser.pugx.org/orchestra/view/license)](https://packagist.org/packages/orchestra/view)
[![Coverage Status](https://coveralls.io/repos/github/orchestral/view/badge.svg?branch=3.7)](https://coveralls.io/github/orchestral/view?branch=3.7)

## Table of Content

* [Version Compatibility](#version-compatibility)
* [Installation](#installation)
* [Configuration](#configuration)
* [Changelog](https://github.com/orchestral/view/releases)

## Version Compatibility

Laravel    | View
:----------|:----------
 4.x.x     | 2.x.x
 5.0.x     | 3.0.x
 5.1.x     | 3.1.x
 5.2.x     | 3.2.x
 5.3.x     | 3.3.x
 5.4.x     | 3.4.x
 5.5.x     | 3.5.x
 5.6.x     | 3.6.x
 5.7.x     | 3.7.x

## Installation

To install through composer, simply put the following in your `composer.json` file:

```json
{
    "require": {
        "orchestra/view": "^3.0"
    }
}
```

And then run `composer install` from the terminal.

### Quick Installation

Above installation can also be simplify by using the following command:

    composer require "orchestra/view=^3.0"

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

