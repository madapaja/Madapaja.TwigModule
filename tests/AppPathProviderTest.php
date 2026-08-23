<?php

declare(strict_types=1);

namespace Madapaja\TwigModule;

use PHPUnit\Framework\TestCase;
use Ray\Di\Injector;
use Twig\Loader\FilesystemLoader;

use function is_dir;
use function mkdir;
use function sys_get_temp_dir;
use function test_path;
use function uniqid;

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

    public function testRootThatDoesNotExistIsNotReturned(): void
    {
        $appDir = sys_get_temp_dir() . '/twig-roots-' . uniqid();
        mkdir($appDir . '/src/Resource', 0777, true);
        $provider = new AppPathProvider(new FakeAppMeta($appDir));

        $this->assertSame([test_path($appDir . '/src/Resource')], $provider->get());

        mkdir($appDir . '/var/templates', 0777, true);

        $this->assertSame([
            test_path($appDir . '/src/Resource'),
            test_path($appDir . '/var/templates'),
        ], $provider->get());
    }
}
