<?php

declare(strict_types=1);

namespace Madapaja\TwigModule;

use function assert;
use function is_int;
use function str_replace;
use function strpos;
use function substr;
use function var_dump;
use const DIRECTORY_SEPARATOR;

class TemplateFinder implements TemplateFinderInterface
{
    /**
     * {@inheritDoc}
     */
    public function __invoke(string $name): string
    {
        $pos = strpos($name, DIRECTORY_SEPARATOR . 'Resource' . DIRECTORY_SEPARATOR);
        assert(is_int($pos));
        $relativePath = substr($name, $pos + 10);

        return str_replace('.php', TwigRenderer::EXT, $relativePath);
    }
}
