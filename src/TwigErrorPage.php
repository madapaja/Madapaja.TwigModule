<?php
/**
 * This file is part of the Madapaja.TwigModule package.
 *
 * @license http://opensource.org/licenses/MIT MIT
 */
namespace Madapaja\TwigModule;

use BEAR\AppMeta\AbstractAppMeta;
use BEAR\Resource\RenderInterface;
use BEAR\Resource\ResourceObject;

class TwigErrorPage extends ResourceObject
{
    /**
     * @var array
     */
    public $headers = [
        'content-type' => 'text/html; charset=utf-8'
    ];

    /**
     * @var \Twig_Environment
     */
    private $twig;

    public function __construct(\Twig_Environment $twig)
    {
        $this->twig = $twig;
        $this->setRenderer(new class($twig) implements RenderInterface
        {
            /**
             * @var \Twig_Environment
             */
            private $twig;

            /**
             * @var string
             */
            private $errorTemplateDir;

            public function __construct(\Twig_Environment $twig)
            {
                $this->twig = $twig;
            }

            public function render(ResourceObject $ro)
            {
                $template = "error.{$ro->code}.html.twig";
                try {
                    $ro->view = $this->twig->render($template, $ro->body);
                } catch (\Twig_Error_Loader $e) {
                    $ro->view = $this->twig->render('/error.html.twig', $ro->body);
                }

                return $ro->view;
            }
        });
    }
}
