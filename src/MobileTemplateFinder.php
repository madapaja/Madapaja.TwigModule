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
     * @TwigPaths("paths")
     */
    public function __construct($userAgent = '', array $paths = null)
    {
        $this->userAgent = $userAgent;
        $this->templateFinder = new TemplateFinder;
    }

    /**
     * {@inheritdoc}
     */
    public function __invoke(string $name) : string
    {
        $templateFile = $this->templateFinder->__invoke($name);
        $detect = new \Mobile_Detect(null, $this->userAgent);
        $isMobile = $detect->isMobile() && ! $detect->isTablet();
        if ($isMobile) {
            $mobileFile = str_replace('.html.twig', '.mobile.twig', $templateFile);

            return $mobileFile;
        }

        return $templateFile;
    }
}
