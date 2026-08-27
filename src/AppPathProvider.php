<?php

declare(strict_types=1);

namespace Madapaja\TwigModule;

use BEAR\AppMeta\AbstractAppMeta;
use Ray\Di\ProviderInterface;

use function array_filter;
use function array_values;

use const DIRECTORY_SEPARATOR;

/**
 * Default template roots, limited to the ones that exist
 *
 * FilesystemLoader::addPath() throws on a directory that is not there, and neither root is
 * guaranteed: an empty directory does not appear in a phar archive.
 *
 * @implements ProviderInterface<array<string>>
 */
class AppPathProvider implements ProviderInterface
{
    public function __construct(
        private readonly AbstractAppMeta $appMeta,
    ) {
    }

    /**
     * {@inheritDoc}
     */
    public function get(): array
    {
        $appDir = $this->appMeta->appDir;

        return array_values(array_filter([
            $appDir . DIRECTORY_SEPARATOR . 'src' . DIRECTORY_SEPARATOR . 'Resource',
            $appDir . DIRECTORY_SEPARATOR . 'var' . DIRECTORY_SEPARATOR . 'templates',
        ], 'is_dir'));
    }
}
