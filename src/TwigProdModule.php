<?php

declare(strict_types=1);

namespace Madapaja\TwigModule;

use BEAR\Sunday\Compile\CompileStepInterface;
use Madapaja\TwigModule\Annotation\TwigOptions;
use Ray\Di\AbstractModule;
use Ray\Di\MultiBinder;

/**
 * Moves the Twig cache from the temporary directory to the build directory
 *
 * Install in the production context module, the one chained over the application module, so that these
 * bindings take priority over TwigModule's.
 *
 * An application that binds TwigOptions itself, through the TwigModule constructor or with
 * annotatedWith(TwigOptions::class)->toInstance(), keeps managing its own cache and is unaffected.
 */
class TwigProdModule extends AbstractModule
{
    /**
     * {@inheritDoc}
     */
    protected function configure()
    {
        $this->bind()->annotatedWith(TwigOptions::class)->toProvider(ProdOptionProvider::class);
        $this->bind(TemplateNames::class);
        MultiBinder::newInstance($this, CompileStepInterface::class)
            ->addBinding(TwigCompileStep::NAME)
            ->to(TwigCompileStep::class);
    }
}
