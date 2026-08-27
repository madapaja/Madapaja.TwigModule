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
    public const CONTEXT = 'prod-app';

    /** @param non-empty-string $appDir */
    public function __construct(string $appDir)
    {
        $this->name = 'Madapaja\TwigModule';
        $this->appDir = $appDir;
        $this->buildDir = $appDir . '/var/build/' . self::CONTEXT;
        $this->tmpDir = $appDir . '/var/tmp/' . self::CONTEXT;
        $this->logDir = $appDir . '/var/log/' . self::CONTEXT;
    }
}
