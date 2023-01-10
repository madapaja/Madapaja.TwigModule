<?php

declare(strict_types=1);

namespace Madapaja\TwigModule;

use Madapaja\TwigModule\Resource\Page\TwigFilter;
use PHPUnit\Framework\TestCase;
use Ray\Di\Injector;

use function str_rot13;
use function trim;

class ExtensionTest extends TestCase
{
    private Injector $injector;

    public function setUp(): void
    {
        $this->injector = new Injector(new TwigExtensionTestModule([$_ENV['TEST_DIR'] . '/Fake/src/Resource/']));
    }

    public function testTwigFilter(): void
    {
        $ro = $this->injector->getInstance(TwigFilter::class);
        $this->assertSame('Twig => "' . str_rot13('Twig') . '"', (string) trim((string) $ro->onGet()));
    }
}
