<?php

declare(strict_types=1);

namespace Madapaja\TwigModule;

use BEAR\Resource\RenderInterface;
use BEAR\Resource\ResourceObject;
use Madapaja\TwigModule\Annotation\TwigErrorPath;
use Twig\Environment;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Error\SyntaxError;

use function assert;
use function is_array;

class ErrorPagerRenderer implements RenderInterface
{
    public function __construct(
        private Environment $twig,
        #[TwigErrorPath]
        private string $errorPage,
    ) {
    }

    /**
     * @throws LoaderError
     * @throws RuntimeError
     * @throws SyntaxError
     */
    public function render(ResourceObject $ro): string
    {
        assert(is_array($ro->body));
        $ro->view = $this->twig->render($this->errorPage, $ro->body);

        return $ro->view;
    }
}
