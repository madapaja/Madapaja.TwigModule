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
    /**
     * @Named("original")
     */
    #[Named('original')]
    public function __construct(private Environment $twig)
    {
    }

    public function get()
    {
        $this->twig->addExtension(new MyTwigExtension());

        return $this->twig;
    }
}
