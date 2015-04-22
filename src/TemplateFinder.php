<?php

namespace Madapaja\TwigModule;

class TemplateFinder implements TemplateFinderInterface
{
    const PHP_EXT = '.php';

    const TWIG_EXT = '.html.twig';

    /**
     * {@inheritdoc}
     */
    public function __invoke($name)
    {
        $pos = strrpos($name, self::PHP_EXT);
        $file = substr($name, 0, $pos) . self::TWIG_EXT;

        return $file;
    }
}
