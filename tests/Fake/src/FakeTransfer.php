<?php

namespace Madapaja\TwigModule;

use BEAR\Resource\ResourceObject;
use BEAR\Sunday\Extension\Transfer\TransferInterface;

class FakeTransfer implements TransferInterface
{
    public static ResourceObject $ro;
    public function __invoke(ResourceObject $ro, array $server)
    {
        self::$ro = $ro;
    }
}