<?php

namespace Madapaja\TwigModule;

class MobileTemplateFinder implements TemplateFinderInterface
{
    const PHP_EXT = '.php';

    const MOBILE_EXT = '.mobile.twig';

    const TWIG_EXT = '.html.twig';

    private $userAgent;

    public function __construct($userAgent = null)
    {
        $this->userAgent = $userAgent;
    }

    /**
     * {@inheritdoc}
     */
    public function __invoke($name)
    {
        $pos = strrpos($name, self::PHP_EXT);
        $mobileFile = substr($name, 0, $pos) . self::MOBILE_EXT;
        $detect = new \Mobile_Detect(null, $this->userAgent);
        $isMobile = $detect->isMobile() && !$detect->isTablet();
        if ($isMobile && file_exists($mobileFile)) {
            return $mobileFile;
        }
        $file = substr($name, 0, $pos) . self::TWIG_EXT;

        return $file;
    }
}
