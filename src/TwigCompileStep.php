<?php

declare(strict_types=1);

namespace Madapaja\TwigModule;

use BEAR\Sunday\Compile\CompileStepInterface;
use Madapaja\TwigModule\Exception\TemplateAlreadyLoaded;
use Twig\Cache\FilesystemCache;
use Twig\Environment;

use function count;
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
     * Must run before anything renders: Environment::loadTemplate() returns early for a template class
     * already defined in the process, so a swapped-in cache is skipped rather than written.
     */
    public function __invoke(string $stepDir): int
    {
        $names = ($this->templateNames)($this->twig->getLoader());
        // The live instance, not getCache()'s string form, which would rebuild
        // FilesystemCache and re-derive its bytecode invalidation flag
        $serveCache = $this->twig->getCache(false);
        $stepCache = new CountingCache(new FilesystemCache($stepDir));
        $this->twig->setCache($stepCache);

        try {
            foreach ($names as $name) {
                $this->twig->load($name);
            }
        } finally {
            $this->twig->setCache($serveCache);
        }

        if ($names !== [] && $stepCache->writes() === 0) {
            throw new TemplateAlreadyLoaded(sprintf('None of %d templates was written; they were loaded before this step ran.', count($names)));
        }

        return $stepCache->writes();
    }
}
