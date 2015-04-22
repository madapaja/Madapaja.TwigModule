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
    /**
     * @var \Twig_Environment
     */
    public $twig;

    /**
     * @var TemplateFinder
     */
    private $templateFinder;

    /**
     * @param \Twig_Environment $twig
     */
    public function __construct(\Twig_Environment $twig, TemplateFinder $templateFinder = null)
    {
        $this->twig = $twig;
        $this->templateFinder = $templateFinder ?: new TemplateFinder;
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

    /**
     * @param ResourceObject $ro
     * @return \Twig_TemplateInterface
     */
    private function loadTemplate(ResourceObject $ro)
    {
        try {
            $loader = $this->twig->getLoader();
            if ($loader instanceof \Twig_Loader_Filesystem) {
                list($file, $dir) = $this->getTemplate($ro, $loader->getPaths());
                if ($dir) {
                    // if the file not in paths, register the directory
                    $loader->prependPath($dir);
                }

                return $this->twig->loadTemplate($file);
            }
            return $this->twig->loadTemplate($this->getReflection($ro)->name . self::EXT);
        } catch (\Twig_Error_Loader $e) {
            throw new Exception\TemplateNotFound($e->getMessage());
        }
    }

    /**
     * @param ResourceObject $ro
     * @return \ReflectionClass
     */
    private function getReflection(ResourceObject $ro)
    {
        if ($ro instanceof WeavedInterface) {
            return (new \ReflectionClass($ro))->getParentClass();
        }

        return (new \ReflectionClass($ro));
    }

    /**
     * return template file full path
     *
     * @param ResourceObject $ro
     * @return string
     */
    private function getTemplatePath(ResourceObject $ro)
    {
        $file = $this->getReflection($ro)->getFileName();

        return $this->templateFinder->__invoke($file);
    }

    /**
     * return template path and directory
     *
     * @param ResourceObject $ro
     * @param array $paths
     * @return array
     */
    private function getTemplate(ResourceObject $ro, array $paths = [])
    {
        $file = $this->getTemplatePath($ro);

        foreach ($paths as $path) {
            if (strpos($file, $path . '/') === 0) {
                return [substr($file, strlen($path . '/')), null];
            }
        }

        return [basename($file), dirname($file)];
    }
}
