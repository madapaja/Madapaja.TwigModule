<?php
/**
 * This file is part of the Madapaja.TwigModule package.
 *
 * @license http://opensource.org/licenses/MIT MIT
 */
namespace Madapaja\TwigModule;

use BEAR\Resource\RenderInterface;
use BEAR\Resource\ResourceObject;
use Ray\Di\Di\Inject;
use Ray\Di\Di\Named;

class TwigErrorPage extends ResourceObject
{
    /**
     * @var array
     */
    public $headers = [
        'content-type' => 'text/html; charset=utf-8'
    ];
    protected $renderer;

    public function __sleep()
    {
        return ['renderer'];
    }

    /**
     * @Inject
     * @Named("error_page")
     */
    public function setRenderer(RenderInterface $renderer)
    {
        $this->renderer = $renderer;
    }
}
