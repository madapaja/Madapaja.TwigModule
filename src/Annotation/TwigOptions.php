<?php
/**
 * This file is part of the Madapaja.TwigModule package.
 *
 * @license http://opensource.org/licenses/MIT MIT
 */
namespace Madapaja\TwigModule\Annotation;

use Attribute;
use Ray\Di\Di\Qualifier;

/**
 * @Annotation
 * @Target("METHOD")
 * @Qualifier
 * @NamedArgumentConstructor
 */
#[Attribute(Attribute::TARGET_METHOD), Qualifier]
final class TwigOptions
{
    /** @var string */
    public $value;

    public function __construct(string $value)
    {
        $this->value = $value;
    }
}
