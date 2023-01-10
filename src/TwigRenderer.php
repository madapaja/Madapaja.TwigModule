<?php

declare(strict_types=1);

namespace Madapaja\TwigModule;

use BEAR\Resource\Code;
use BEAR\Resource\RenderInterface;
use BEAR\Resource\ResourceObject;
use Madapaja\TwigModule\Annotation\TwigRedirectPath;
use Ray\Aop\WeavedInterface;
use ReflectionClass;
use Twig\Environment;
use Twig\Error\LoaderError;
use Twig\Loader\FilesystemLoader;
use Twig\TemplateWrapper;

use function in_array;
use function is_array;

class TwigRenderer implements RenderInterface
{
    /**
     * File extension
     */
    public const EXT = '.html.twig';

    /** @var Environment */
    public $twig;
    private TemplateFinderInterface|TemplateFinder $templateFinder;

    public function __construct(
        Environment $twig,
        #[TwigRedirectPath]
        private string $redirectPage,
        TemplateFinderInterface|null $templateFinder = null,
    ) {
        $this->twig = $twig;
        $this->templateFinder = $templateFinder ?: new TemplateFinder();
    }

    /**
     * {@inheritdoc}
     */
    public function render(ResourceObject $ro)
    {
        $this->setContentType($ro);

        if ($this->isNoContent($ro)) {
            $ro->view = '';

            return $ro->view;
        }

        if ($this->isRedirect($ro)) {
            $ro->view = $this->renderRedirectView($ro);

            return $ro->view;
        }

        $ro->view = $this->renderView($ro);

        return $ro->view;
    }

    private function setContentType(ResourceObject $ro): void
    {
        if (isset($ro->headers['Content-Type'])) {
            return;
        }

        $ro->headers['Content-Type'] = 'text/html; charset=utf-8';
    }

    private function renderView(ResourceObject $ro)
    {
        $template = $this->load($ro);

        return $template ? $template->render($this->buildBody($ro)) : '';
    }

    private function renderRedirectView(ResourceObject $ro)
    {
        try {
            return $this->twig->render($this->redirectPage, ['url' => $ro->headers['Location']]);
        } catch (LoaderError) {
            return '';
        }
    }

    private function load(ResourceObject $ro): TemplateWrapper|null
    {
        try {
            return $this->loadTemplate($ro);
        } catch (LoaderError $e) {
            if ($ro->code === 200) {
                throw new Exception\TemplateNotFound($e->getMessage(), 500, $e);
            }
        }

        return null;
    }

    private function isNoContent(ResourceObject $ro): bool
    {
        return $ro->code === Code::NO_CONTENT || $ro->view === '';
    }

    private function isRedirect(ResourceObject $ro): bool
    {
        return in_array($ro->code, [
            Code::MOVED_PERMANENTLY,
            Code::FOUND,
            Code::SEE_OTHER,
            Code::TEMPORARY_REDIRECT,
            Code::PERMANENT_REDIRECT,
        ], true) && isset($ro->headers['Location']);
    }

    private function loadTemplate(ResourceObject $ro): TemplateWrapper
    {
        $loader = $this->twig->getLoader();
        if ($loader instanceof FilesystemLoader) {
            $classFile = $this->getReflection($ro)->getFileName();
            $templateFile = ($this->templateFinder)($classFile);

            return $this->twig->load($templateFile);
        }

        return $this->twig->load($this->getReflection($ro)->name . self::EXT);
    }

    private function getReflection(ResourceObject $ro): ReflectionClass
    {
        if ($ro instanceof WeavedInterface) {
            return (new ReflectionClass($ro))->getParentClass();
        }

        return new ReflectionClass($ro);
    }

    private function buildBody(ResourceObject $ro): array
    {
        $body = is_array($ro->body) ? $ro->body : [];
        $body += ['_ro' => $ro];

        return $body;
    }
}
