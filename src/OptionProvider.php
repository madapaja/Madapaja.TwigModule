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
     * @var AbstractAppMeta
     */
    private $appMeta;

    /**
     * @var bool
     */
    private $isDebug;

    /**
     * @Named("isDebug=Madapaja\TwigModule\Annotation\TwigDebug")
     * @SuppressWarnings(PHPMD.BooleanArgumentFlag)
     */
    public function __construct(AbstractAppMeta $appMeta, $isDebug = false)
    {
        $this->appMeta = $appMeta;
        $this->isDebug = $isDebug;
    }

    /**
     * {@inheritdoc}
     */
    public function get()
    {
        $options = [
            'debug' => $this->isDebug,
            'cache' => $this->appMeta->tmpDir
        ];

        return $options;
    }
}
