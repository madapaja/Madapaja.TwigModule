<?php
/**
 * This file is part of the Madapaja.TwigModule package.
 *
 * @license http://opensource.org/licenses/MIT MIT
 */
namespace Madapaja\TwigModule;

use Madapaja\TwigModule\Resource\Page\TwigFilter;
use PHPUnit\Framework\TestCase;
use Ray\Di\Injector;

class ExtensionTest extends TestCase
{
    /**
     * @var Injector
     */
    private $injector;

    public function setUp(): void
    {
        $this->injector = new Injector(new TwigExtensionTestModule([$_ENV['TEST_DIR'] . '/Fake/src/Resource/']));
    }

    public function testTwigFilter()
    {
        $ro = $this->injector->getInstance(TwigFilter::class);
        $this->assertSame('Twig => "' . \str_rot13('Twig') . '"', (string) \trim($ro->onGet()));
    }
}
