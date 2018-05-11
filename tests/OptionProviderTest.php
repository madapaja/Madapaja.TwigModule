<?php
/**
 * This file is part of the Madapaja.TwigModule package.
 *
 * @license http://opensource.org/licenses/MIT MIT
 */
namespace Madapaja\TwigModule;

use PHPUnit_Framework_TestCase;
use Ray\Di\Injector;

class OptionProviderTest extends PHPUnit_Framework_TestCase
{
    public $tmpDir;

    public function setUp()
    {
        $this->tmpDir = __DIR__ . '/tmp/optionProvider/tmp';
        if (! is_dir($this->tmpDir)) {
            mkdir($this->tmpDir, 0777, true);
        }
    }

    public function testOptionProvider()
    {
        /** @var $renderer TwigRenderer */
        $renderer = (new Injector(new OptionProviderTestModule($this->tmpDir, true)))->getInstance(TwigRenderer::class);

        $this->assertSame($this->tmpDir . '/twig', $renderer->twig->getCache());
        $this->assertSame(true, $renderer->twig->isDebug());
    }

    public function testOptionProviderDebugFalse()
    {
        /** @var $renderer TwigRenderer */
        $renderer = (new Injector(new OptionProviderTestModule($this->tmpDir, false)))->getInstance(TwigRenderer::class);
        $this->assertSame(false, $renderer->twig->isDebug());
    }
}
