<?php

declare(strict_types=1);

namespace Madapaja\TwigModule;

use BEAR\AppMeta\AbstractAppMeta;
use Ray\Di\ProviderInterface;

/** @implements ProviderInterface<array<string>> */
class AppPathProvider implements ProviderInterface
{
    public function __construct(
        private AbstractAppMeta $appMeta,
    ) {
    }

    /**
     * {@inheritdoc}
     */
    public function get(): array
    {
        $appDir = $this->appMeta->appDir;

        return [
            $appDir . '/src/Resource',
            $appDir . '/var/templates',
        ];
    }
}
