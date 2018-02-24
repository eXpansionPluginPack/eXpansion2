<?php
/**
 * Created by PhpStorm.
 * User: olive
 * Date: 13/05/2017
 * Time: 10:35
 */

namespace Tests\eXpansion\Framework\Core\Helpers;

use eXpansion\Framework\Config\Services\ConfigManager;
use eXpansion\Framework\Config\Services\ConfigManagerInterface;
use eXpansion\Framework\Core\Helpers\Translations;
use oliverde8\AssociativeArraySimplified\AssociativeArray;
use Tests\eXpansion\Framework\Core\TestCore;

class TranslationsTest extends TestCore
{
    /** @var \PHPUnit_Framework_MockObject_MockObject */
    protected $mockConfig;

    /** @var Translations */
    protected $translationHelper;

    protected function setUp()
    {
        parent::setUp();

        $this->mockConfig = $this->getMockBuilder(ConfigManagerInterface::class)->getMock();
        $this->mockConfig->method('getConfigDefinitionTree')->willReturn(
            new AssociativeArray([
                'path' => ['color' => ['test' => '$fff'], 'icon' => ['test' => 'ICON']]
            ])
        );

        $this->translationHelper = new Translations(
            ['en', 'fr'],
            $this->mockConfig,
            'path/color',
            'path/icon',
            $this->container->get('translator')
        );
    }


    public function testGetTranslation()
    {
        $translationHelper = $this->translationHelper;


        $tranlationEn = $translationHelper->getTranslation('expansion_core.test', ['%test%' => 'TOTO'], 'en');
        $tranlationFr = $translationHelper->getTranslation('expansion_core.test', ['%test%' => 'TOTO'], 'fr');
        $tranlationUn = $translationHelper->getTranslation('expansion_core.test', ['%test%' => 'TOTO'], 'tt');

        $this->assertEquals("This is a test translation : TOTO", $tranlationEn);
        $this->assertEquals("Ceci est une trad de test : TOTO", $tranlationFr);
        $this->assertEquals("This is a test translation : TOTO", $tranlationUn);
    }


    public function testColorCodes()
    {
        $translationHelper = $this->translationHelper;


        $colorEn = $translationHelper->getTranslation('expansion_core.test_color', ['%test%' => 'TOTO'], 'en');
        $this->assertEquals('$fffThis is a test translation : TOTO', $colorEn);

        $glyphEn = $translationHelper->getTranslation('expansion_core.test_glyph', ['%test%' => 'TOTO'], 'en');
        $this->assertEquals('ICONThis is a test translation : TOTO', $glyphEn);

        $colorglyphEn = $translationHelper->getTranslation('expansion_core.test_color_glyph', ['%test%' => 'TOTO'],
            'en');
        $this->assertEquals('$fffICONThis is a test translation : TOTO', $colorglyphEn);
    }

    public function testGetTranslations()
    {
        $translationHelper = $this->translationHelper;

        $tranlationEn = $translationHelper->getTranslations('expansion_core.test', ['%test%' => 'TOTO']);
        $this->assertEquals(
            [
                0 => ['Lang' => 'en', 'Text' => "This is a test translation : TOTO"],
                1 => ['Lang' => 'fr', 'Text' => "Ceci est une trad de test : TOTO"],
            ],
            $tranlationEn
        );
    }

}
