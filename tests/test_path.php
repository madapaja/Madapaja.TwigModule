<?php

function test_path(string $path): string
{
    return str_replace('/', DIRECTORY_SEPARATOR, $path);
}
