<?php

declare(strict_types=1);

namespace Madapaja\TwigModule;

use BEAR\Sunday\Compile\CompileStepInterface;
use Madapaja\TwigModule\Exception\TemplateNotWritten;
use PHPUnit\Framework\TestCase;
use Ray\Di\Injector;
use Twig\Cache\FilesystemCache;
use Twig\Environment;

use function count;
use function glob;
use function is_dir;
use function mkdir;
use function sys_get_temp_dir;
use function uniqid;

class TwigCompileStepTest extends TestCase
{
    private string $rootPath;

    /** @var non-empty-string */
    private string $stepDir;

    protected function setUp(): void
    {
        $this->stepDir = sys_get_temp_dir() . '/twig-step-' . uniqid();
        mkdir($this->stepDir, 0777, true);
        $this->rootPath = __DIR__ . '/Fake';
    }

    public function testWriteIntoStepDirThenRestoreCache(): void
    {
        $serveCache = new CompiledCache(new FilesystemCache($this->rootPath . '/var/build/twig'));
        $twig = new Environment(
            new RootRelativeLoader([$this->rootPath . '/compile'], $this->rootPath),
            ['cache' => $serveCache],
        );
        $step = new TwigCompileStep($twig, new TemplateNames($this->rootPath));

        $this->assertSame(5, $step($this->stepDir));
        $this->assertCount(5, (array) glob($this->stepDir . '/*/*.php'));
        $this->assertSame($serveCache, $twig->getCache(false));
        $this->assertFalse(is_dir($this->rootPath . '/var/build/twig'));
    }

    public function testTemplateLoadedBeforeTheStepIsRefused(): void
    {
        $twig = new Environment(new RootRelativeLoader([$this->rootPath . '/preloaded'], $this->rootPath));
        $twig->render('only.twig', ['name' => 'X']);

        $this->expectException(TemplateNotWritten::class);

        (new TwigCompileStep($twig, new TemplateNames($this->rootPath)))($this->stepDir);
    }

    /** A partly warm process is the dangerous case: some templates are skipped while the rest are written */
    public function testPartialWriteIsRefused(): void
    {
        $twig = new Environment(new RootRelativeLoader([$this->rootPath . '/partial'], $this->rootPath));
        $twig->render('two.twig', ['n' => 'X']);

        $this->expectException(TemplateNotWritten::class);
        $this->expectExceptionMessage('Wrote 2 of 3 templates');
        $this->expectExceptionMessageMatches('#"two\.twig" was not written#');

        (new TwigCompileStep($twig, new TemplateNames($this->rootPath)))($this->stepDir);
    }

    /** Names sharing a cache key share a template class, so one artifact for two names is not a shortfall */
    public function testOverlappingRootsAreNotAShortfall(): void
    {
        $loader = new RootRelativeLoader([$this->rootPath . '/overlap'], $this->rootPath);
        $loader->addPath($this->rootPath . '/overlap/sub', 's');
        $twig = new Environment($loader);
        $names = (new TemplateNames($this->rootPath))($loader);

        $this->assertCount(4, $names);
        $this->assertSame(2, (new TwigCompileStep($twig, new TemplateNames($this->rootPath)))($this->stepDir));
        $this->assertCount(2, (array) glob($this->stepDir . '/*/*.php'));
    }

    public function testBoundByName(): void
    {
        $steps = (new Injector(new TwigProdTestModule(__DIR__ . '/Fake')))->getInstance(FakeCompileSteps::class);

        $this->assertSame(1, count($steps->steps));
        $this->assertInstanceOf(CompileStepInterface::class, $steps->steps[TwigCompileStep::NAME]);
    }
}
