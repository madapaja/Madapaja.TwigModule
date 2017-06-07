<?php
/**
 * This file is part of the Madapaja.TwigModule package.
 *
 * @license http://opensource.org/licenses/MIT MIT
 */
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
