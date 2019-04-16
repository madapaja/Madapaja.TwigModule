<?php
/**
 * This file is part of the Madapaja.TwigModule package.
 *
 * @license http://opensource.org/licenses/MIT MIT
 */
namespace Madapaja\TwigModule;

use Ray\Di\AbstractModule;
use Ray\Di\Scope;
use Twig\Environment;

class TwigExtensionTestModule extends AbstractModule
{
    private $paths;

    public function __construct(array $paths = [])
    {
        $this->paths = $paths;
        parent::__construct();
    }

    /**
     * {@inheritdoc}
     */
    protected function configure()
    {
        $this->install(new TwigModule($this->paths));
        $this
            ->bind(Environment::class)
            ->toProvider(ExtendedTwigEnvironmentProvider::class)
            ->in(Scope::SINGLETON);
    }
}
