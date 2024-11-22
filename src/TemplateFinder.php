<?php

declare(strict_types=1);

namespace Madapaja\TwigModule;

use LogicException;

use function assert;
use function is_int;
use function str_replace;
use function strpos;
use function substr;
use function var_dump;

class TemplateFinder implements TemplateFinderInterface
{
    /**
     * {@inheritDoc}
     */
    public function __invoke(string $name): string
    {
        $pos = strpos($name, '/Resource/');
        if (! is_int($pos)) {
            var_dump($pos);
            var_dump($name);

            throw new LogicException('Resource not found in ' . $name);
        }

        assert(is_int($pos));
        $relativePath = substr($name, $pos + 10);

        return str_replace('.php', TwigRenderer::EXT, $relativePath);
    }
}
