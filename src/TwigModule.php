<?php
/**
 * This file is part of the Madapaja.TwigModule package.
 *
 * @license http://opensource.org/licenses/MIT MIT
 */
namespace Madapaja\TwigModule;

use BEAR\Resource\RenderInterface;
use Madapaja\TwigModule\Annotation\TwigLoader;
use Madapaja\TwigModule\Annotation\TwigOptions;
use Madapaja\TwigModule\Annotation\TwigPaths;
use Madapaja\TwigModule\Annotation\TwigRedirectPath;
use Ray\Di\AbstractModule;
use Ray\Di\Scope;
use Twig\Environment;
use Twig\Loader\FilesystemLoader;
use Twig\Loader\LoaderInterface;

class TwigModule extends AbstractModule
{
    /**
     * @var array
     */
    private $paths;

    /**
     * @var array
     */
    private $options;

    /**
     * @param array               $paths   Twig template paths
     * @param array               $options Twig_Environment options
     * @param AbstractModule|null $module
     *
     * @see http://twig.sensiolabs.org/api/master/Twig_Environment.html
     */
    public function __construct($paths = [], $options = [], AbstractModule $module = null)
    {
        $this->paths = $paths;
        $this->options = $options;
        parent::__construct($module);
    }

    /**
     * {@inheritdoc}
     */
    protected function configure()
    {
        $this->bindRender();
        $this->bindTwigLoader();
        $this->bindTwigEnvironment();
        $this->bindTwigPaths();
        $this->bindTwigOptions();
        $this->bindTwigRedirectPath();
    }

    private function bindRender()
    {
        $this->bind(RenderInterface::class)
             ->to(TwigRenderer::class)
             ->in(Scope::SINGLETON);
    }

    private function bindTwigLoader()
    {
        $this
            ->bind(LoaderInterface::class)
            ->annotatedWith(TwigLoader::class)
            ->toConstructor(
                FilesystemLoader::class,
                'paths=Madapaja\TwigModule\Annotation\TwigPaths'
            );
    }

    private function bindTwigEnvironment()
    {
        $this
            ->bind(Environment::class)
            ->annotatedWith('original')
            ->toConstructor(
                Environment::class,
                [
                    'loader' => TwigLoader::class,
                    'options' => TwigOptions::class
                ]
            );

        $this
            ->bind(Environment::class)
            ->toConstructor(
                Environment::class,
                [
                    'loader' => TwigLoader::class,
                    'options' => TwigOptions::class
                ]
            );
    }

    private function bindTwigPaths()
    {
        if ($this->isNotEmpty($this->paths)) {
            $this->bind()->annotatedWith(TwigPaths::class)->toInstance($this->paths);

            return;
        }
        $this->bind()->annotatedWith(TwigPaths::class)->toProvider(AppPathProvider::class);
    }

    private function bindTwigOptions()
    {
        if ($this->isNotEmpty($this->options)) {
            $this->bind()->annotatedWith(TwigOptions::class)->toInstance($this->options);

            return;
        }
        $this->bind()->annotatedWith(TwigOptions::class)->toProvider(OptionProvider::class);
    }

    private function bindTwigRedirectPath()
    {
        $this->bind()->annotatedWith(TwigRedirectPath::class)->toInstance('/redirect/redirect.html.twig');
    }

    private function isNotEmpty($var)
    {
        return \is_array($var) && ! empty($var);
    }
}
