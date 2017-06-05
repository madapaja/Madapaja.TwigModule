<?php

namespace Madapaja\TwigModule;

use Twig_Extension;
use Twig_SimpleFilter;

class MyTwigExtension extends Twig_Extension
{
    public function getFilters()
    {
        return [
            new Twig_SimpleFilter('rot13', function ($string) {
                return str_rot13($string);
            }),
        ];
    }
}
