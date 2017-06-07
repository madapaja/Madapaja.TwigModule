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
        $appDir = __DIR__ . '/tmp/appPathProvider';
        if (! is_dir($appDir . '/var/tmp')) {
            mkdir($appDir . '/var/tmp', 0777, true);
        }

        $paths = [
            $appDir . '/src/Resource',
            $appDir . '/var/lib/twig',
        ];

        foreach ($paths as $path) {
            if (! is_dir($path)) {
                mkdir($path, 0777, true);
            }
        }

        /** @var $renderer TwigRenderer */
        $renderer = (new Injector(new AppPathProviderTestModule($appDir)))->getInstance(TwigRenderer::class);
        $this->assertSame($paths, $renderer->twig->getLoader()->getPaths());
    }
}
