<?php
/**
 * This file is part of the Madapaja.TwigModule package.
 *
 * @license http://opensource.org/licenses/MIT MIT
 */
namespace Madapaja\TwigModule\Resource\Page;

use BEAR\Resource\ResourceObject;

class Page extends ResourceObject
{
    public function onGet($name = 'BEAR.Sunday')
    {
        $this['name'] = $name;

        return $this;
    }
}
