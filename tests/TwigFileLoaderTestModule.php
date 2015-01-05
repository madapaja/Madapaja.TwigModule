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
        $this->install(new TwigModule([__DIR__ . '/Resource/']));
    }
}
