<?php
/**
 * This file is part of the Madapaja.TwigModule package.
 *
 * @license http://opensource.org/licenses/MIT MIT
 */
namespace Madapaja\TwigModule\Resource\Page;

use BEAR\Resource\ResourceObject;

class Index extends ResourceObject
{
    public $templatePath;

    public function onGet($name = 'BEAR.Sunday')
    {
        $this['name'] = $name;

        return $this;
    }

    public function onPost($name = 'BEAR.Sunday')
    {
        $this->templatePath = __DIR__ . '/IndexPost.html.twig';
        $this['name'] = $name;
        $this['isPost'] = true;

        return $this;
    }
}
