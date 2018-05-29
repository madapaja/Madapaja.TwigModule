<?php
/**
 * This file is part of the Madapaja.TwigModule package.
 *
 * @license http://opensource.org/licenses/MIT MIT
 */
use Doctrine\Common\Annotations\AnnotationRegistry;

\error_reporting(E_ALL);

// loader
$loader = require \dirname(__DIR__) . '/vendor/autoload.php';
/* @var $loader \Composer\Autoload\ClassLoader */
$loader->addPsr4('Madapaja\TwigModule\\', [__DIR__]);
AnnotationRegistry::registerLoader([$loader, 'loadClass']);

$_ENV['TEST_DIR'] = __DIR__;
$_ENV['TMP_DIR'] = __DIR__ . '/tmp';
