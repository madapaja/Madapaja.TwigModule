<?php

namespace Madapaja\TwigModule;

use BEAR\Resource\RenderInterface;
use Doctrine\Common\Annotations\AnnotationRegistry;
use Madapaja\TwigModule\Annotation\TwigOptions;
use Madapaja\TwigModule\Annotation\TwigPaths;
use Ray\Di\AbstractModule;
use Ray\Di\Scope;
use Twig_Environment;
use Twig_Loader_Filesystem;
use Twig_LoaderInterface;

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
        AnnotationRegistry::registerFile(__DIR__ . '/DoctrineAnnotations.php');
        $this->bind(RenderInterface::class)->to(TwigRenderer::class)->in(Scope::SINGLETON);
        if ($this->paths) {
            $this->bind()->annotatedWith(TwigPaths::class)->toInstance($this->paths);
            $this->bind()->annotatedWith(TwigOptions::class)->toInstance($this->options);
        }
        $this
            ->bind(Twig_LoaderInterface::class)
            ->annotatedWith('twig_loader')
            ->toConstructor(
                Twig_Loader_Filesystem::class,
                'paths=Madapaja\TwigModule\Annotation\TwigPaths'
            );
        $this
            ->bind(Twig_Environment::class)
            ->toConstructor(
                Twig_Environment::class,
                'loader=twig_loader,options=Madapaja\TwigModule\Annotation\TwigOptions'
            )
            ->in(Scope::SINGLETON);
    }
}
