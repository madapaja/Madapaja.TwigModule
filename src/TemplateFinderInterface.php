<?php

declare(strict_types=1);

namespace Madapaja\TwigModule;

interface TemplateFinderInterface
{
    public function __invoke(string $name): string;
}
