<?php

namespace Madapaja\TwigModule;

use BEAR\Resource\RenderInterface;
use BEAR\Resource\ResourceObject;

class ErrorPagerRenderer implements RenderInterface
{
    /**
     * @var \Twig_Environment
     */
    private $twig;

    public function __construct(\Twig_Environment $twig)
    {
        $this->twig = $twig;
    }

    /**
     * @return string
     *
     * @throws \Twig_Error_Loader
     * @throws \Twig_Error_Runtime
     * @throws \Twig_Error_Syntax
     */
    public function render(ResourceObject $ro)
    {
        $template = "error/error.{$ro->code}.html.twig";
        try {
            $ro->view = $this->twig->render($template, $ro->body);
        } catch (\Twig_Error_Loader $e) {
            $ro->view = $this->twig->render('/error/error.html.twig', $ro->body);
        }

        return $ro->view;
    }
}
