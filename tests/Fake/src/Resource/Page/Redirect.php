<?php
/**
 * This file is part of the Madapaja.TwigModule package.
 *
 * @license http://opensource.org/licenses/MIT MIT
 */
namespace Madapaja\TwigModule\Resource\Page;

use BEAR\Resource\Code;
use BEAR\Resource\ResourceObject;

class Redirect extends ResourceObject
{
    public function onGet()
    {
        $this->body = [
            'foo' => 'bar',
        ];

        return $this;
    }

    public function onPost()
    {
        $this->code = Code::FOUND;
        $this->headers['Location'] = '/path/to/baz';

        return $this;
    }
}
