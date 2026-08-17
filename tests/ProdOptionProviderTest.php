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
        $options = (new ProdOptionProvider(new FakeAppMeta($appDir)))->get();
        $cache = $options['cache'];

        $this->assertInstanceOf(CompiledCache::class, $cache);
        $this->assertStringStartsWith(
            $appDir . '/var/build/twig/',
            $cache->generateKey('page/index.html.twig', '__TwigTemplate_test'),
        );
        $this->assertFalse($options['debug']);
        $this->assertArrayNotHasKey('auto_reload', $options);
        $this->assertFalse(is_dir($appDir . '/var/build'));
    }
}
