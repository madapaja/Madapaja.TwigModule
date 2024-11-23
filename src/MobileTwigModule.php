<?php

declare(strict_types=1);

namespace Madapaja\TwigModule;

use Detection\MobileDetect;
use Ray\Di\AbstractModule;
use RuntimeException;

use function class_exists;

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
    public function __construct(AbstractModule $module)
    {
        if (! class_exists(MobileDetect::class)) {
            throw new RuntimeException('mobiledetect/mobiledetectlib is required for MobileTwigModule, please install it via composer. (composer require mobiledetect/mobiledetectlib)'); // @codeCoverageIgnore
        }

        parent::__construct($module);
    }

    /**
     * {@inheritDoc}
     */
    protected function configure()
    {
        $this->bind(TemplateFinderInterface::class)->to(MobileTemplateFinder::class);
        $this->bind(TemplateFinderInterface::class)->annotatedWith('original')->to(TemplateFinder::class);
    }
}
