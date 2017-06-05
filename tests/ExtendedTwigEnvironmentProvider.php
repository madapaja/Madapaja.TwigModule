<?php

namespace Madapaja\TwigModule;


class ExtendedTwigEnvironmentProvider extends TwigEnvironmentProvider
{
    public function get()
    {
        $twig = parent::get();
        $twig->addExtension(new MyTwigExtension());

        return $twig;
    }
}
