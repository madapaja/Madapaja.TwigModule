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

    /**
     * {@inheritdoc}
     */
    public function render(ResourceObject $ro)
    {
        $this->beforeRender($ro);
        $ro->view = $this->isNoContent($ro) ? '' : $this->renderView($ro);

        return $ro->view;
    }

    /**
     * @return void
     */
    private function beforeRender(ResourceObject $ro)
    {
        if (! isset($ro->headers['content-type'])) {
            $ro->headers['content-type'] = 'text/html; charset=utf-8';
        }
    }

    /**
     * @return void
     */
    private function renderView(ResourceObject $ro)
    {
        $template = $this->load($ro);

        return $template ? $template->render($this->buildBody($ro)) : '';
    }

    /**
     * @return bool|\Twig_Template
     */
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

    private function isNoContent(ResourceObject $ro) : bool
    {
        return $ro->code === Code::NO_CONTENT || $ro->view === '';
    }

    private function loadTemplate(ResourceObject $ro) : \Twig_Template
    {
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
    }

    private function getReflection(ResourceObject $ro) : \ReflectionClass
    {
        if ($ro instanceof WeavedInterface) {
            return (new \ReflectionClass($ro))->getParentClass();
        }

        return new \ReflectionClass($ro);
    }

    /**
     * return template file full path
     */
    private function getTemplatePath(ResourceObject $ro) : string
    {
        if (isset($ro->templatePath) && ! empty($ro->templatePath)) {
            return $ro->templatePath;
        }

        $file = $this->getReflection($ro)->getFileName();

        return $this->templateFinder->__invoke($file);
    }

    /**
     * return template path and directory
     */
    private function getTemplate(ResourceObject $ro, array $paths = []) : array
    {
        $file = $this->getTemplatePath($ro);

        foreach ($paths as $path) {
            if (strpos($file, $path . '/') === 0) {
                return [substr($file, strlen($path . '/')), null];
            }
        }

        return [basename($file), dirname($file)];
    }

    private function buildBody(ResourceObject $ro) : array
    {
        $body = is_array($ro->body) ? $ro->body : [];
        $body += ['_code' => $ro->code, '_headers' => $ro->headers];

        return $body;
    }
}
