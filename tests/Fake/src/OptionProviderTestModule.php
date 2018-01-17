<?php
/**
 * This file is part of the Madapaja.TwigModule package.
 *
 * @license http://opensource.org/licenses/MIT MIT
 */
namespace Madapaja\TwigModule;

use BEAR\AppMeta\AbstractAppMeta;
use BEAR\AppMeta\AppMeta;
use Madapaja\TwigModule\Annotation\TwigDebug;
use Madapaja\TwigModule\Annotation\TwigOptions;
use Ray\Di\AbstractModule;

class OptionProviderTestModule extends AbstractModule
{
    private $tmpDir;
    private $isDebug;

    /**
     * @param string $tmpDir
     * @param bool   $isDebug
     */
    public function __construct($tmpDir, $isDebug)
    {
        $this->tmpDir = $tmpDir;
        $this->isDebug = $isDebug;
        parent::__construct(null);
    }

    /**
     * {@inheritdoc}
     */
    protected function configure()
    {
        $appMeta = new AppMeta('Madapaja\TwigModule');
        $appMeta->tmpDir = $this->tmpDir;

        $this->bind(AbstractAppMeta::class)->toInstance($appMeta);
        $this->install(new TwigModule());
        $this->bind()->annotatedWith(TwigOptions::class)->toProvider(OptionProvider::class);
        $this->bind()->annotatedWith(TwigDebug::class)->toInstance($this->isDebug);
    }
}
