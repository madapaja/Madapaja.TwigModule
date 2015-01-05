<?php

namespace Madapaja\TwigModule;

use BEAR\Resource\RenderInterface;
use BEAR\Resource\ResourceObject;
use Ray\Aop\WeavedInterface;
use Twig_Environment;

class TwigRenderer implements RenderInterface
{
    /**
     * File extension
     *
     * @var string
     */
    const EXT = '.html.twig';

    /**
     * @var \Twig_Environment
     */
    public $twig;

    /**
     * @param \Twig_Environment $twig
     */
    public function __construct(\Twig_Environment $twig)
    {
        $this->twig = $twig;
    }

    /**
     * {@inheritdoc}
     */
    public function render(ResourceObject $ro)
    {
        if (!isset($ro->headers['content-type'])) {
            $ro->headers['content-type'] = 'text/html; charset=utf-8';
        }
        $template = $this->loadTemplate($ro);
        $ro->view = $template->render($ro->body);

        return $ro->view;
    }

    private function loadTemplate(ResourceObject $ro)
    {
        try {
            if ($this->twig->getLoader() instanceof \Twig_Loader_Filesystem) {
                $path = $this->getTemplatePath($ro);
                $this->twig->getLoader()->prependPath(dirname($path));

                return $this->twig->loadTemplate(basename($path));
            }

            return $this->twig->loadTemplate($this->getReflection($ro)->name . self::EXT);
        } catch (\Twig_Error_Loader $e) {
            throw new Exception\TemplateNotFound($e->getMessage());
        }
    }

    /**
     * @param ResourceObject $ro
     *
     * @return \ReflectionClass
     */
    private function getReflection(ResourceObject $ro)
    {
        if ($ro instanceof WeavedInterface) {
            return (new \ReflectionClass($ro))->getParentClass();
        }

        return (new \ReflectionClass($ro));
    }

    private function getTemplatePath(ResourceObject $ro)
    {
        return $this->changeExtension($this->getReflection($ro)->getFileName());
    }

    private function changeExtension($name, $from = '.php', $to = self::EXT)
    {
        $pos = strrpos($name, $from);

        return substr($name, 0, $pos) . $to;
    }
}
