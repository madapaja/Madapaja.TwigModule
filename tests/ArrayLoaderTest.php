<?php

declare(strict_types=1);

namespace Madapaja\TwigModule;

use Madapaja\TwigModule\Exception\TemplateNotFound;
use Madapaja\TwigModule\Resource\Page\Index;
use Madapaja\TwigModule\Resource\Page\NoTemplate;
use Madapaja\TwigModule\Resource\Page\Page;
use PHPUnit\Framework\TestCase;
use Ray\Di\Injector;
use ReflectionClass;

class ArrayLoaderTest extends TestCase
{
    private Injector $injector;

    public function setUp(): void
    {
        $this->injector = new Injector(new TwigArrayLoaderTestModule());
    }

    public function testRenderer(): void
    {
        $ro = $this->injector->getInstance(Index::class);
        $prop = (new ReflectionClass($ro))->getProperty('renderer');
        $prop->setAccessible(true);

        $this->assertInstanceOf(TwigRenderer::class, $prop->getValue($ro));
    }

    public function testIndex(): void
    {
        $ro = $this->injector->getInstance(Index::class);

        $this->assertSame('Hello, BEAR.Sunday!', (string) $ro->onGet());
    }

    public function testIndexWithArg(): void
    {
        $ro = $this->injector->getInstance(Index::class);

        $this->assertSame('Hello, Madapaja!', (string) $ro->onGet('Madapaja'));
    }

    public function testTemplateNotFoundException(): void
    {
        $this->expectException(TemplateNotFound::class);
        $ro = $this->injector->getInstance(NoTemplate::class);
        $prop = (new ReflectionClass($ro))->getProperty('renderer');
        $prop->setAccessible(true);
        $prop->getValue($ro)->render($ro);
    }

    public function testPage(): void
    {
        $ro = $this->injector->getInstance(Page::class);

        $this->assertSame('<!DOCTYPE html><html><head><title>Page</title><body>Hello, BEAR.Sunday!</body></html>', (string) $ro->onGet());
    }
}
