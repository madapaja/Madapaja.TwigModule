<?php

namespace Madapaja\TwigModule;

use Ray\Di\AbstractModule;
use Ray\Di\Scope;
use Twig_LoaderInterface;
use Twig_Loader_Array;
use Twig_Environment;
use BEAR\Resource\RenderInterface;

/**
 * Twig module
 */
class TwigArrayLoaderTestModule extends AbstractModule
{
    /**
     * Configure dependency binding
     *
     * @return void
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
