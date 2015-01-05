<?php

use Doctrine\Common\Annotations\AnnotationRegistry;

error_reporting(E_ALL);

// loader
$loader = require dirname(__DIR__) . '/vendor/autoload.php';
/** @var $loader \Composer\Autoload\ClassLoader */
$loader->addPsr4('Madapaja\TwigModule\\', [__DIR__]);
AnnotationRegistry::registerLoader([$loader, 'loadClass']);

$_ENV['TMP_DIR'] = __DIR__ . '/tmp';
