<?php

namespace eXpansion\Framework\Core\Helpers;

use Symfony\Component\Translation\Translator;


/**
 * Class Translations
 *
 * @package eXpansion\Framework\Core\Services;
 * @author oliver de Cramer <oliverde8@gmail.com>
 */
class Translations
{
    /** @var Translator */
    protected $translator;

    /** @var string[] List of supported locales. */
    protected $supportedLocales;

    /** @var string[] */
    protected $replacementPatterns = [];

    /**
     * Translations constructor.
     *
     * @param       $translator
     * @param array $supportedLocales
     * @param       $colorCodes
     * @param       $glyphIcons
     */
    public function __construct(
        $translator,
        array $supportedLocales,
        $colorCodes,
        $glyphIcons
    )
    {
        $this->translator = $translator;
        $this->supportedLocales = $supportedLocales;

        foreach ($colorCodes as $code => $colorCode) {
            $this->replacementPatterns["{".$code."}"] = '$z'.$colorCode;
        }

        foreach ($glyphIcons as $name => $icon) {
            $this->replacementPatterns["|".$name."|"] = $icon;
        }
    }

    /**
     * Get translated message.
     *
     * @param string $id
     * @param array $parameters
     * @param $locale
     *
     * @return mixed
     */
    public function getTranslation($id, $parameters = [], $locale = null)
    {
        $parameters = array_merge($this->replacementPatterns, $parameters);

        return $this->translator->trans($id, $parameters, null, $locale);
    }

    /**
     * Get list of translations.
     *
     * @TODO optimize by preparing the messages before.
     *
     * @param $id
     * @param $parameters
     *
     * @return array
     */
    public function getTranslations($id, $parameters = [])
    {
        $messages = [];
        // $parameters = array_merge($this->replacementPatterns, $parameters);

        foreach ($this->supportedLocales as $locale) {
            $message = $this->getTranslation($id, $parameters, $locale);
            $messages[] = array(
                "Lang" => lcfirst($locale),
                "Text" => $message,
            );
        }

        return $messages;
    }
}
