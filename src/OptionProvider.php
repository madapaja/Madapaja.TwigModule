<?php
/**
 * This file is part of the Madapaja.TwigModule package.
 *
 * @license http://opensource.org/licenses/MIT MIT
 */
namespace Madapaja\TwigModule;

use BEAR\AppMeta\AbstractAppMeta;
use Ray\Di\Di\Named;
use Ray\Di\ProviderInterface;

class OptionProvider implements ProviderInterface
{
    /**
     * @SuppressWarnings(PHPMD.BooleanArgumentFlag)
     */
    public function __construct(private AbstractAppMeta $appMeta, #[\Ray\Di\Di\Named(\Madapaja\TwigModule\Annotation\TwigDebug::class)]private bool $isDebug = false)
    {
    }

    /**
     * {@inheritdoc}
     */
    public function get()
    {
        $tmpDir = $this->appMeta->tmpDir . '/twig';
        ! \file_exists($tmpDir) && \mkdir($tmpDir);
        $options = [
            'debug' => $this->isDebug,
            'cache' => $tmpDir
        ];

        return $options;
    }
}
