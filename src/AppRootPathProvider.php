<?php

declare(strict_types=1);

namespace Madapaja\TwigModule;

use BEAR\AppMeta\AbstractAppMeta;
use Ray\Di\ProviderInterface;

/**
 * Root path for FilesystemLoader cache keys
 *
 * getCacheKey() strips this prefix, so it must match the prefix of the paths AppPathProvider builds.
 * Twig defaults it to getcwd(), which makes the key vary per working directory.
 *
 * @implements ProviderInterface<string>
 */
class AppRootPathProvider implements ProviderInterface
{
    public function __construct(
        private readonly AbstractAppMeta $appMeta,
    ) {
    }

    /**
     * {@inheritDoc}
     */
    public function get(): string
    {
        return $this->appMeta->appDir;
    }
}
