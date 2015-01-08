<?php

namespace Madapaja\TwigModule\Resource\Page;

use Madapaja\TwigModule\TwigWeavedResourceTestModule;
use Madapaja\TwigModule\TwigRenderer;
use PHPUnit_Framework_TestCase;
use Ray\Di\Injector;

class WeavedResourceTest extends PHPUnit_Framework_TestCase
{
    /**
     * @var Injector
     */
    private $injector;

    public function setUp()
    {
        $this->injector = new Injector(new TwigWeavedResourceTestModule([$_ENV['TEST_DIR'] . '/Resource/']));
    }

    public function testRenderer()
    {
        $ro = $this->injector->getInstance(Index::class);
        $prop = (new \ReflectionClass($ro))->getProperty('renderer');
        $prop->setAccessible(true);

        $this->assertInstanceOf(TwigRenderer::class, $prop->getValue($ro));
    }

    public function testIndex()
    {
        $ro = $this->injector->getInstance(Index::class);

        $this->assertSame('Hello, BEAR.Sunday!', (string) $ro->onGet());
    }
}
