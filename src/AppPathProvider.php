<?php

declare(strict_types=1);

namespace Madapaja\TwigModule;

use BEAR\AppMeta\AbstractAppMeta;
use Ray\Di\ProviderInterface;

use const DIRECTORY_SEPARATOR;

/** @implements ProviderInterface<array<string>> */
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

        return [
            $appDir . DIRECTORY_SEPARATOR . 'src' . DIRECTORY_SEPARATOR,
            'Resource',
            $appDir . DIRECTORY_SEPARATOR . 'var' . DIRECTORY_SEPARATOR,
            'templates',
        ];
    }
}
