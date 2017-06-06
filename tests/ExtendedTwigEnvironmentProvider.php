<?php

namespace Madapaja\TwigModule;

use Ray\Di\Di\Named;
use Ray\Di\ProviderInterface;
use Twig_Environment;

class ExtendedTwigEnvironmentProvider implements ProviderInterface
{
    private $twig;

    /**
     * @Named("original")
     */
    public function __construct(Twig_Environment $twig)
    {
        // $twig is an original Twig_Environment instance
        $this->twig = $twig;
    }

    public function get()
    {
        $this->twig->addExtension(new MyTwigExtension());

        return $this->twig;
    }
}
