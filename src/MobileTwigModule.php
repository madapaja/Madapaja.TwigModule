<?php

declare(strict_types=1);

namespace Madapaja\TwigModule;

use Ray\Di\AbstractModule;

/**
 * Provides TemplateFinderInterface and derived bindings
 *
 * Module for finding mobile templates.
 *
 * The following bindings are provided:
 *
 *  TemplateFinderInterface
 */
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
