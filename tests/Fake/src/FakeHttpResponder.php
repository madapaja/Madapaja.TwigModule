<?php
/**
 * This file is part of the Madapaja.TwigModule package.
 *
 * @license http://opensource.org/licenses/MIT MIT
 */
namespace Madapaja\TwigModule;

use BEAR\Resource\ResourceObject;
use BEAR\Sunday\Extension\Transfer\TransferInterface;

class FakeHttpResponder implements TransferInterface
{
    public static $code;
    public static $headers = [];
    public static $content;

    public static function reset()
    {
        static::$headers = [];
        static::$content = null;
    }

    public function __invoke(ResourceObject $ro, array $server)
    {
        unset($server);
        $ro->toString();
        self::$content = $ro->view;
        self::$code = $ro->code;
        self::$headers = $ro->headers;
    }
}
