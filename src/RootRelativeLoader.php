<?php

declare(strict_types=1);

namespace Madapaja\TwigModule;

use Twig\Loader\FilesystemLoader;
use Twig\Source;

use function str_replace;

/**
 * FilesystemLoader that reports template paths relative to the root path
 *
 * ModuleNode compiles Source::getPath() into the artifact, so the parent bakes the absolute path of the
 * machine that compiled it into every cache file: a production error then names a directory that only
 * existed at build time. Twig reads the path for diagnostics only, never to look a template up.
 */
class RootRelativeLoader extends FilesystemLoader
{
    public function getSourceContext(string $name): Source
    {
        $source = parent::getSourceContext($name);

        // getCacheKey() is the same path with the root path prefix stripped, and leaves
        // a template found outside the root absolute. It spells native (realpath), so
        // normalize: the path is compiled into artifacts on one OS and read on another
        return new Source($source->getCode(), $source->getName(), str_replace('\\', '/', $this->getCacheKey($name)));
    }
}
