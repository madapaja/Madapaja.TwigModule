<?php

namespace Madapaja\TwigModule;

use BEAR\AppMeta\AbstractAppMeta;
use Madapaja\TwigModule\Annotation\TwigDebug;
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
     * @param AbstractAppMeta $appMeta
     * @param bool            $isDebug
     *
     * @TwigDebug("isDebug")
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
