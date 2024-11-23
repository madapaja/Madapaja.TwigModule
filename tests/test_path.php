<?php

declare(strict_types=1);

function test_path(string $path): string
{
    return str_replace('/', DIRECTORY_SEPARATOR, $path);
}
