<?php

declare(strict_types=1);

namespace Madapaja\TwigModule;

use Madapaja\TwigModule\Exception\LoaderNotEnumerable;
use PHPUnit\Framework\TestCase;
use Twig\Loader\ArrayLoader;
use Twig\Loader\ChainLoader;
use Twig\Loader\FilesystemLoader;

use function file_put_contents;
use function mkdir;
use function rmdir;
use function sort;
use function symlink;
use function sys_get_temp_dir;
use function uniqid;
use function unlink;

use const PHP_OS_FAMILY;

class TemplateNamesTest extends TestCase
{
    private string $rootPath;
    private TemplateNames $templateNames;

    protected function setUp(): void
    {
        $this->rootPath = __DIR__ . '/Fake';
        $this->templateNames = new TemplateNames($this->rootPath);
    }

    public function testEverySuffixThatEndsWithTwig(): void
    {
        $names = ($this->templateNames)(new FilesystemLoader([$this->rootPath . '/compile'], $this->rootPath));
        sort($names);

        $this->assertSame([
            'base.html.twig',
            'mail/notice.txt.twig',
            'page/_menu.twig',
            'page/index.html.twig',
            'page/index.mobile.twig',
        ], $names);
    }

    public function testPathRelativeToRootPath(): void
    {
        $names = ($this->templateNames)(new FilesystemLoader(['compile'], $this->rootPath));
        sort($names);

        $this->assertSame('base.html.twig', $names[0]);
    }

    public function testNamespacedName(): void
    {
        $loader = new FilesystemLoader([], $this->rootPath);
        $loader->addPath($this->rootPath . '/compile/mail', 'mail');

        $this->assertSame(['@mail/notice.txt.twig'], ($this->templateNames)($loader));
    }

    public function testChainLoaderRecursion(): void
    {
        $loader = new ChainLoader([
            new FilesystemLoader([$this->rootPath . '/compile/mail'], $this->rootPath),
            new FilesystemLoader([$this->rootPath . '/compile/page'], $this->rootPath),
        ]);
        $names = ($this->templateNames)($loader);
        sort($names);

        $this->assertSame(['_menu.twig', 'index.html.twig', 'index.mobile.twig', 'notice.txt.twig'], $names);
    }

    public function testArrayLoaderCannotBeEnumerated(): void
    {
        $this->expectException(LoaderNotEnumerable::class);

        ($this->templateNames)(new ArrayLoader(['page.twig' => 'hello']));
    }

    public function testSymlinkedDirectoryIsNotEnumerated(): void
    {
        $base = sys_get_temp_dir() . '/' . uniqid('twig-names-', true);
        $dir = $base . '/templates';
        $outside = $base . '/outside';
        mkdir($dir . '/real', 0777, true);
        mkdir($outside);
        file_put_contents($dir . '/real/a.twig', '');
        file_put_contents($outside . '/b.twig', '');
        if (! @symlink($outside, $dir . '/linked')) {
            $this->markTestSkipped('symlink() is not available');
        }

        try {
            $names = ($this->templateNames)(new FilesystemLoader([$dir], $this->rootPath));

            $this->assertSame(['real/a.twig'], $names);
        } finally {
            // Windows removes a directory symlink with rmdir(), not unlink()
            PHP_OS_FAMILY === 'Windows' ? rmdir($dir . '/linked') : unlink($dir . '/linked');
            unlink($dir . '/real/a.twig');
            unlink($outside . '/b.twig');
            rmdir($dir . '/real');
            rmdir($dir);
            rmdir($outside);
            rmdir($base);
        }
    }
}
