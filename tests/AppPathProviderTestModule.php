<?php

declare(strict_types=1);

namespace Madapaja\TwigModule;

use BEAR\AppMeta\AbstractAppMeta;
use BEAR\AppMeta\AppMeta;
use Madapaja\TwigModule\Annotation\TwigPaths;
use Ray\Di\AbstractModule;

class AppPathProviderTestModule extends AbstractModule
{
    /**
     * {@inheritDoc}
     */
    protected function configure()
    {
        $this->bind(AbstractAppMeta::class)->toInstance(new AppMeta('Madapaja\TwigModule'));
        $this->install(new TwigModule());
        $this->bind()->annotatedWith(TwigPaths::class)->toProvider(AppPathProvider::class);
    }
}
