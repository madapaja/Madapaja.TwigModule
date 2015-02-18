<?php

namespace Madapaja\TwigModule\Resource\Page;

use BEAR\Resource\RenderInterface;
use Madapaja\TwigModule\TwigAppMetaTestModule;
use Madapaja\TwigModule\TwigRenderer;
use PHPUnit_Framework_TestCase;
use Ray\Di\Injector;

class AppMetaTest extends PHPUnit_Framework_TestCase
{
    /**
     * @var Injector
     */
    private $injector;

    public function testRenderer()
    {
        /** @var $renderer TwigRenderer */
        $renderer = (new Injector(new TwigAppMetaTestModule()))->getInstance(RenderInterface::class);
        $this->assertInstanceOf(TwigRenderer::class, $renderer);
        $this->assertFalse($renderer->twig->isDebug());
    }

}
