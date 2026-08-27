<?php

declare(strict_types=1);

namespace Madapaja\TwigModule;

use Twig\Cache\CacheInterface;

/** Records which templates were written, which is not the set asked for: Twig skips a class it already loaded */
class RecordingCache implements CacheInterface
{
    /** @var array<string, string> cache key => template name */
    private array $names = [];

    /** @var array<string, true> template name => written */
    private array $written = [];

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

    public function write(string $key, string $content): void
    {
        $this->cache->write($key, $content);
        $this->written[$this->names[$key] ?? $key] = true;
    }

    public function load(string $key): void
    {
        $this->cache->load($key);
    }

    public function getTimestamp(string $key): int
    {
        return $this->cache->getTimestamp($key);
    }

    /** @return array<string, true> */
    public function written(): array
    {
        return $this->written;
    }
}
