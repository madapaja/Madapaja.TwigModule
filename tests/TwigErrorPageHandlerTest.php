<?php
/**
 * This file is part of the Madapaja.TwigModule package.
 *
 * @license http://opensource.org/licenses/MIT MIT
 */
namespace Madapaja\TwigModule;

use BEAR\Sunday\Extension\Router\RouterMatch;
use BEAR\Resource\Exception\ResourceNotFoundException as NotFound;
use Twig\Loader\ArrayLoader;
use Twig\Loader\FilesystemLoader;


class TwigErrorPageHandlerTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var TwigErrorPageHandler
     */
    private $handler;

    public function setUp()
    {
        $this->handler = new TwigErrorPageHandler(
            new TwigErrorPage(
                new \Twig_Environment(
                    new FilesystemLoader(
                        __DIR__ . '/Fake/templates/error'
                    )
                )
            ),
            new FakeHttpResponder
        );
    }

    public function testHandle()
    {
        $request = new RouterMatch;
        $request->method = 'get';
        $request->path = '/';
        $request->query = [];
        $handler = $this->handler->handle(new NotFound, $request);
        $this->assertInstanceOf(TwigErrorPageHandler::class, $handler);

        return $handler;
    }

    /**
     * @depends testHandle
     */
    public function testTransfer(TwigErrorPageHandler $handler)
    {
        $handler->transfer();
        $this->assertSame(404, FakeHttpResponder::$code);
        $c = FakeHttpResponder::$content;
        $this->assertSame('<html>code:404 msg:Not Found</html>', FakeHttpResponder::$content);
        $h = FakeHttpResponder::$headers;
    }

}
