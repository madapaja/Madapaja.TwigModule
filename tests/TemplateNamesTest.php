<?php

declare(strict_types=1);

namespace Madapaja\TwigModule;

use Madapaja\TwigModule\Exception\LoaderNotEnumerable;
use PHPUnit\Framework\TestCase;
use Twig\Loader\ArrayLoader;
use Twig\Loader\ChainLoader;
use Twig\Loader\FilesystemLoader;

use function sort;

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
}
