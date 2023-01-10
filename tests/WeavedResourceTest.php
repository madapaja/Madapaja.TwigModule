<?php

declare(strict_types=1);

/**
 * This file is part of the Madapaja.TwigModule package.
 */

namespace Madapaja\TwigModule;

use Madapaja\TwigModule\Resource\Page\Index;
use PHPUnit\Framework\TestCase;
use Ray\Di\Injector;
use ReflectionClass;

use function trim;

class WeavedResourceTest extends TestCase
{
    private Injector $injector;

    public function setUp(): void
    {
        $this->injector = new Injector(new TwigWeavedResourceTestModule([$_ENV['TEST_DIR'] . '/Fake/src/Resource']));
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

        $this->assertSame('Hello, BEAR.Sunday!', trim((string) $ro->onGet()));
    }
}
