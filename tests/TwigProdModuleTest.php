<?php

declare(strict_types=1);

namespace Madapaja\TwigModule;

use Madapaja\TwigModule\Exception\TemplateNotCompiled;
use PHPUnit\Framework\TestCase;

use function copy;
use function escapeshellarg;
use function exec;
use function glob;
use function implode;
use function is_dir;
use function mkdir;
use function sprintf;
use function sys_get_temp_dir;
use function trim;
use function uniqid;

use const PHP_BINARY;

class TwigProdModuleTest extends TestCase
{
    private const TEMPLATES = ['base.html.twig', 'page/_menu.twig', 'page/index.html.twig'];

    private string $appDir;

    protected function setUp(): void
    {
        $this->appDir = sys_get_temp_dir() . '/twig-prod-' . uniqid();
        foreach (['src/Resource/page', 'var/templates', 'var/tmp', 'public'] as $dir) {
            mkdir($this->appDir . '/' . $dir, 0777, true);
        }

        foreach (self::TEMPLATES as $template) {
            copy(__DIR__ . '/Fake/compile/' . $template, $this->appDir . '/src/Resource/' . $template);
        }
    }

    public function testRenderWithoutCompileIsRefused(): void
    {
        [$status, $output] = $this->php('serve');

        $this->assertSame(255, $status);
        $this->assertStringContainsString(TemplateNotCompiled::class, $output);
    }

    public function testCompiledCacheIsReusedFromAnotherWorkingDirectory(): void
    {
        [$status, $output] = $this->php('compile');
        $this->assertSame(0, $status);
        $this->assertSame('3', trim($output));

        $buildDir = $this->appDir . '/var/build/twig';
        $compiled = (array) glob($buildDir . '/*/*.php');
        $this->assertCount(3, $compiled);

        [$status, $output] = $this->php('serve');
        $this->assertSame(0, $status);
        $this->assertStringContainsString('Hello, BEAR!', $output);
        $this->assertSame($compiled, (array) glob($buildDir . '/*/*.php'));
        $this->assertFalse(is_dir($this->appDir . '/var/tmp/twig'));
    }

    /** @return array{0:int, 1:string} */
    private function php(string $mode): array
    {
        $command = sprintf(
            '%s %s %s %s 2>&1',
            escapeshellarg(PHP_BINARY),
            escapeshellarg(__DIR__ . '/Fake/script/twig_prod.php'),
            escapeshellarg($mode),
            escapeshellarg($this->appDir),
        );
        $lines = [];
        $status = 0;
        exec($command, $lines, $status);

        return [$status, implode("\n", $lines)];
    }
}
