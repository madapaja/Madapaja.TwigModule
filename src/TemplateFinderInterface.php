<?php

declare(strict_types=1);

/**
 * This file is part of the Madapaja.TwigModule package.
 */

namespace Madapaja\TwigModule;

interface TemplateFinderInterface
{
    public function __invoke(string $name): string;
}
