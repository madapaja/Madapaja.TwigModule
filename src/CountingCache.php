<?php

declare(strict_types=1);

namespace Madapaja\TwigModule;

use Twig\Cache\CacheInterface;

/** Counts writes, which is not the number of templates asked for: Twig skips a class it already loaded */
class CountingCache implements CacheInterface
{
    private int $writes = 0;

    public function __construct(
        private readonly CacheInterface $cache,
    ) {
    }

    public function generateKey(string $name, string $className): string
    {
        return $this->cache->generateKey($name, $className);
    }

    public function write(string $key, string $content): void
    {
        $this->cache->write($key, $content);
        ++$this->writes;
    }

    public function load(string $key): void
    {
        $this->cache->load($key);
    }

    public function getTimestamp(string $key): int
    {
        return $this->cache->getTimestamp($key);
    }

    public function writes(): int
    {
        return $this->writes;
    }
}
