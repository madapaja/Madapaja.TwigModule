<?php

declare(strict_types=1);

namespace Madapaja\TwigModule;

use Madapaja\TwigModule\Exception\TemplateNotCompiled;
use PHPUnit\Framework\TestCase;
use Twig\Cache\FilesystemCache;

use function sys_get_temp_dir;

class CompiledCacheTest extends TestCase
{
    public function testWriteIsRefusedByTemplateName(): void
    {
        $cache = new CompiledCache(new FilesystemCache(sys_get_temp_dir() . '/twig-compiled-cache'));
        $key = $cache->generateKey('page/index.html.twig', '__TwigTemplate_test');

        $this->expectException(TemplateNotCompiled::class);
        $this->expectExceptionMessageMatches('#page/index\.html\.twig#');

        $cache->write($key, '<?php');
    }
}
