<?php

namespace Madapaja\TwigModule\Annotation;

use Ray\Di\Di\Qualifier;

/**
 * @Annotation
 * @Target("METHOD")
 * @Qualifier
 */
final class TwigPaths
{
    public $value;
}
