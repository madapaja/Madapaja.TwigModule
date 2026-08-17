<?php

declare(strict_types=1);

namespace Madapaja\TwigModule;

use Madapaja\TwigModule\Exception\TemplateNotCompiled;
use Twig\Cache\CacheInterface;

use function sprintf;

/**
 * Read-only view of the build directory
 *
 * A cache miss while serving means the build step did not write this template, or wrote it under a
 * different options hash. Twig would silently compile it instead, and Twig's own ReadOnlyFilesystemCache
 * would silently discard the write, so the write raises an error here.
 */
class CompiledCache implements CacheInterface
{
    /** @var array<string, string> */
    private array $names = [];

    public function __construct(
        private readonly CacheInterface $cache,
    ) {
    }

    public function generateKey(string $name, string $className): string
    {
        $key = $this->cache->generateKey($name, $className);
        $this->names[$key] = $name;

        return $key;
    }

    /** @SuppressWarnings(PHPMD.UnusedFormalParameter) */
    public function write(string $key, string $content): void
    {
        throw new TemplateNotCompiled(sprintf(
            'Template "%s" has no compiled artifact (%s).',
            $this->names[$key] ?? $key,
            $key,
        ));
    }

    public function load(string $key): void
    {
        $this->cache->load($key);
    }

    public function getTimestamp(string $key): int
    {
        return $this->cache->getTimestamp($key);
    }
}
