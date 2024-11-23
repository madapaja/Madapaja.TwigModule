<?php

declare(strict_types=1);

namespace Madapaja\TwigModule;

use PHPUnit\Framework\TestCase;
use Ray\Di\Injector;
use Twig\Loader\FilesystemLoader;

use function is_dir;
use function mkdir;
use function test_path;

class AppPathProviderTest extends TestCase
{
    public function testAppPathProvider(): void
    {
        $paths = [
            test_path(__DIR__ . '/Fake/src/Resource'),
            test_path(__DIR__ . '/Fake/var/templates'),
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
