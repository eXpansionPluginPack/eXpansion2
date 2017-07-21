<?php
/**
 * Created by PhpStorm.
 * User: olive
 * Date: 13/05/2017
 * Time: 10:35
 */

namespace Tests\eXpansion\Framework\Core\Helpers;

use eXpansion\Framework\Core\Helpers\Translations;
use Tests\eXpansion\Framework\Core\TestCore;

class TranslationsTest extends TestCore
{
    public function testGetTranslation()
    {
        $translationHelper = $this->getTranslationHelper();

        $tranlationEn = $translationHelper->getTranslation('expansion_core.test', ['%test%' => 'TOTO'], 'en');
        $tranlationFr = $translationHelper->getTranslation('expansion_core.test', ['%test%' => 'TOTO'], 'fr');
        $tranlationUn = $translationHelper->getTranslation('expansion_core.test', ['%test%' => 'TOTO'], 'tt');

        $this->assertEquals("This is a test translation : TOTO", $tranlationEn);
        $this->assertEquals("Ceci est une trad de test : TOTO", $tranlationFr);
        // And default should be in english.
        $this->assertEquals("This is a test translation : TOTO", $tranlationUn);

        // test color
        $colorCodes = $this->container->getParameter('expansion.config.core_chat_color_codes');
        $testColor = $colorCodes['test'];

        $glyphCodes = $this->container->getParameter('expansion.config.core_chat_glyph_icons');
        $testGlyph = $glyphCodes['test'];

        $colorEn = $translationHelper->getTranslation('expansion_core.test_color', ['%test%' => 'TOTO'], 'en');
        $this->assertEquals('$z'.$testColor.'This is a test translation : TOTO', $colorEn);

        $glyphEn = $translationHelper->getTranslation('expansion_core.test_glyph', ['%test%' => 'TOTO'], 'en');
        $this->assertEquals($testGlyph.'This is a test translation : TOTO', $glyphEn);

        $colorglyphEn = $translationHelper->getTranslation('expansion_core.test_color_glyph', ['%test%' => 'TOTO'],
            'en');
        $this->assertEquals('$z'.$testColor.$testGlyph.'This is a test translation : TOTO', $colorglyphEn);

    }

    public function testGetTranslations()
    {
        $translationHelper = $this->getTranslationHelper();

        $tranlationEn = $translationHelper->getTranslations('expansion_core.test', ['%test%' => 'TOTO']);
        $this->assertEquals(
            [
                0 => ['Lang' => 'fr', 'Text' => "Ceci est une trad de test : TOTO"],
                1 => ['Lang' => 'de', 'Text' => "This is a test translation : TOTO"],
                2 => ['Lang' => 'fi', 'Text' => "Tämä on testikäännös : TOTO"],
                3 => ['Lang' => 'nl', 'Text' => "This is a test translation : TOTO"],
                4 => ['Lang' => 'en', 'Text' => "This is a test translation : TOTO"],
            ],
            $tranlationEn
        );
    }

    /**
     * @return Translations
     */
    protected function getTranslationHelper()
    {
        return $this->container->get('expansion.helper.translations');
    }

}
