<?php

namespace Madapaja\TwigModule\Resource\Page;

use BEAR\Resource\ResourceObject;

class NoTemplate extends ResourceObject
{
    public function onGet()
    {
        return $this;
    }
}
