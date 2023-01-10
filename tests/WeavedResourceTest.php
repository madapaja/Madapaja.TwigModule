<?php
/**
 * This file is part of the Madapaja.TwigModule package.
 *
 * @license http://opensource.org/licenses/MIT MIT
 */
namespace Madapaja\TwigModule;

use Madapaja\TwigModule\Resource\Page\Index;
use PHPUnit\Framework\TestCase;
use Ray\Di\Injector;

class WeavedResourceTest extends TestCase
{
    private \Ray\Di\Injector $injector;

    public function setUp(): void
    {
        $this->injector = new Injector(new TwigWeavedResourceTestModule([$_ENV['TEST_DIR'] . '/Fake/src/Resource']));
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

        $this->assertSame('Hello, BEAR.Sunday!', \trim((string) $ro->onGet()));
    }
}
