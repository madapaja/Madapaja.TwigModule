<?php

declare(strict_types=1);

namespace Madapaja\TwigModule;

use BEAR\Sunday\Compile\CompileStepInterface;
use Madapaja\TwigModule\Exception\TemplateAlreadyLoaded;
use PHPUnit\Framework\TestCase;
use Ray\Di\Injector;
use Twig\Cache\FilesystemCache;
use Twig\Environment;
use Twig\Loader\FilesystemLoader;

use function count;
use function glob;
use function is_dir;
use function mkdir;
use function sys_get_temp_dir;
use function uniqid;

class TwigCompileStepTest extends TestCase
{
    /** @var non-empty-string */
    private string $stepDir;

    protected function setUp(): void
    {
        $this->stepDir = sys_get_temp_dir() . '/twig-step-' . uniqid();
        mkdir($this->stepDir, 0777, true);
    }

    public function testWriteIntoStepDirThenRestoreCache(): void
    {
        $rootPath = __DIR__ . '/Fake';
        $serveCache = new CompiledCache(new FilesystemCache($rootPath . '/var/build/twig'));
        $twig = new Environment(
            new FilesystemLoader([$rootPath . '/compile'], $rootPath),
            ['cache' => $serveCache],
        );
        $step = new TwigCompileStep($twig, new TemplateNames($rootPath));

        $this->assertSame(5, $step($this->stepDir));
        $this->assertCount(5, (array) glob($this->stepDir . '/*/*.php'));
        $this->assertSame($serveCache, $twig->getCache(false));
        $this->assertFalse(is_dir($rootPath . '/var/build/twig'));
    }

    public function testTemplateLoadedBeforeTheStepIsRefused(): void
    {
        $rootPath = __DIR__ . '/Fake';
        $twig = new Environment(new FilesystemLoader([$rootPath . '/preloaded'], $rootPath));
        $twig->render('only.twig', ['name' => 'X']);

        $this->expectException(TemplateAlreadyLoaded::class);

        (new TwigCompileStep($twig, new TemplateNames($rootPath)))($this->stepDir);
    }

    public function testBoundByName(): void
    {
        $steps = (new Injector(new TwigProdTestModule(__DIR__ . '/Fake')))->getInstance(FakeCompileSteps::class);

        $this->assertSame(1, count($steps->steps));
        $this->assertInstanceOf(CompileStepInterface::class, $steps->steps[TwigCompileStep::NAME]);
    }
}
