<?php

declare(strict_types=1);

namespace Madapaja\TwigModule;

use Detection\MobileDetect;
use Madapaja\TwigModule\Annotation\TwigPaths;
use Mobile_Detect;
use Ray\Di\Di\Named;

use function class_alias;
use function class_exists;
use function file_exists;
use function sprintf;
use function str_replace;

class MobileTemplateFinder implements TemplateFinderInterface
{
    /** @param array<string> $paths */
    public function __construct(
        #[TwigPaths]
        private array $paths,
        #[Named('original')]
        private TemplateFinderInterface $templateFinder,
    ) {
    }

    /**
     * {@inheritDoc}
     */
    public function __invoke(string $name): string
    {
        if (! class_exists(MobileDetect::class)) {
            // @codeCoverageIgnoreStart
            class_alias(Mobile_Detect::class, MobileDetect::class); // @phpstan-ignore-line
            // @codeCoverageIgnoreEnd
        }

        $detect = new MobileDetect();
        $templatePath = ($this->templateFinder)($name);
        $isMobile = $detect->isMobile() && ! $detect->isTablet();
        if ($isMobile) {
            $mobilePath = str_replace(TwigRenderer::EXT, '.mobile.twig', $templatePath);
            foreach ($this->paths as $path) {
                $mobileFile = sprintf('%s/%s', $path, $mobilePath);
                if (file_exists($mobileFile)) {
                    return $mobilePath;
                }
            }
        }

        return $templatePath;
    }
}
