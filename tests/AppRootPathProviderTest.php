<?php

declare(strict_types=1);

namespace Madapaja\TwigModule;

use PHPUnit\Framework\TestCase;
use Ray\Di\Injector;
use Twig\Loader\FilesystemLoader;

use function assert;
use function chdir;
use function getcwd;
use function sys_get_temp_dir;
use function test_path;

class AppRootPathProviderTest extends TestCase
{
    public function testCacheKeyIsIndependentOfWorkingDirectory(): void
    {
        $appDirKey = $this->cacheKeyIn(__DIR__ . '/Fake');
        $tmpDirKey = $this->cacheKeyIn(sys_get_temp_dir());

        $this->assertSame(test_path('src/Resource/Page/Index.html.twig'), $appDirKey);
        $this->assertSame($appDirKey, $tmpDirKey);
    }

    private function cacheKeyIn(string $cwd): string
    {
        $origin = getcwd();
        assert($origin !== false);
        chdir($cwd);

        try {
            $renderer = (new Injector(new TwigAppMetaTestModule()))->getInstance(TwigRenderer::class);
            $loader = $renderer->twig->getLoader();
            assert($loader instanceof FilesystemLoader);

            return $loader->getCacheKey('Page/Index.html.twig');
        } finally {
            chdir($origin);
        }
    }
}
