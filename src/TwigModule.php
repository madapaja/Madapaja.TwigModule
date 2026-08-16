<?php

declare(strict_types=1);

namespace Madapaja\TwigModule;

use BEAR\Resource\RenderInterface;
use Madapaja\TwigModule\Annotation\TwigLoader;
use Madapaja\TwigModule\Annotation\TwigOptions;
use Madapaja\TwigModule\Annotation\TwigPaths;
use Madapaja\TwigModule\Annotation\TwigRedirectPath;
use Madapaja\TwigModule\Annotation\TwigRootPath;
use Ray\Di\AbstractModule;
use Ray\Di\Scope;
use Twig\Environment;
use Twig\Loader\FilesystemLoader;
use Twig\Loader\LoaderInterface;

/**
 * Provides Twig and derived bindings
 *
 * The following bindings are provided:
 *
 * LoaderInterface
 * Environment
 * ::TwigPaths
 * ::TwigRedirectPath
 * ::TwigOptions
 * ::TwigRootPath
 * /
 *
 * @SuppressWarnings(PHPMD.CouplingBetweenObjects)
 */
class TwigModule extends AbstractModule
{
    /**
     * @param array<string>       $paths   Twig template paths
     * @param array<mixed>        $options Twig_Environment options
     * @param AbstractModule|null $module
     *
     * @see http://twig.sensiolabs.org/api/master/Twig_Environment.html
     */
    public function __construct(
        private readonly array $paths = [],
        private readonly array $options = [],
        AbstractModule|null $module = null,
    ) {
        parent::__construct($module);
    }

    /**
     * {@inheritDoc}
     */
    protected function configure()
    {
        $this->bindRender();
        $this->bindTwigLoader();
        $this->bindTwigEnvironment();
        $this->bindTwigPaths();
        $this->bindTwigOptions();
        $this->bindTwigRootPath();
        $this->bindTwigRedirectPath();
    }

    private function bindRender(): void
    {
        $this->bind(RenderInterface::class)
             ->to(TwigRenderer::class)
             ->in(Scope::SINGLETON);
    }

    private function bindTwigLoader(): void
    {
        $this
            ->bind(LoaderInterface::class)
            ->annotatedWith(TwigLoader::class)
            ->toConstructor(
                FilesystemLoader::class,
                [
                    'paths' => TwigPaths::class,
                    'rootPath' => TwigRootPath::class,
                ],
            );
    }

    private function bindTwigEnvironment(): void
    {
        $this
            ->bind(Environment::class)
            ->annotatedWith('original')
            ->toConstructor(
                Environment::class,
                [
                    'loader' => TwigLoader::class,
                    'options' => TwigOptions::class,
                ],
            );

        $this
            ->bind(Environment::class)
            ->toConstructor(
                Environment::class,
                [
                    'loader' => TwigLoader::class,
                    'options' => TwigOptions::class,
                ],
            );
    }

    private function bindTwigPaths(): void
    {
        if (! empty($this->paths)) {
            $this->bind()->annotatedWith(TwigPaths::class)->toInstance($this->paths);

            return;
        }

        $this->bind()->annotatedWith(TwigPaths::class)->toProvider(AppPathProvider::class);
    }

    private function bindTwigOptions(): void
    {
        if (! empty($this->options)) {
            $this->bind()->annotatedWith(TwigOptions::class)->toInstance($this->options);

            return;
        }

        $this->bind()->annotatedWith(TwigOptions::class)->toProvider(OptionProvider::class);
    }

    private function bindTwigRootPath(): void
    {
        $this->bind()->annotatedWith(TwigRootPath::class)->toProvider(AppRootPathProvider::class);
    }

    private function bindTwigRedirectPath(): void
    {
        $this->bind()->annotatedWith(TwigRedirectPath::class)->toInstance('/redirect/redirect.html.twig');
    }
}
