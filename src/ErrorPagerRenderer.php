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
use Twig\Environment;

class ErrorPagerRenderer implements RenderInterface
{
    public function __construct(private Environment $twig, #[\Madapaja\TwigModule\Annotation\TwigErrorPath]private string $errorPage)
    {
    }

    /**
     * @throws \Twig\Error\LoaderError
     * @throws \Twig\Error\RuntimeError
     * @throws \Twig\Error\SyntaxError
     *
     * @return string
     */
    public function render(ResourceObject $ro)
    {
        $ro->view = $this->twig->render($this->errorPage, $ro->body);

        return $ro->view;
    }
}
