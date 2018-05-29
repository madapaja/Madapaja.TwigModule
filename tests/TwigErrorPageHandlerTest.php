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
use BEAR\Resource\Exception\ServerErrorException as ServerError;



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
    public function testTransfer404(TwigErrorPageHandler $handler)
    {
        $handler->transfer();
        $this->assertSame(404, FakeHttpResponder::$code);
        $this->assertSame('text/html; charset=utf-8', FakeHttpResponder::$headers['content-type']);
        $this->assertSame('<html>404 404</html>', FakeHttpResponder::$content);
    }

    public function testTransfer()
    {
        $request = new RouterMatch;
        $request->method = 'get';
        $request->path = '/';
        $request->query = [];
        $handler = $this->handler->handle(new ServerError, $request);
        $handler->transfer();
        $this->assertSame(500, FakeHttpResponder::$code);
        $this->assertSame('text/html; charset=utf-8', FakeHttpResponder::$headers['content-type']);
        $this->assertSame('<html>code:500 msg:Internal Server Error</html>', FakeHttpResponder::$content);
    }

}
