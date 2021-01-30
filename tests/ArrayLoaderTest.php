<?php
/**
 * This file is part of the Madapaja.TwigModule package.
 *
 * @license http://opensource.org/licenses/MIT MIT
 */
namespace Madapaja\TwigModule;

use Madapaja\TwigModule\Exception\TemplateNotFound;
use Madapaja\TwigModule\Resource\Page\Index;
use Madapaja\TwigModule\Resource\Page\NoTemplate;
use Madapaja\TwigModule\Resource\Page\Page;
use PHPUnit\Framework\TestCase;
use Ray\Di\Injector;

class ArrayLoaderTest extends TestCase
{
    /**
     * @var Injector
     */
    private $injector;

    public function setUp(): void
    {
        $this->injector = new Injector(new TwigArrayLoaderTestModule);
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

    public function testIndexWithArg()
    {
        $ro = $this->injector->getInstance(Index::class);

        $this->assertSame('Hello, Madapaja!', (string) $ro->onGet('Madapaja'));
    }

    public function testTemplateNotFoundException()
    {
        $this->expectException(TemplateNotFound::class);
        $ro = $this->injector->getInstance(NoTemplate::class);
        $prop = (new \ReflectionClass($ro))->getProperty('renderer');
        $prop->setAccessible(true);
        $prop->getValue($ro)->render($ro);
    }

    public function testPage()
    {
        $ro = $this->injector->getInstance(Page::class);

        $this->assertSame('<!DOCTYPE html><html><head><title>Page</title><body>Hello, BEAR.Sunday!</body></html>', (string) $ro->onGet());
    }
}
