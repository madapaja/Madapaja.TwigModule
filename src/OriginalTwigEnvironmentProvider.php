<?php
/**
 * This file is part of the Madapaja.TwigModule package.
 *
 * @license http://opensource.org/licenses/MIT MIT
 */
namespace Madapaja\TwigModule;

use Ray\Di\Di\Named;
use Ray\Di\ProviderInterface;
use Twig_Environment;
use Twig_LoaderInterface;

class OriginalTwigEnvironmentProvider implements ProviderInterface
{
    private $loader;
    private $options = [];

    /**
     * @Named("loader=twig_loader,options=Madapaja\TwigModule\Annotation\TwigOptions")
     */
    public function __construct(Twig_LoaderInterface $loader, array $options = [])
    {
        $this->loader = $loader;
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
