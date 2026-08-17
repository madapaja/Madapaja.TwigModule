<?php

declare(strict_types=1);

namespace Madapaja\TwigModule;

use BEAR\Sunday\Compile\CompileStepInterface;
use Madapaja\TwigModule\Exception\TemplateNotWritten;
use Twig\Cache\FilesystemCache;
use Twig\Environment;

use function array_diff;
use function array_keys;
use function array_values;
use function count;
use function reset;
use function sprintf;

class TwigCompileStep implements CompileStepInterface
{
    /** Multibinding key, which the caller also uses as the directory name */
    final public const NAME = 'twig';

    public function __construct(
        private readonly Environment $twig,
        private readonly TemplateNames $templateNames,
    ) {
    }

    /**
     * {@inheritDoc}
     *
     * Twig writes a template class at most once per process, so anything that loaded a template before
     * this step ran makes the swapped-in cache skip it silently. Every expected artifact is accounted
     * for before returning.
     */
    public function __invoke(string $stepDir): int
    {
        $loader = $this->twig->getLoader();
        $targets = [];
        // One artifact per loader cache key: names resolving to the same file share a template class,
        // which happens whenever roots nest or a namespace aliases a subdirectory
        foreach (($this->templateNames)($loader) as $name) {
            $targets[$loader->getCacheKey($name)] = $name;
        }

        // The live instance, not getCache()'s string form, which would rebuild
        // FilesystemCache and re-derive its bytecode invalidation flag
        $serveCache = $this->twig->getCache(false);
        $stepCache = new RecordingCache(new FilesystemCache($stepDir));
        $this->twig->setCache($stepCache);

        try {
            foreach ($targets as $name) {
                $this->twig->load($name);
            }
        } finally {
            $this->twig->setCache($serveCache);
        }

        $written = $stepCache->written();
        $missing = array_diff(array_values($targets), array_keys($written));
        if ($missing !== []) {
            throw new TemplateNotWritten(sprintf(
                'Wrote %d of %d templates into %s; "%s" was not written.',
                count($written),
                count($targets),
                $stepDir,
                (string) reset($missing),
            ));
        }

        return count($written);
    }
}
