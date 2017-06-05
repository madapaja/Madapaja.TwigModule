<?php

namespace Madapaja\TwigModule;

use Ray\Di\Di\Named;
use Ray\Di\ProviderInterface;
use Twig_Environment;

class TwigEnvironmentProvider implements ProviderInterface
{
    public $twig;

    /**
     * @Named("original")
     * @param Twig_Environment $twig
     */
    public function __construct(Twig_Environment $twig)
    {
        $this->twig = $twig;
    }

    /**
     * @return Twig_Environment
     */
    public function get()
    {
        return $this->twig;
    }
}
