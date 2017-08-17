<?php
/**
 * This file is part of the Madapaja.TwigModule package.
 *
 * @license http://opensource.org/licenses/MIT MIT
 */
namespace Madapaja\TwigModule;

use BEAR\Resource\Code;
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
     * @var Twig_Environment
     */
    public $twig;

    /**
     * @var TemplateFinderInterface
     */
    private $templateFinder;

    public function __construct(Twig_Environment $twig, TemplateFinderInterface $templateFinder = null)
    {
        $this->twig = $twig;
        $this->templateFinder = $templateFinder ?: new TemplateFinder;
    }

    public function render(ResourceObject $ro)
    {
        $this->beforeRender($ro);
        $ro->view = $this->isNoContent($ro) ? '' : $this->renderView($ro);

        return $ro->view;
    }

    private function beforeRender(ResourceObject $ro)
    {
        if (! isset($ro->headers['content-type'])) {
            $ro->headers['content-type'] = 'text/html; charset=utf-8';
        }
    }

    private function renderView(ResourceObject $ro)
    {
        $template = $this->load($ro);

        return $template ? $template->render($this->buildBody($ro)) : '';
    }

    private function load(ResourceObject $ro)
    {
        try {
            return $this->loadTemplate($ro);
        } catch (\Twig_Error_Loader $e) {
            if ($ro->code !== 200) {
                return false;
            }
        }

        throw new Exception\TemplateNotFound($e->getMessage(), 500, $e);
    }

    private function isNoContent(ResourceObject $ro)
    {
        return $ro->code === Code::NO_CONTENT || $ro->view === '';
    }

    /**
     * @return \Twig_TemplateWrapper
     */
    private function loadTemplate(ResourceObject $ro)
    {
        $loader = $this->twig->getLoader();
        if ($loader instanceof \Twig_Loader_Filesystem) {
            list($file, $dir) = $this->getTemplate($ro, $loader->getPaths());
            if ($dir) {
                // if the file not in paths, register the directory
                $loader->prependPath($dir);
            }

            return $this->twig->load($file);
        }

        return $this->twig->load($this->getReflection($ro)->name . self::EXT);
    }

    /**
     * @return \ReflectionClass
     */
    private function getReflection(ResourceObject $ro)
    {
        if ($ro instanceof WeavedInterface) {
            return (new \ReflectionClass($ro))->getParentClass();
        }

        return new \ReflectionClass($ro);
    }

    /**
     * return template file full path
     *
     * @return string
     */
    private function getTemplatePath(ResourceObject $ro)
    {
        if (isset($ro->templatePath)) {
            return $ro->templatePath;
        }

        $file = $this->getReflection($ro)->getFileName();

        return $this->templateFinder->__invoke($file);
    }

    /**
     * return template path and directory
     *
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

    private function buildBody(ResourceObject $ro)
    {
        $body = is_array($ro->body) ? $ro->body : [];
        $body += ['_code' => $ro->code, '_headers' => $ro->headers];

        return $body;
    }
}
