# Madapaja.TwigModule

Madapaja.TwigModule is [Twig](http://twig.sensiolabs.org/) **v2** adaptor extension for [BEAR.Sunday](https://github.com/koriym/BEAR.Sunday) framework.

[![Latest Stable Version](https://poser.pugx.org/madapaja/twig-module/v/stable.svg)](https://packagist.org/packages/madapaja/twig-module)
[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/madapaja/Madapaja.TwigModule/badges/quality-score.png?b=2.x)](https://scrutinizer-ci.com/g/madapaja/Madapaja.TwigModule/?branch=2.x)
[![Code Coverage](https://scrutinizer-ci.com/g/madapaja/Madapaja.TwigModule/badges/coverage.png?b=2.x)](https://scrutinizer-ci.com/g/madapaja/Madapaja.TwigModule/?branch=2.x)
[![Build Status](https://travis-ci.org/madapaja/Madapaja.TwigModule.svg)](https://travis-ci.org/madapaja/Madapaja.TwigModule)
[![Total Downloads](https://poser.pugx.org/madapaja/twig-module/downloads.png)](https://packagist.org/packages/madapaja/twig-module)

# Installation

## Composer install

```
composer require madapaja/twig-module 2.x-dev
```

## Module Install

```php
namespace MyVendor\MyPackage\Module;

use Ray\Di\AbstractModule;

class HtmlModule extends AbstractModule
{
    protected function configure()
    {
        $this->install(new TwigModule);
    }
}
```

Place twig template file in the same directory of resource class or `var/lib/twig/` directory.

```twig
<h1>{{ greeting }}</h1>
```

For example, The template file for `Resource/Page/Index` class should be place either

 * `src/Resource/Page/Index.html.twig`
 
or

 * `var/lib/twig/Page/Index.html.twig`

## Extending Twig

You may want to extend twig with `addExtension()` method.
In that case, provide your own Twig Provider class.

```php
use Ray\Di\Di\Named;
use Ray\Di\ProviderInterface;

class MyTwigEnvironmentProvider implements ProviderInterface
{
    private $twig;

    /**
     * @Named("original")
     */
    public function __construct(\Twig_Environment $twig)
    {
        // $twig is an original \Twig_Environment instance
        $this->twig = $twig;
    }

    public function get()
    {
        // Extending Twig
        $this->twig->addExtension(new MyTwigExtension());

        return $this->twig;
    }
}
```

And bind it to `Twig_Environment` class.

```php
class HtmlModule extends AbstractModule
{
    protected function configure()
    {
        $this->install(new TwigModule());
        
        // override \Twig_Environment provider
        $this
            ->bind(\Twig_Environment::class)
            ->toProvider(MyTwigEnvironmentProvider::class)
            ->in(Scope::SINGLETON);
    }
}
```

## Options

You may want to specify custom path or options for Twig template engine.

```php
class HtmlModule extends AbstractModule
{
    protected function configure()
    {
        $appDir = dirname(dirname(__DIR__));
        $paths = [
             $appDir . '/src/Resource',
             $appDir . '/var/lib/twig'
        ];
        $options = [
            'debug' => true,
            'cache' => $appDir . '/tmp/twig'
        ];
        $this->install(new TwigModule($paths, $options));
    }
}
```
