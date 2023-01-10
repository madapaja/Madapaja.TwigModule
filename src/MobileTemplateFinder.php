<?php
/**
 * This file is part of the Madapaja.TwigModule package.
 *
 * @license http://opensource.org/licenses/MIT MIT
 */
namespace Madapaja\TwigModule;

use Madapaja\TwigModule\Annotation\TwigPaths;

class MobileTemplateFinder implements TemplateFinderInterface
{
    private \Madapaja\TwigModule\TemplateFinder $templateFinder;

    public function __construct(private mixed $userAgent = '', #[\Madapaja\TwigModule\Annotation\TwigPaths]private array $paths = [])
    {
        $this->templateFinder = new TemplateFinder;
    }

    /**
     * {@inheritdoc}
     */
    public function __invoke(string $name) : string
    {
        $templatePath = ($this->templateFinder)($name);
        $detect = new \Mobile_Detect(null, $this->userAgent);
        $isMobile = $detect->isMobile() && ! $detect->isTablet();
        if ($isMobile) {
            $mobilePath = \str_replace('.html.twig', '.mobile.twig', $templatePath);
            foreach ($this->paths as $path) {
                $mobileFile = \sprintf('%s/%s', $path, $mobilePath);
                if (\file_exists($mobileFile)) {
                    return $mobilePath;
                }
            }
        }

        return $templatePath;
    }
}
