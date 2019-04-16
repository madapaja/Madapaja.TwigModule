<?php
/**
 * This file is part of the Madapaja.TwigModule package.
 *
 * @license http://opensource.org/licenses/MIT MIT
 */
namespace Madapaja\TwigModule;

use Ray\Di\Di\Named;
use Ray\Di\ProviderInterface;
use Twig\Environment;

class ExtendedTwigEnvironmentProvider implements ProviderInterface
{
    private $twig;

    /**
     * @Named("original")
     * @param Environment $twig
     */
    public function __construct(Environment $twig)
    {
        // $twig is an original Twig\Environment instance
        $this->twig = $twig;
    }

    public function get()
    {
        $this->twig->addExtension(new MyTwigExtension());

        return $this->twig;
    }
}
