<?php

declare(strict_types=1);

/**
 * @param T $path
 *
 * @return (T is non-empty-string ? non-empty-string : string)
 *
 * @template T of string
 */
function test_path(string $path): string
{
    return str_replace('/', DIRECTORY_SEPARATOR, $path);
}
