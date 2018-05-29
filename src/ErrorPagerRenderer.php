<?php
/**
 * This file is part of the Madapaja.TwigModule package.
 *
 * @license http://opensource.org/licenses/MIT MIT
 */
namespace Madapaja\TwigModule;

use BEAR\Resource\RenderInterface;
use BEAR\Resource\ResourceObject;

class ErrorPagerRenderer implements RenderInterface
{
    /**
     * @var \Twig_Environment
     */
    private $twig;

    private $errorPage;

    public function __construct(\Twig_Environment $twig, string $errorPage = '/error/error.html.twig')
    {
        $this->twig = $twig;
        $this->errorPage = $errorPage;
    }

    /**
     * @return string
     * @throws \Twig_Error_Loader
     * @throws \Twig_Error_Runtime
     * @throws \Twig_Error_Syntax
     */
    public function render(ResourceObject $ro)
    {
        $ro->view = $this->twig->render($this->errorPage, $ro->body);

        return $ro->view;
    }
}
