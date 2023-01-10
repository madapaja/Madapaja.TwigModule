<?php

declare(strict_types=1);

/**
 * This file is part of the Madapaja.TwigModule package.
 */

namespace Madapaja\TwigModule;

use PHPUnit\Framework\TestCase;
use Ray\Di\Injector;
use Twig\Loader\FilesystemLoader;

use function is_dir;
use function mkdir;

class AppPathProviderTest extends TestCase
{
    public function testAppPathProvider(): void
    {
        $appDir = __DIR__ . '/Fake';
        $paths = [
            $appDir . '/src/Resource',
            $appDir . '/var/templates',
        ];

        foreach ($paths as $path) {
            if (is_dir($path)) {
                continue;
            }

            mkdir($path, 0777, true);
        }

        /** @var TwigRenderer $renderer */
        $renderer = (new Injector(new AppPathProviderTestModule()))->getInstance(TwigRenderer::class);
        /** @var FilesystemLoader $loader */
        $loader = $renderer->twig->getLoader();
        $this->assertInstanceOf(FilesystemLoader::class, $loader);
        $this->assertSame($paths, $loader->getPaths());
    }
}
