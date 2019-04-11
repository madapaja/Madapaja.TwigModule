<?php
/**
 * This file is part of the Madapaja.TwigModule package.
 *
 * @license http://opensource.org/licenses/MIT MIT
 */
namespace Madapaja\TwigModule;

use Madapaja\TwigModule\Annotation\TwigLoader;
use Ray\Di\AbstractModule;
use Twig\Loader\ArrayLoader;
use Twig\Loader\LoaderInterface;

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
            ->bind(LoaderInterface::class)
            ->annotatedWith(TwigLoader::class)
            ->toConstructor(
                ArrayLoader::class,
                'templates=twig_templates'
            );
    }
}
