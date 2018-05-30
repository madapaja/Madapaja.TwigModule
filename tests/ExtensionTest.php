<?php
/**
 * This file is part of the Madapaja.TwigModule package.
 *
 * @license http://opensource.org/licenses/MIT MIT
 */
namespace Madapaja\TwigModule;

use Madapaja\TwigModule\Resource\Page\TwigFilter;
use PHPUnit_Framework_TestCase;
use Ray\Di\Injector;

class ExtensionTest extends PHPUnit_Framework_TestCase
{
    /**
     * @var Injector
     */
    private $injector;

    public function setUp()
    {
        $this->injector = new Injector(new TwigExtensionTestModule([$_ENV['TEST_DIR'] . '/Fake/src/Resource/']));
    }

    public function testTwigFilter()
    {
        $ro = $this->injector->getInstance(TwigFilter::class);
        $this->assertSame('Twig => "' . \str_rot13('Twig') . '"', (string) \trim($ro->onGet()));
    }
}
