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
        $this->setContentType($ro);
        $ro->view = $this->isNoContent($ro) ? '' : $this->renderView($ro);

        return $ro->view;
    }

    private function setContentType(ResourceObject $ro)
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

    /**
     * @return null|\Twig_TemplateWrapper
     */
    private function load(ResourceObject $ro)
    {
        try {
            return $this->loadTemplate($ro);
        } catch (\Twig_Error_Loader $e) {
            if ($ro->code !== 200) {
                return null;
            }
        }

        throw new Exception\TemplateNotFound($e->getMessage(), 500, $e);
    }

    private function isNoContent(ResourceObject $ro) : bool
    {
        return $ro->code === Code::NO_CONTENT || $ro->view === '';
    }

    private function loadTemplate(ResourceObject $ro) : \Twig_TemplateWrapper
    {
        $loader = $this->twig->getLoader();
        if ($loader instanceof \Twig_Loader_Filesystem) {
            $classFile = $this->getReflection($ro)->getFileName();
            $templateFile = $this->templateFinder->__invoke($classFile);

            return $this->twig->load($templateFile);
        }

        return $this->twig->load($this->getReflection($ro)->name . self::EXT);
    }

    private function getReflection(ResourceObject $ro) : \ReflectionClass
    {
        if ($ro instanceof WeavedInterface) {
            return (new \ReflectionClass($ro))->getParentClass();
        }

        return new \ReflectionClass($ro);
    }

    private function buildBody(ResourceObject $ro) : array
    {
        $body = is_array($ro->body) ? $ro->body : [];
        $body += ['_ro' => $ro,];

        return $body;
    }
}
