<?php

namespace Madapaja\TwigModule;

use PHPUnit_Framework_TestCase;
use Ray\Di\Injector;

class OptionProviderTest extends PHPUnit_Framework_TestCase
{
    public function testOptionProvider()
    {
        $tmpDir = __DIR__.'/tmp/optionProvider/tmp';
        if (!is_dir($tmpDir)) {
            mkdir($tmpDir, 0777, true);
        }

        /** @var $renderer TwigRenderer */
        $renderer = (new Injector(new OptionProviderTestModule($tmpDir, true)))->getInstance(TwigRenderer::class);
        $this->assertSame($tmpDir, $renderer->twig->getCache());
        $this->assertSame(true, $renderer->twig->isDebug());

        /** @var $renderer TwigRenderer */
        $renderer = (new Injector(new OptionProviderTestModule($tmpDir, false)))->getInstance(TwigRenderer::class);
        $this->assertSame(false, $renderer->twig->isDebug());
    }
}
