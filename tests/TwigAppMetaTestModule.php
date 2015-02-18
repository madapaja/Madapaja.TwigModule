<?php

namespace Madapaja\TwigModule;

use BEAR\AppMeta\AbstractAppMeta;
use BEAR\AppMeta\AppMeta;
use Ray\Di\AbstractModule;

class TwigAppMetaTestModule extends AbstractModule
{
    /**
     * {@inheritdoc}
     */
    protected function configure()
    {
        $this->bind(AbstractAppMeta::class)->toInstance(new AppMeta('Madapaja\TwigModule'));
        $this->install(new TwigModule());
    }
}
