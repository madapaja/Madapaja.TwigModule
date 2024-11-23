<?php

declare(strict_types=1);

require dirname(__DIR__, 1) . '/vendor/autoload.php';

function test_path(string $path): string
{
    return str_replace('/', DIRECTORY_SEPARATOR, $path);
}
