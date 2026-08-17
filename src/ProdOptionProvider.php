<?php

declare(strict_types=1);

namespace Madapaja\TwigModule;

use BEAR\AppMeta\AbstractAppMeta;
use Madapaja\TwigModule\Annotation\TwigDebug;
use Ray\Di\Di\Named;
use Ray\Di\ProviderInterface;
use Twig\Cache\CacheInterface;
use Twig\Cache\FilesystemCache;

/**
 * Twig options that read the build directory instead of writing to the temporary one
 *
 * auto_reload is left unset so that it keeps following debug.
 *
 * @implements ProviderInterface<array{"debug":bool, "cache":CacheInterface}>
 */
class ProdOptionProvider implements ProviderInterface
{
    /** @SuppressWarnings(PHPMD.BooleanArgumentFlag) */
    public function __construct(
        private readonly AbstractAppMeta $appMeta,
        #[Named(TwigDebug::class)]
        private readonly bool $isDebug = false,
    ) {
    }

    /**
     * {@inheritDoc}
     *
     * The directory is not created here; the compiler creates it before running the step.
     */
    public function get()
    {
        $buildDir = $this->appMeta->appDir . '/var/build/' . TwigCompileStep::NAME;

        return [
            'debug' => $this->isDebug,
            'cache' => new CompiledCache(new FilesystemCache($buildDir)),
        ];
    }
}
