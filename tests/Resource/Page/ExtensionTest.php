<?php

namespace Madapaja\TwigModule\Resource\Page;

use Madapaja\TwigModule\TwigExtensionTestModule;
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
        $this->injector = new Injector(new TwigExtensionTestModule([$_ENV['TEST_DIR'] . '/Resource/']));
    }

    public function testTwigFilter()
    {
        $ro = $this->injector->getInstance(TwigFilter::class);
        $this->assertSame('Twig => "'.str_rot13('Twig').'"', (string) trim($ro->onGet()));
    }
}
