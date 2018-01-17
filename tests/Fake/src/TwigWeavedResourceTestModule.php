<?php
/**
 * This file is part of the Madapaja.TwigModule package.
 *
 * @license http://opensource.org/licenses/MIT MIT
 */
namespace Madapaja\TwigModule;

use BEAR\Resource\ResourceObject;
use Ray\Di\AbstractModule;

class TwigWeavedResourceTestModule extends AbstractModule
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
        $this->bindInterceptor(
            $this->matcher->subclassesOf(ResourceObject::class),
            $this->matcher->startsWith('on'),
            [EmptyInterceptor::class]
        );
    }
}
