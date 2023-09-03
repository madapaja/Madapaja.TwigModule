<?php

declare(strict_types=1);

namespace Madapaja\TwigModule;

use BEAR\Sunday\Extension\Error\ErrorInterface;
use BEAR\Sunday\Extension\Router\RouterMatch;
use BEAR\Sunday\Extension\Transfer\TransferInterface;
use Madapaja\TwigModule\Exception\RuntimeException;
use PHPUnit\Framework\TestCase;
use Psr\Log\LoggerInterface;
use Psr\Log\NullLogger;
use Ray\Di\AbstractModule;
use Ray\Di\Injector;

use function assert;

class TwigErrorPageModuleTest extends TestCase
{
    public function testError(): void
    {
        $module = new TwigErrorPageModule(new TwigModule());
        $module->install(new class extends AbstractModule{
            protected function configure(): void
            {
                $this->bind(LoggerInterface::class)->to(NullLogger::class);
                $this->bind(TransferInterface::class)->to(FakeTransfer::class);
            }
        });
        $injector = (new Injector($module));
        $error = $injector->getInstance(ErrorInterface::class);
        assert($error instanceof ErrorInterface);
        $error->handle(new RuntimeException(), new RouterMatch())->transfer();
        $this->assertArrayHasKey('status', FakeTransfer::$ro->body);
        $this->assertArrayHasKey('e', FakeTransfer::$ro->body);
        $this->assertArrayHasKey('logref', FakeTransfer::$ro->body);
    }
}
