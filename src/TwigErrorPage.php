<?php

declare(strict_types=1);

namespace Madapaja\TwigModule;

use BEAR\Resource\RenderInterface;
use BEAR\Resource\ResourceObject;
use Ray\Di\Di\Inject;
use Ray\Di\Di\Named;

class TwigErrorPage extends ResourceObject
{
    /** @var array<string, string> */
    public $headers = ['content-type' => 'text/html; charset=utf-8'];

    /** @var RenderInterface */
    protected $renderer;

    public function __sleep()
    {
        return ['renderer'];
    }

    /**
     * @Inject
     * @Named("error_page")
     * {@inheritDoc}
     */
    #[Inject]
    #[Named('error_page')]
    public function setRenderer(RenderInterface $renderer)
    {
        $this->renderer = $renderer;

        return $this;
    }
}
