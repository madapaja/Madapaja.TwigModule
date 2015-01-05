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
class TwigModule extends AbstractModule
{
    /**
     * Configure dependency binding
     *
     * @return void
     */
    protected function configure()
    {
        $this->bind(RenderInterface::class)->to(TwigRenderer::class);

        $this->bind()->annotatedWith('twig_paths')->toInstance([]);

        $this
            ->bind(Twig_LoaderInterface::class)
            ->annotatedWith('twig_loader')
            ->toConstructor(
                Twig_Loader_Filesystem::class,
                'paths=twig_paths'
            );

        $this->bind()->annotatedWith('twig_options')->toInstance([]);

        $this
            ->bind(Twig_Environment::class)
            ->toConstructor(
                Twig_Environment::class,
                'loader=twig_loader,options=twig_options'
            )
            ->in(Scope::SINGLETON);
    }
}
