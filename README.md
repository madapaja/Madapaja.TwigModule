Madapaja.TwigModule
===================

Twig Module for BEAR.Resource

[![Latest Stable Version](https://poser.pugx.org/madapaja/twig-module/v/stable.svg)](https://packagist.org/packages/madapaja/twig-module)
[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/madapaja/Madapaja.TwigModule/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/madapaja/Madapaja.TwigModule/?branch=master)
[![Build Status](https://travis-ci.org/madapaja/Madapaja.TwigModule.svg)](https://travis-ci.org/madapaja/Madapaja.TwigModule)

Introduction
------------
Madapaja.TwigModule is [Twig](http://twig.sensiolabs.org/) adaptor extension for [BEAR.Sunday](https://github.com/koriym/BEAR.Sunday) framework.

Installation
------------
Add the package name to your `composer.json`.

To composer command:

```
composer require madapaja/twig-module
```

or to modify your `composer.json` and `composer update` command:

```json
{
    "require": {
        "madapaja/twig-module": "~1.0"
    }
}
```

Usage
-----

For example you are using [BEAR.Package](https://github.com/koriym/BEAR.Package) ...

modifying your `AppModule` as:

```php
namespace MyVendor\MyPackage\Module;

use Madapaja\TwigModule\Annotation\TwigOptions;
use Madapaja\TwigModule\Annotation\TwigPaths;
use Madapaja\TwigModule\TwigModule;
use Ray\Di\AbstractModule;

class AppModule extends AbstractModule
{
    protected function configure()
    {
        $this->install(new TwigModule());

        // You can add twig template paths by the following
        $appDir = dirname(dirname(__DIR__));
        $paths = [$appDir . '/src/Resource', $appDir . '/var/lib/twig'];
        $this->bind()->annotatedWith(TwigPaths::class)->toInstance($paths);

        // Also you can set environment options
        // @see http://twig.sensiolabs.org/doc/api.html#environment-options
        $options = [
            'debug' => true,
            'cache' => '/tmp/'
        ];
        $this->bind()->annotatedWith(TwigOptions::class)->toInstance($options);
    }
}

```

And you put twig template file into the resource directory.

### Resource/Page/Index.html.twig

```twig
<h1>{{ greeting }}</h1>
```

Run your app, enjoy!
