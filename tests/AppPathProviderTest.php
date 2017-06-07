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
            $appDir . '/var/lib/twig',
        ];

        /** @var $renderer TwigRenderer */
        $renderer = (new Injector(new AppPathProviderTestModule()))->getInstance(TwigRenderer::class);
        $this->assertSame($paths, $renderer->twig->getLoader()->getPaths());
    }
}
