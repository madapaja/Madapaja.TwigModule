<?php
/**
 * This file is part of the Madapaja.TwigModule package.
 *
 * @license http://opensource.org/licenses/MIT MIT
 */
namespace Madapaja\TwigModule;

use BEAR\Resource\RenderInterface;
use BEAR\Resource\ResourceObject;
use Madapaja\TwigModule\Annotation\TwigErrorPath;

class ErrorPagerRenderer implements RenderInterface
{
    /**
     * @var \Twig_Environment
     */
    private $twig;

    private $errorPage;

    /**
     * @TwigErrorPath("errorPage")
     */
    public function __construct(\Twig_Environment $twig, string $errorPage)
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
