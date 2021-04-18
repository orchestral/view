View Component for Orchestra Platform
==============

View Component is Orchestra Platform approach to deliver themeable application that support extensions. The functionality evolves by modifying how `Illuminate\View\ViewFileFinder` would resolve which file, which would first look into the current active theme folder, before resolving it cascading-ly.

This would allow extension (or even packages) to have it's own set of view styling while developer can maintain a standardise overall design through out the project using a theme.

[![tests](https://github.com/orchestral/view/workflows/tests/badge.svg?branch=6.x)](https://github.com/orchestral/view/actions?query=workflow%3Atests+branch%3A6.x)
[![Latest Stable Version](https://poser.pugx.org/orchestra/view/version)](https://packagist.org/packages/orchestra/view)
[![Total Downloads](https://poser.pugx.org/orchestra/view/downloads)](https://packagist.org/packages/orchestra/view)
[![Latest Unstable Version](https://poser.pugx.org/orchestra/view/v/unstable)](//packagist.org/packages/orchestra/view)
[![License](https://poser.pugx.org/orchestra/view/license)](https://packagist.org/packages/orchestra/view)
[![Coverage Status](https://coveralls.io/repos/github/orchestral/view/badge.svg?branch=6.x)](https://coveralls.io/github/orchestral/view?branch=6.x)

## Version Compatibility

Laravel    | View
:----------|:----------
 5.5.x     | 3.5.x
 5.6.x     | 3.6.x
 5.7.x     | 3.7.x
 5.8.x     | 3.8.x
 6.x       | 4.x
 7.x       | 5.x
 8.x       | 6.x

## Installation


To install through composer, run the following command from terminal:

```bash
composer require "orchestra/view"
```

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

