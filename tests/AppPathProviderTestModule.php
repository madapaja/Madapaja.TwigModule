<?php
/**
 * This file is part of the Madapaja.TwigModule package.
 *
 * @license http://opensource.org/licenses/MIT MIT
 */
namespace Madapaja\TwigModule;

use BEAR\AppMeta\AbstractAppMeta;
use BEAR\AppMeta\AppMeta;
use Madapaja\TwigModule\Annotation\TwigPaths;
use Ray\Di\AbstractModule;

class AppPathProviderTestModule extends AbstractModule
{
    private $dir;

    public function __construct($dir)
    {
        $this->dir = $dir;
        parent::__construct(null);
    }

    /**
     * {@inheritdoc}
     */
    protected function configure()
    {
        $this->bind(AbstractAppMeta::class)->toInstance(new AppMeta('Madapaja\TwigModule'));
        $this->install(new TwigModule());
        $this->bind()->annotatedWith(TwigPaths::class)->toProvider(AppPathProvider::class);
    }
}
