<?php

declare(strict_types=1);

use Madapaja\TwigModule\Annotation\TwigDebug;
use Madapaja\TwigModule\Annotation\TwigErrorPath;
use Madapaja\TwigModule\Annotation\TwigLoader;
use Madapaja\TwigModule\Annotation\TwigOptions;
use Madapaja\TwigModule\Annotation\TwigPaths;
use Madapaja\TwigModule\Annotation\TwigRedirectPath;
use Rector\CodeQuality\Rector\Class_\InlineConstructorDefaultToPropertyRector;
use Rector\Config\RectorConfig;
use Rector\Php80\Rector\Class_\AnnotationToAttributeRector;
use Rector\Php80\ValueObject\AnnotationToAttribute;
use Rector\Ray\AnnotationBinding\Rector\ClassMethod\AnnotationBindingRector;
use Rector\Set\ValueObject\LevelSetList;

return static function (RectorConfig $rectorConfig): void {
    $rectorConfig->paths([
        __DIR__ . '/src',
        __DIR__ . '/tests',
    ]);

    // register a single rule
    $rectorConfig->rule(InlineConstructorDefaultToPropertyRector::class);
    $rectorConfig->rule(AnnotationBindingRector::class);
    $rectorConfig->ruleWithConfiguration(
        AnnotationToAttributeRector::class,
        [
            new AnnotationToAttribute(TwigDebug::class),
            new AnnotationToAttribute(TwigErrorPath::class),
            new AnnotationToAttribute(TwigLoader::class),
            new AnnotationToAttribute(TwigOptions::class),
            new AnnotationToAttribute(TwigPaths::class),
            new AnnotationToAttribute(TwigRedirectPath::class),
        ]
    );

    // define sets of rules
        $rectorConfig->sets([
            LevelSetList::UP_TO_PHP_80
        ]);
};
