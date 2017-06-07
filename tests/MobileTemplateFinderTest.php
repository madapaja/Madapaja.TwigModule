<?php
/**
 * This file is part of the Madapaja.TwigModule package.
 *
 * @license http://opensource.org/licenses/MIT MIT
 */
namespace Madapaja\TwigModule;

use Ray\Di\Injector;

class MobileTemplateFinderTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var Injector
     */
    private $injector;

    public function setUp()
    {
        $this->injector = new Injector(new MobileTwigModule());
    }

    public function testMobileTemplate()
    {
        $iphone = 'Mozilla/5.0 (iPhone; CPU iPhone OS 6_0_1 like Mac OS X) AppleWebKit/536.26 (KHTML, like Gecko) Version/6.0 Mobile/10A523 Safari/8536.25';
        $templateFinder = new MobileTemplateFinder($iphone);
        $file = $templateFinder->__invoke($_ENV['TEST_DIR'] . '/Resource/Page/Index.php', $iphone);
        $expected = $_ENV['TEST_DIR'] . '/Resource/Page/Index.mobile.twig';
        $this->assertSame($expected, $file);
    }

    public function testPcTemplate()
    {
        $pc = 'Mozilla/5.0 (compatible; MSIE 9.0; Windows NT 6.1; WOW64; Trident/5.0)';
        $templateFinder = new MobileTemplateFinder($pc);
        $file = $templateFinder->__invoke($_ENV['TEST_DIR'] . '/Resource/Page/Index.php', $pc);
        $expected = $_ENV['TEST_DIR'] . '/Resource/Page/Index.html.twig';
        $this->assertSame($expected, $file);
    }

    public function testMobileTemplateFinder()
    {
        $templateFinder = $this->injector->getInstance(TemplateFinderInterface::class);
        $this->assertInstanceOf(MobileTemplateFinder::class, $templateFinder);
    }
}
