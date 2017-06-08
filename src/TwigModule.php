<?php
/**
 * This file is part of the Madapaja.TwigModule package.
 *
 * @license http://opensource.org/licenses/MIT MIT
 */
namespace Madapaja\TwigModule;

use BEAR\Resource\RenderInterface;
use Doctrine\Common\Annotations\AnnotationRegistry;
use Madapaja\TwigModule\Annotation\TwigOptions;
use Madapaja\TwigModule\Annotation\TwigPaths;
use Ray\Di\AbstractModule;
use Ray\Di\Scope;
use Twig_Environment;
use Twig_Loader_Filesystem;
use Twig_LoaderInterface;

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
     * @param array $paths   Twig template paths
     * @param array $options Twig_Environment options
     *
     * @see http://twig.sensiolabs.org/api/master/Twig_Environment.html
     */
    public function __construct($paths = [], $options = [])
    {
        $this->paths = $paths;
        $this->options = $options;

        parent::__construct();
    }

    /**
     * {@inheritdoc}
     */
    protected function configure()
    {
        AnnotationRegistry::registerFile(__DIR__ . '/DoctrineAnnotations.php');

        $this->bindRender();
        $this->bindTwigLoader();
        $this->bindTwigEnvironment();
        $this->bindTwigPaths();
        $this->bindTwigOptions();
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
            ->bind(Twig_LoaderInterface::class)
            ->annotatedWith('twig_loader')
            ->toConstructor(
                Twig_Loader_Filesystem::class,
                'paths=Madapaja\TwigModule\Annotation\TwigPaths'
            );
    }

    private function bindTwigEnvironment()
    {
        $this
            ->bind(Twig_Environment::class)
            ->annotatedWith('original')
            ->toProvider(OriginalTwigEnvironmentProvider::class)
            ->in(Scope::SINGLETON);

        $this
            ->bind(Twig_Environment::class)
            ->toProvider(TwigEnvironmentProvider::class)
            ->in(Scope::SINGLETON);
    }

    private function bindTwigPaths()
    {
        if ($this->isNotEmpty($this->paths)) {
            $this->bind()->annotatedWith(TwigPaths::class)->toInstance($this->paths);
        }
    }

    private function bindTwigOptions()
    {
        if ($this->isNotEmpty($this->options)) {
            $this->bind()->annotatedWith(TwigOptions::class)->toInstance($this->options);
        }
    }

    private function isNotEmpty($var)
    {
        return is_array($var) && ! empty($var);
    }
}
