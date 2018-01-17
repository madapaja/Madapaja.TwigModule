<?php
/**
 * This file is part of the Madapaja.TwigModule package.
 *
 * @license http://opensource.org/licenses/MIT MIT
 */
namespace Madapaja\TwigModule\Resource\Page;

use BEAR\Resource\RenderInterface;
use Madapaja\TwigModule\TwigAppMetaTestModule;
use Madapaja\TwigModule\TwigRenderer;
use PHPUnit_Framework_TestCase;
use Ray\Di\Injector;

class AppMetaTest extends PHPUnit_Framework_TestCase
{
    public function testRenderer()
    {
        /** @var $renderer TwigRenderer */
        $renderer = (new Injector(new TwigAppMetaTestModule()))->getInstance(RenderInterface::class);
        $this->assertInstanceOf(TwigRenderer::class, $renderer);
        $this->assertFalse($renderer->twig->isDebug());
    }
}
