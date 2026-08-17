<?php
/**
 * This file is part of the Madapaja.TwigModule package.
 *
 * @license http://opensource.org/licenses/MIT MIT
 */
namespace Madapaja\TwigModule;

use BEAR\AppMeta\AbstractAppMeta;
use Ray\Di\AbstractModule;

class TwigAppTestModule extends AbstractModule
{
    /** @param non-empty-string $appDir */
    public function __construct(private readonly string $appDir)
    {
        parent::__construct();
    }

    /**
     * {@inheritdoc}
     */
    protected function configure()
    {
        $this->bind(AbstractAppMeta::class)->toInstance(new FakeAppMeta($this->appDir));
        $this->install(new TwigModule());
    }
}
