<?php
/**
 * This file is part of the Madapaja.TwigModule package.
 *
 * @license http://opensource.org/licenses/MIT MIT
 */
namespace Madapaja\TwigModule;

use Ray\Di\AbstractModule;

class TwigFileLoaderTestModule extends AbstractModule
{
    public function __construct(private readonly array $paths = [], private readonly array $options = [])
    {
        parent::__construct();
    }

    /**
     * {@inheritdoc}
     */
    protected function configure()
    {
        $this->install(new TwigModule($this->paths, $this->options));
    }
}
