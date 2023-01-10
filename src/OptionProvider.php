<?php

declare(strict_types=1);

/**
 * This file is part of the Madapaja.TwigModule package.
 */

namespace Madapaja\TwigModule;

use BEAR\AppMeta\AbstractAppMeta;
use Madapaja\TwigModule\Annotation\TwigDebug;
use Ray\Di\Di\Named;
use Ray\Di\ProviderInterface;

use function file_exists;
use function mkdir;

class OptionProvider implements ProviderInterface
{
    /** @SuppressWarnings(PHPMD.BooleanArgumentFlag) */
    public function __construct(
        private AbstractAppMeta $appMeta,
        #[Named(TwigDebug::class)]
        private bool $isDebug = false,
    ) {
    }

    /**
     * {@inheritdoc}
     */
    public function get()
    {
        $tmpDir = $this->appMeta->tmpDir . '/twig';
        ! file_exists($tmpDir) && mkdir($tmpDir);

        return [
            'debug' => $this->isDebug,
            'cache' => $tmpDir,
        ];
    }
}
