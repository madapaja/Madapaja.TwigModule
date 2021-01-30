<?php
/**
 * This file is part of the Madapaja.TwigModule package.
 *
 * @license http://opensource.org/licenses/MIT MIT
 */
namespace Madapaja\TwigModule;

use BEAR\Resource\RenderInterface;
use PHPUnit\Framework\TestCase;
use Ray\Di\Injector;

class AppMetaTest extends TestCase
{
    public function testRenderer()
    {
        /** @var TwigRenderer $renderer */
        $renderer = (new Injector(new TwigAppMetaTestModule()))->getInstance(RenderInterface::class);
        $this->assertInstanceOf(TwigRenderer::class, $renderer);
        $this->assertFalse($renderer->twig->isDebug());
    }
}
