<?php

namespace Madapaja\TwigModule\Resource\Page;

use BEAR\Resource\ResourceObject;

class Index extends ResourceObject
{
    public function onGet($name = 'BEAR.Sunday')
    {
        $this['name'] = $name;
        return $this;
    }
}
