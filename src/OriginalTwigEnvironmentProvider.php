<?php

namespace Madapaja\TwigModule;

use Madapaja\TwigModule\Annotation\TwigOptions;
use Ray\Di\Di\Inject;
use Ray\Di\Di\Named;
use Ray\Di\ProviderInterface;
use Twig_LoaderInterface;
use Twig_Environment;

class OriginalTwigEnvironmentProvider implements ProviderInterface
{
    private $loader;
    private $options = [];

    /**
     * @Named("twig_loader")
     */
    public function __construct(Twig_LoaderInterface $loader)
    {
        $this->loader = $loader;
    }

    /**
     * @Inject
     * @TwigOptions
     */
    public function setOptions(array $options = [])
    {
        $this->options = $options;
    }

    /**
     * @return Twig_Environment
     */
    public function get()
    {
        return new Twig_Environment($this->loader, $this->options);
    }
}
