<?php

declare(strict_types=1);

namespace Madapaja\TwigModule;

use PHPUnit\Framework\TestCase;

use function str_replace;

class RootRelativeLoaderTest extends TestCase
{
    public function testSourcePathIsRelativeToRootPath(): void
    {
        $rootPath = __DIR__ . '/Fake';
        $source = (new RootRelativeLoader([$rootPath . '/compile'], $rootPath))->getSourceContext('base.html.twig');

        $this->assertSame('compile/base.html.twig', $source->getPath());
        $this->assertSame('base.html.twig', $source->getName());
        $this->assertStringContainsString('<!DOCTYPE html>', $source->getCode());
    }

    public function testTemplateOutsideRootPathKeepsItsAbsolutePath(): void
    {
        $source = (new RootRelativeLoader([__DIR__ . '/Fake/compile'], __DIR__ . '/Fake/preloaded'))
            ->getSourceContext('base.html.twig');

        $this->assertSame(str_replace('\\', '/', __DIR__) . '/Fake/compile/base.html.twig', $source->getPath());
    }
}
