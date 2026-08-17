<?php
/**
 * This file is part of the Madapaja.TwigModule package.
 *
 * @license http://opensource.org/licenses/MIT MIT
 */
namespace Madapaja\TwigModule;

use BEAR\AppMeta\AbstractAppMeta;

class FakeAppMeta extends AbstractAppMeta
{
    /** @param non-empty-string $appDir */
    public function __construct(string $appDir)
    {
        $this->name = 'Madapaja\TwigModule';
        $this->appDir = $appDir;
        $this->tmpDir = $appDir . '/var/tmp';
        $this->logDir = $appDir . '/var/log';
    }
}
