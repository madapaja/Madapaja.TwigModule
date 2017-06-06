<?php

namespace Madapaja\TwigModule;

use Ray\Di\Di\Named;
use Ray\Di\ProviderInterface;
use Twig_Environment;

class TwigEnvironmentProvider implements ProviderInterface
{
    private $twig;

    /**
     * @param Twig_Environment $twig
     *
     * @Named("original")
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
