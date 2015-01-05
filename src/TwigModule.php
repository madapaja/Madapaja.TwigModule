<?php

namespace Madapaja\TwigModule;

use Ray\Di\AbstractModule;
use Ray\Di\Scope;
use Twig_LoaderInterface;
use Twig_Loader_Filesystem;
use Twig_Environment;
use BEAR\Resource\RenderInterface;

class TwigModule extends AbstractModule
{
    /**
     * @var array
     */
    private $paths;

    /**
     * @var array
     */
    private $options;

    /**
     * @param array $paths   Twig template paths
     * @param array $options Twig_Environment options
     *
     * @see http://twig.sensiolabs.org/api/master/Twig_Environment.html
     */
    public function __construct($paths = [], $options = [])
    {
        $this->paths = $paths;
        $this->options = $options;
    }

    /**
     * {@inheritdoc}
     */
    protected function configure()
    {
        $this->bind(RenderInterface::class)->to(TwigRenderer::class);
        $this->bind()->annotatedWith('twig_paths')->toInstance($this->paths);
        $this->bind()->annotatedWith('twig_options')->toInstance($this->options);
        $this
            ->bind(Twig_LoaderInterface::class)
            ->annotatedWith('twig_loader')
            ->toConstructor(
                Twig_Loader_Filesystem::class,
                'paths=twig_paths'
            );
        $this
            ->bind(Twig_Environment::class)
            ->toConstructor(
                Twig_Environment::class,
                'loader=twig_loader,options=twig_options'
            )
            ->in(Scope::SINGLETON);
    }
}
