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
     * @param string $name
     */
    public function __invoke(string $name) : string;
}
