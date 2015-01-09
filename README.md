Madapaja.TwigModule
===================

BEAR.Sunday Module

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
        "madapaja/twig-module": "dev-master"
    }
}
```

Usage
-----

For example you are using [BEAR.Package](https://github.com/koriym/BEAR.Package) ...

modifying your `AppModule` as:

```php
namespace YourVendor\YourApp\Module;

use Madapaja\TwigModule\TwigModule;

class AppModule extends AbstractModule
{
    protected function configure()
    {
        $appDir = dirname(dirname(__DIR__));
        $this->install(new TwigModule([$appDir . '/src/Resource']));

        // You can add twig template paths by the following
        // $this->install(new TwigModule([$appDir . '/src/Resource', $appDir . '/var/lib/twig']));

        // Or you can set environment options by 2nd argument
        // see: http://twig.sensiolabs.org/doc/api.html#environment-options
        // $this->install(new TwigModule([$appDir . '/src/Resource'], [
        //   'debug' => true,
        //   'cache' => '/tmp/'
        // ]));

        // (Existing configurations here)
    }
}
```

And you put twig template file into the resource directory.

### Resource/Page/Index.html.twig

```twig
<h1>{{ greeting }}</h1>
```

Run your app, enjoy!
