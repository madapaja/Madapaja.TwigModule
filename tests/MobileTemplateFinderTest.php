<?php

namespace Madapaja\TwigModule;

class MobileTemplateFinderTest extends \PHPUnit_Framework_TestCase
{
    public function testMobileTemplate()
    {
        $iphone = 'Mozilla/5.0 (iPhone; CPU iPhone OS 6_0_1 like Mac OS X) AppleWebKit/536.26 (KHTML, like Gecko) Version/6.0 Mobile/10A523 Safari/8536.25';
        $templateFinder = new MobileTemplateFinder($iphone);
        $file = $templateFinder->__invoke($_ENV['TEST_DIR'] . '/Resource/Page/Index.php', $iphone);
        $expected = $_ENV['TEST_DIR'] . '/Resource/Page/Index.mobile.twig';
        $this->assertSame($expected, $file);
    }
}
