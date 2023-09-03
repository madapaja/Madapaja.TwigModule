<?php

declare(strict_types=1);

namespace Madapaja\TwigModule;

use Ray\Di\AbstractModule;

class MobileTwigModule extends AbstractModule
{
    /**
     * {@inheritDoc}
     */
    protected function configure()
    {
        $this->bind(TemplateFinderInterface::class)->to(MobileTemplateFinder::class);
        $this->bind(TemplateFinderInterface::class)->annotatedWith('original')->to(TemplateFinder::class);
    }
}
