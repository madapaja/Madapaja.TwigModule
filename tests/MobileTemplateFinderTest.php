<?php

declare(strict_types=1);

namespace Madapaja\TwigModule;

use BEAR\AppMeta\AbstractAppMeta;
use BEAR\AppMeta\Meta;
use PHPUnit\Framework\TestCase;
use Ray\Di\AbstractModule;
use Ray\Di\Injector;

class MobileTemplateFinderTest extends TestCase
{
    private Injector $injector;

    public function setUp(): void
    {
        $module = new MobileTwigModule(new TwigModule());
        $module->install(new class extends AbstractModule{
            protected function configure(): void
            {
                $this->bind(AbstractAppMeta::class)->toInstance(new Meta(__NAMESPACE__));
            }
        });
        $this->injector = new Injector($module);
    }

    public function testMobileTemplate(): void
    {
        $_SERVER['HTTP_USER_AGENT'] = 'Mozilla/5.0 (iPhone; CPU iPhone OS 6_0_1 like Mac OS X) AppleWebKit/536.26 (KHTML, like Gecko) Version/6.0 Mobile/10A523 Safari/8536.25';
        $paths = [$_ENV['TEST_DIR'] . '/Fake/src/Resource'];
        $templateFinder = new MobileTemplateFinder($paths, new TemplateFinder());
        $file = ($templateFinder)($_ENV['TEST_DIR'] . '/Resource/Page/Index.php');
        $expected = 'Page/Index.mobile.twig';
        $this->assertSame($expected, $file);
    }

    public function testPcTemplate(): void
    {
        $_SERVER['HTTP_USER_AGENT'] = 'Mozilla/5.0 (compatible; MSIE 9.0; Windows NT 6.1; WOW64; Trident/5.0)';
        $paths = [$_ENV['TEST_DIR'] . '/Fake/src/Resource'];
        $templateFinder = new MobileTemplateFinder($paths, new TemplateFinder());
        $file = ($templateFinder)($_ENV['TEST_DIR'] . '/Resource/Page/Index.php');
        $expected = 'Page/Index.html.twig';
        $this->assertSame($expected, $file);
    }

    public function testMobileTemplateFinder(): void
    {
        $templateFinder = $this->injector->getInstance(TemplateFinderInterface::class);
        $this->assertInstanceOf(MobileTemplateFinder::class, $templateFinder);
    }
}
