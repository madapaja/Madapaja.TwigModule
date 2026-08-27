<?php
/**
 * This file is part of the Madapaja.TwigModule package.
 *
 * @license http://opensource.org/licenses/MIT MIT
 */
namespace Madapaja\TwigModule;

use BEAR\Sunday\Compile\CompileStepInterface;
use Ray\Di\Di\Set;
use Ray\Di\MultiBinding\Map;

class FakeCompileSteps
{
    /** @param Map<CompileStepInterface> $steps */
    public function __construct(
        #[Set(CompileStepInterface::class)]
        public readonly Map $steps,
    ) {
    }
}
