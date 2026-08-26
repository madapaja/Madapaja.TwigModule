<?php

declare(strict_types=1);

namespace Madapaja\TwigModule;

use PHPUnit\Framework\TestCase;

use function is_dir;

class ProdOptionProviderTest extends TestCase
{
    public function testCacheReadsTheBuildDir(): void
    {
        $appDir = __DIR__ . '/Fake';
        $meta = new FakeAppMeta($appDir);
        $options = (new ProdOptionProvider($meta))->get();
        $cache = $options['cache'];

        $this->assertInstanceOf(CompiledCache::class, $cache);
        $this->assertStringStartsWith(
            $meta->buildDir . '/twig/',
            $cache->generateKey('page/index.html.twig', '__TwigTemplate_test'),
        );
        $this->assertFalse($options['debug']);
        $this->assertFalse($options['auto_reload']);
        $this->assertFalse(is_dir($meta->buildDir));
    }
}
