<?php
/**
 * This file is part of the Madapaja.TwigModule package.
 *
 * @license http://opensource.org/licenses/MIT MIT
 */
namespace Madapaja\TwigModule;

use PHPUnit_Framework_TestCase;
use Ray\Di\Injector;

class AppPathProviderTest extends PHPUnit_Framework_TestCase
{
    public function testAppPathProvider()
    {
        $appDir = __DIR__ . '/Fake';
        $paths = [
            $appDir . '/src/Resource',
            $appDir . '/var/templates',
        ];

        foreach ($paths as $path) {
            if (! \is_dir($path)) {
                \mkdir($path, 0777, true);
            }
        }

        /** @var TwigRenderer $renderer */
        $renderer = (new Injector(new AppPathProviderTestModule($appDir)))->getInstance(TwigRenderer::class);
        /** @var \Twig_Loader_Filesystem $loader */
        $loader = $renderer->twig->getLoader();
        $this->assertInstanceOf(\Twig_Loader_Filesystem::class, $loader);
        $this->assertSame($paths, $loader->getPaths());
    }
}
