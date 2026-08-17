<?php
/**
 * Build and serve a production application in separate processes
 *
 * Twig never writes a template class twice in one process, so compiling and rendering have to be
 * measured apart. Usage: php twig_prod.php compile|serve {appDir}
 *
 * This file is part of the Madapaja.TwigModule package.
 *
 * @license http://opensource.org/licenses/MIT MIT
 */

declare(strict_types=1);

namespace Madapaja\TwigModule;

use Ray\Di\Injector;
use Twig\Environment;

/** @psalm-suppress UnresolvableInclude */
require dirname(__DIR__, 3) . '/vendor/autoload.php';

[, $mode, $appDir] = $argv;
assert($appDir !== '');

$injector = new Injector(new TwigProdTestModule($appDir));

if ($mode === 'compile') {
    $stepDir = $appDir . '/var/build/' . TwigCompileStep::NAME;
    mkdir($stepDir, 0777, true);
    $step = $injector->getInstance(TwigCompileStep::class);
    echo $step($stepDir);

    return;
}

chdir($appDir . '/public');
echo $injector->getInstance(Environment::class)->render('page/index.html.twig', ['name' => 'BEAR']);
