<?php
/**
 * This file is part of the Madapaja.TwigModule package.
 *
 * @license http://opensource.org/licenses/MIT MIT
 */
namespace Madapaja\TwigModule;

use BEAR\AppMeta\AppMeta;
use BEAR\Resource\Exception\ResourceNotFoundException as NotFound;
use BEAR\Resource\Exception\ServerErrorException as ServerError;
use BEAR\Sunday\Extension\Router\RouterMatch;
use Twig\Loader\FilesystemLoader;


class TwigErrorPageHandlerTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var TwigErrorHandler
     */
    private $handler;

    public function setUp()
    {
        $twig = new \Twig_Environment(
            new FilesystemLoader(
                __DIR__ . '/Fake/templates'
            )
        );
        $errorPage =  new TwigErrorPage;
        $errorPage->setRenderer(new ErrorPagerRenderer($twig));
        $this->handler = new TwigErrorHandler(
            $errorPage,
            new FakeHttpResponder(),
            new AppMeta('Madapaja\TwigModule')
        );
    }

    public function testHandle()
    {
        $request = new RouterMatch;
        $request->method = 'get';
        $request->path = '/';
        $request->query = [];
        $handler = $this->handler->handle(new NotFound, $request);
        $this->assertInstanceOf(TwigErrorHandler::class, $handler);

        return $handler;
    }

    /**
     * @depends testHandle
     */
    public function testTransfer404(TwigErrorHandler $handler)
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
        $this->assertSame(503, FakeHttpResponder::$code);
        $this->assertSame('text/html; charset=utf-8', FakeHttpResponder::$headers['content-type']);
        $this->assertSame('<html>code:503 msg:Service Unavailable</html>', FakeHttpResponder::$content);
    }

}
