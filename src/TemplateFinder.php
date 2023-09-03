<?php

declare(strict_types=1);

namespace Madapaja\TwigModule;

use function str_replace;
use function strpos;
use function substr;

class TemplateFinder implements TemplateFinderInterface
{
    /**
     * {@inheritdoc}
     */
    public function __invoke(string $name): string
    {
        $pos = strpos($name, '/Resource/');
        $relativePath = substr($name, $pos + 10);

        return str_replace('.php', TwigRenderer::EXT, $relativePath);
    }
}
