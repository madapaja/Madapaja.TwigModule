<?php

declare(strict_types=1);

namespace Madapaja\TwigModule;

use BEAR\Resource\RenderInterface;
use PHPUnit\Framework\TestCase;
use Ray\Di\Injector;

class AppMetaTest extends TestCase
{
    public function testRenderer(): void
    {
        /** @var TwigRenderer $renderer */
        $renderer = (new Injector(new TwigAppMetaTestModule()))->getInstance(RenderInterface::class);
        $this->assertInstanceOf(TwigRenderer::class, $renderer);
        $this->assertFalse($renderer->twig->isDebug());
    }
}
