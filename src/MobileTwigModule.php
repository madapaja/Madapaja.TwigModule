<?php

namespace Madapaja\TwigModule;

use Ray\Di\AbstractModule;
use Ray\Di\Scope;

class MobileTwigModule extends AbstractModule
{
    /**
     * {@inheritdoc}
     */
    protected function configure()
    {
        $this->bind(TemplateFinderInterface::class)->to(MobileTemplateFinder::class)->in(Scope::SINGLETON);
    }
}
