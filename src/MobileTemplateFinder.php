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
    /**
     * @var string
     */
    private $userAgent;

    /**
     * @var TemplateFinder
     */
    private $templateFinder;

    /**
     * @var array
     */
    private $paths;

    /**
     * @TwigPaths("paths")
     *
     * @param mixed $userAgent
     */
    public function __construct($userAgent = '', array $paths = [])
    {
        $this->userAgent = $userAgent;
        $this->templateFinder = new TemplateFinder;
        $this->paths = $paths;
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
