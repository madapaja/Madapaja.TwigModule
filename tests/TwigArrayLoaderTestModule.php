<?php

namespace Madapaja\TwigModule;

use Ray\Di\AbstractModule;
use Twig_Loader_Array;
use Twig_LoaderInterface;

class TwigArrayLoaderTestModule extends AbstractModule
{
    /**
     * {@inheritdoc}
     */
    protected function configure()
    {
        $this->install(new TwigModule);
        $this->bind()->annotatedWith('twig_templates')->toInstance([
            'Layout.html.twig' => '<!DOCTYPE html><html><head><title>{% block title %}{% endblock %}</title><body>{% block body %}{% endblock %}</body></html>',
            'Madapaja\TwigModule\Resource\Page\Index.html.twig' => 'Hello, {{ name }}!',
            'Madapaja\TwigModule\Resource\Page\Page.html.twig' => <<<'EOD'
{% extends "Layout.html.twig" %}

{% block title %}Page{% endblock %}
{% block body %}Hello, {{ name }}!{% endblock %}
EOD
        ]);
        $this
            ->bind(Twig_LoaderInterface::class)
            ->annotatedWith('twig_loader')
            ->toConstructor(
                Twig_Loader_Array::class,
                'templates=twig_templates'
            );
    }
}
