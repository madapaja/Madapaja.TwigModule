<?php

namespace Madapaja\TwigModule;

use Ray\Di\AbstractModule;

class TwigFileLoaderTestModule extends AbstractModule
{
    /**
     * {@inheritdoc}
     */
    protected function configure()
    {
        $this->install(new TwigModule);
        $this->bind()->annotatedWith('twig_paths')->toInstance([__DIR__ . '/Resource/']);
    }
}
