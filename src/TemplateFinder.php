<?php
/**
 * This file is part of the Madapaja.TwigModule package.
 *
 * @license http://opensource.org/licenses/MIT MIT
 */
namespace Madapaja\TwigModule;

class TemplateFinder implements TemplateFinderInterface
{
    /**
     * {@inheritdoc}
     */
    public function __invoke(string $resourceFilePath) : string
    {
        $pos = \strpos($resourceFilePath, '/Resource/');
        $relativePath = \substr($resourceFilePath, $pos + 10);
        $templateFile = \str_replace('.php', '.html.twig', $relativePath);

        return $templateFile;
    }
}
