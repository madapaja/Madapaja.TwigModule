<?php
/**
 * This file is part of the Madapaja.TwigModule package.
 *
 * @license http://opensource.org/licenses/MIT MIT
 */
namespace Madapaja\TwigModule;

use Ray\Di\AbstractModule;

/** Stands in for an application's ProdModule: chained over the app module, as BEAR.Package composes contexts */
class TwigProdTestModule extends AbstractModule
{
    /** @param non-empty-string $appDir */
    public function __construct(string $appDir)
    {
        parent::__construct(new TwigAppTestModule($appDir));
    }

    /**
     * {@inheritdoc}
     */
    protected function configure()
    {
        $this->install(new TwigProdModule());
    }
}
