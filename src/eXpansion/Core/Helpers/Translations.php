<?php

namespace eXpansion\Core\Helpers;

use Symfony\Component\Translation\Translator;


/**
 * Class Translations
 *
 * @package eXpansion\Core\Services;
 * @author oliver de Cramer <oliverde8@gmail.com>
 */
class Translations
{
    /** @var Translator */
    protected $translator;

    /** @var string[] List of supported locales. */
    protected $supportedLocales;

    /**
     * Translations constructor.
     *
     * @param $translator
     * @param array $supportedLocales
     */
    public function __construct($translator, array $supportedLocales)
    {
        $this->translator = $translator;
        $this->supportedLocales = $supportedLocales;
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
    public function getTranslations($id, $parameters)
    {
        $messages = [];

        foreach ($this->supportedLocales as $locale)
        {
            $message = $this->getTranslation($id, $parameters, $locale);
            $messages[] = array(
                "Lang" => lcfirst($locale),
                "Text" => $message,
            );
        }

        return $messages;
    }
}