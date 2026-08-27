<?php

declare(strict_types=1);

namespace Madapaja\TwigModule;

use FilesystemIterator;
use Madapaja\TwigModule\Annotation\TwigRootPath;
use Madapaja\TwigModule\Exception\LoaderNotEnumerable;
use RecursiveDirectoryIterator;
use RecursiveIteratorIterator;
use SplFileInfo;
use Twig\Loader\ChainLoader;
use Twig\Loader\FilesystemLoader;
use Twig\Loader\LoaderInterface;

use function array_keys;
use function assert;
use function parse_url;
use function preg_match;
use function str_ends_with;
use function str_replace;
use function strlen;
use function strspn;
use function substr;

use const DIRECTORY_SEPARATOR;
use const PHP_URL_SCHEME;

/**
 * Lists every template name a loader can resolve
 *
 * LoaderInterface has no enumeration API, so the loader tree is walked instead.
 */
class TemplateNames
{
    /**
     * Suffix of a compilable template
     *
     * Wider than TwigRenderer::EXT: Twig compiles one class per file and resolves {% include %} and
     * {% extends %} at render time, so partials and layout parents must be compiled as well.
     */
    final public const EXT = '.twig';

    public function __construct(
        #[TwigRootPath]
        private readonly string $rootPath,
    ) {
    }

    /** @return list<string> */
    public function __invoke(LoaderInterface $loader): array
    {
        return array_keys($this->names($loader));
    }

    /** @return array<string, true> */
    private function names(LoaderInterface $loader): array
    {
        if ($loader instanceof ChainLoader) {
            $names = [];
            foreach ($loader->getLoaders() as $child) {
                $names += $this->names($child);
            }

            return $names;
        }

        if (! $loader instanceof FilesystemLoader) {
            throw new LoaderNotEnumerable($loader::class);
        }

        $names = [];
        foreach ($loader->getNamespaces() as $namespace) {
            $prefix = $namespace === FilesystemLoader::MAIN_NAMESPACE ? '' : '@' . $namespace . '/';
            foreach ($loader->getPaths($namespace) as $path) {
                $names += $this->scan($this->root($path), $prefix);
            }
        }

        return $names;
    }

    /** @return array<string, true> */
    private function scan(string $dir, string $prefix): array
    {
        $names = [];
        $files = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($dir, FilesystemIterator::SKIP_DOTS));
        foreach ($files as $file) {
            assert($file instanceof SplFileInfo);
            if (! str_ends_with($file->getFilename(), self::EXT) || ! $file->isFile()) {
                continue;
            }

            $relative = substr($file->getPathname(), strlen($dir) + 1);
            $names[$prefix . str_replace(DIRECTORY_SEPARATOR, '/', $relative)] = true;
        }

        return $names;
    }

    /** Mirror of the private FilesystemLoader::isAbsolutePath(): getPaths() returns what was registered */
    private function root(string $path): string
    {
        $isAbsolute = strspn($path, '/\\', 0, 1) === 1
            || preg_match('#^[a-zA-Z]:[/\\\\]#', $path) === 1
            || parse_url($path, PHP_URL_SCHEME) !== null;

        return $isAbsolute ? $path : $this->rootPath . DIRECTORY_SEPARATOR . $path;
    }
}
