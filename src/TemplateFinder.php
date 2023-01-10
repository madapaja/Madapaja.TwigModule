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
    public function __invoke(string $resourceFilePath): string
    {
        $pos = strpos($resourceFilePath, '/Resource/');
        $relativePath = substr($resourceFilePath, $pos + 10);

        return str_replace('.php', TwigRenderer::EXT, $relativePath);
    }
}
