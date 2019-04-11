<?php
/**
 * This file is part of the Madapaja.TwigModule package.
 *
 * @license http://opensource.org/licenses/MIT MIT
 */
namespace Madapaja\TwigModule;

use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;

class MyTwigExtension extends AbstractExtension
{
    public function getFilters()
    {
        return [
            new TwigFilter('rot13', function ($string) {
                return str_rot13($string);
            }),
        ];
    }

    public function getName()
    {
        return 'my_twig_extension';
    }
}
