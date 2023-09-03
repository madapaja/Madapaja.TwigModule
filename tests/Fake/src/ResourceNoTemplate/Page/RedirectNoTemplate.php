<?php
/**
 * This file is part of the Madapaja.TwigModule package.
 *
 * @license http://opensource.org/licenses/MIT MIT
 */
namespace Madapaja\TwigModule\Fake\src\ResourceNotemplate\Page;

use BEAR\Resource\Code;
use BEAR\Resource\ResourceObject;

class RedirectNoTemplate extends ResourceObject
{
    public $headers = [
        'Content-Type' => 'text/html; charset=SJIS'
    ];

    public function onPost()
    {
        $this->code = Code::FOUND;
        $this->headers['Location'] = '/path/to/baz';

        return $this;
    }
}
