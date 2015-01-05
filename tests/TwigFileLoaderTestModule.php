<?php

namespace Madapaja\TwigModule;

use Ray\Di\AbstractModule;
use Ray\Di\Scope;
use Twig_LoaderInterface;
use Twig_Loader_Filesystem;
use Twig_Environment;
use BEAR\Resource\RenderInterface;

/**
 * Twig module
 */
class TwigFileLoaderTestModule extends AbstractModule
{
    /**
     * Configure dependency binding
     *
     * @return void
     */
    protected function configure()
    {
        $this->install(new TwigModule);
        $this->bind()->annotatedWith('twig_paths')->toInstance([__DIR__ . '/Resource/']);
    }
}
