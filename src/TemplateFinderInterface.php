<?php

namespace Madapaja\TwigModule;

interface TemplateFinderInterface
{
    /**
     * @param string $name target class file name
     *
     * @return string
     */
    public function __invoke($name);
}
