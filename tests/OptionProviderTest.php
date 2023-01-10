<?php

declare(strict_types=1);

namespace Madapaja\TwigModule;

use PHPUnit\Framework\TestCase;
use Ray\Di\Injector;

use function is_dir;
use function mkdir;

class OptionProviderTest extends TestCase
{
    public $tmpDir;

    public function setUp(): void
    {
        $this->tmpDir = __DIR__ . '/tmp/optionProvider/tmp';
        if (is_dir($this->tmpDir)) {
            return;
        }

        mkdir($this->tmpDir, 0777, true);
    }

    public function testOptionProvider(): void
    {
        /** @var TwigRenderer $renderer */
        $renderer = (new Injector(new OptionProviderTestModule($this->tmpDir, true)))->getInstance(TwigRenderer::class);

        $this->assertSame($this->tmpDir . '/twig', $renderer->twig->getCache());
        $this->assertTrue($renderer->twig->isDebug());
    }

    public function testOptionProviderDebugFalse(): void
    {
        /** @var TwigRenderer $renderer */
        $renderer = (new Injector(new OptionProviderTestModule($this->tmpDir, false)))->getInstance(TwigRenderer::class);
        $this->assertFalse($renderer->twig->isDebug());
    }
}
