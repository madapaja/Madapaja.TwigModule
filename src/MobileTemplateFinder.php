<?php

declare(strict_types=1);

namespace Madapaja\TwigModule;

use Detection\MobileDetect;
use Madapaja\TwigModule\Annotation\TwigPaths;

use function file_exists;
use function sprintf;
use function str_replace;

class MobileTemplateFinder implements TemplateFinderInterface
{
    private TemplateFinder $templateFinder;

    public function __construct(
        private mixed $userAgent = '',
        #[TwigPaths]
        private array $paths = [],
    ) {
        $this->templateFinder = new TemplateFinder();
    }

    /**
     * {@inheritdoc}
     */
    public function __invoke(string $name): string
    {
        $templatePath = ($this->templateFinder)($name);
        $isMobileDetectV3 = class_exists(MobileDetect::class);
        $detect = $isMobileDetectV3 ? new MobileDetect(null, $this->userAgent) : new \Mobile_Detect(null, $this->userAgent);
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
