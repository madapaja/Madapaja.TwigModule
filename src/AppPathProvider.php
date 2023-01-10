<?php
/**
 * This file is part of the Madapaja.TwigModule package.
 *
 * @license http://opensource.org/licenses/MIT MIT
 */
namespace Madapaja\TwigModule;

use BEAR\AppMeta\AbstractAppMeta;
use Ray\Di\ProviderInterface;

class AppPathProvider implements ProviderInterface
{
    public function __construct(private AbstractAppMeta $appMeta)
    {
    }

    /**
     * {@inheritdoc}
     */
    public function get()
    {
        $appDir = $this->appMeta->appDir;
        $paths = [
            $appDir . '/src/Resource',
            $appDir . '/var/templates'
        ];

        return $paths;
    }
}
