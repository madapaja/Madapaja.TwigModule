<?php

declare(strict_types=1);

use Composer\Autoload\ClassLoader;
use Koriym\Attributes\AttributeReader;
use Ray\ServiceLocator\ServiceLocator;

error_reporting(E_ALL);

// loader
$loader = require dirname(__DIR__) . '/vendor/autoload.php';
/** @var ClassLoader $loader */
$loader->addPsr4('Madapaja\TwigModule\\', [__DIR__]);
$_ENV['TEST_DIR'] = __DIR__;
$_ENV['TMP_DIR'] = __DIR__ . '/tmp';
// no annotation in PHP 8
if (PHP_MAJOR_VERSION >= 8) {
    ServiceLocator::setReader(new AttributeReader());
}
