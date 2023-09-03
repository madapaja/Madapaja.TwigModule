<?php

declare(strict_types=1);

namespace Madapaja\TwigModule;

use BEAR\Resource\Code;
use Madapaja\TwigModule\Exception\TemplateNotFound;
use Madapaja\TwigModule\Fake\src\ResourceNoTemplate\Page\RedirectNoTemplate;
use Madapaja\TwigModule\Resource\Page\Index;
use Madapaja\TwigModule\Resource\Page\NoTemplate;
use Madapaja\TwigModule\Resource\Page\Page;
use Madapaja\TwigModule\Resource\Page\Redirect;
use PHPUnit\Framework\TestCase;
use Ray\Di\Injector;
use ReflectionClass;

use function trim;

class FileLoaderTest extends TestCase
{
    public function getInjector(): Injector
    {
        return new Injector(new TwigFileLoaderTestModule([$_ENV['TEST_DIR'] . '/Fake/src/Resource']));
    }

    public function testRenderer(): void
    {
        $injector = $this->getInjector();
        $ro = $injector->getInstance(Index::class);
        $prop = (new ReflectionClass($ro))->getProperty('renderer');
        $prop->setAccessible(true);

        $this->assertInstanceOf(TwigRenderer::class, $prop->getValue($ro));
    }

    public function testTwigOptions(): void
    {
        /** @var TwigRenderer $renderer */
        $renderer = (new Injector(new TwigFileLoaderTestModule([$_ENV['TEST_DIR'] . '/Fake/src/Resource'], ['debug' => true])))->getInstance(TwigRenderer::class);
        $this->assertTrue($renderer->twig->isDebug());

        /** @var TwigRenderer $renderer */
        $renderer = (new Injector(new TwigFileLoaderTestModule([$_ENV['TEST_DIR'] . '/Fake/src/Resource'], ['debug' => false])))->getInstance(TwigRenderer::class);
        $this->assertFalse($renderer->twig->isDebug());
    }

    public function testIndex(): void
    {
        $injector = $this->getInjector();
        $ro = $injector->getInstance(Index::class);

        $this->assertSame('Hello, BEAR.Sunday!', trim((string) $ro->onGet()));
    }

    public function testTemplatePath(): void
    {
        $injector = $this->getInjector();
        $ro = $injector->getInstance(Index::class);

        $this->assertSame('Your Name is MyName!', trim((string) $ro->onPost('MyName')));
    }

    public function testIndexWithArg(): void
    {
        $injector = $this->getInjector();
        $ro = $injector->getInstance(Index::class);

        $this->assertSame('Hello, Madapaja!', trim((string) $ro->onGet('Madapaja')));
    }

    public function testTemplateNotFoundException(): void
    {
        $this->expectException(TemplateNotFound::class);
        $injector = $this->getInjector();
        $ro = $injector->getInstance(NoTemplate::class);
        $prop = (new ReflectionClass($ro))->getProperty('renderer');
        $prop->setAccessible(true);
        $prop->getValue($ro)->render($ro);
    }

    public function testNoViewWhenCode301(): void
    {
        $injector = $this->getInjector();
        $ro = $injector->getInstance(NoTemplate::class);
        $ro->code = 303;
        $prop = (new ReflectionClass($ro))->getProperty('renderer');
        $prop->setAccessible(true);
        $view = $prop->getValue($ro)->render($ro);
        $this->assertSame('', $view);
    }

    public function testNoContent(): void
    {
        $injector = $this->getInjector();
        $ro = $injector->getInstance(NoTemplate::class);
        $ro->code = Code::NO_CONTENT;
        $prop = (new ReflectionClass($ro))->getProperty('renderer');
        $prop->setAccessible(true);
        $view = $prop->getValue($ro)->render($ro);
        $this->assertSame('', $view);
    }

    public function testPage(): void
    {
        $injector = $this->getInjector();
        $ro = $injector->getInstance(Page::class);

        $this->assertSame('<!DOCTYPE html><html><head><title>Page</title><body>Hello, BEAR.Sunday!</body></html>', (string) $ro->onGet());
    }

    public function testCode(): void
    {
        $injector = $this->getInjector();
        $ro = $injector->getInstance(\Madapaja\TwigModule\Resource\Page\Code::class);

        $this->assertSame('code:200 date:Tue, 15 Nov 1994 12:45:26 GMT', (string) $ro->onGet());
    }

    public function testIndexTemplateWithoutPaths(): void
    {
        $injector = new Injector(new TwigAppMetaTestModule());
        $ro = $injector->getInstance(Index::class);
        $ro->onGet();
        $this->assertSame('Hello, BEAR.Sunday!', trim((string) $ro));
    }

    public function testNoRedirectOnGet(): void
    {
        $injector = $this->getInjector();
        $ro = $injector->getInstance(Redirect::class);
        $ro->onGet();

        $this->assertSame(Code::OK, $ro->code);
        $this->assertSame('foo is bar.', trim((string) $ro));
    }

    public function testRedirectOnPost(): void
    {
        $injector = $this->getInjector();
        $ro = $injector->getInstance(Redirect::class);
        $ro->onPost();

        $this->assertSame(Code::FOUND, $ro->code);
        $this->assertSame('/path/to/baz', $ro->headers['Location']);
        $this->assertMatchesRegularExpression('#^.*Redirecting to /path/to/baz.*$#s', trim((string) $ro));
    }

    public function testRedirectOnPostNoRedirectTemplate(): void
    {
        $injector = new Injector(new TwigFileLoaderTestModule([$_ENV['TEST_DIR'] . '/Fake/src/ResourceNoTemplate']));
        $ro = $injector->getInstance(RedirectNoTemplate::class);
        $ro->onPost();
        (string) $ro;
        $this->assertSame(Code::FOUND, $ro->code);
        $this->assertSame('/path/to/baz', $ro->headers['Location']);
        $this->assertSame('', $ro->view);
    }
}
