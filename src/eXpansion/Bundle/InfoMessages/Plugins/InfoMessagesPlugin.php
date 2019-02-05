<?php

namespace eXpansion\Bundle\InfoMessages\Plugins;

use eXpansion\Bundle\InfoMessages\DependencyInjection\InfoMessagesExtension;
use eXpansion\Framework\Config\Model\IntegerConfig;
use eXpansion\Framework\Config\Model\TextListConfig;
use eXpansion\Framework\Config\Services\ConfigManagerInterface;
use eXpansion\Framework\Core\DataProviders\Listener\ListenerInterfaceExpTimer;
use eXpansion\Framework\Core\Helpers\Translations;
use eXpansion\Framework\Core\Services\DedicatedConnection\Factory;

/**
 * Class InfoMessagesPlugin
 *
 * @package eXpansion\Bundle\InfoMessages\Plugins;
 * @author  oliver de Cramer <oliverde8@gmail.com>
 */
class InfoMessagesPlugin implements ListenerInterfaceExpTimer
{
    /** @var IntegerConfig */
    protected $frequencyConfig;

    /** @var ConfigManagerInterface */
    protected $configManager;

    /** @var Factory */
    protected $factory;

    /** @var Translations */
    protected $translations;

    /** @var string[] */
    protected $supportedLocales;

    /** @var int */
    protected $lastSentTime;

    /**
     * InfoMessagesPlugin constructor.
     *
     * @param IntegerConfig          $frequencyConfig
     * @param ConfigManagerInterface $configManager
     * @param string[]               $supportedLocales
     */
    public function __construct(
        IntegerConfig $frequencyConfig,
        ConfigManagerInterface $configManager,
        Factory $factory,
        Translations $translations,
        array $supportedLocales
    ) {
        $this->frequencyConfig = $frequencyConfig;
        $this->configManager = $configManager;
        $this->factory = $factory;
        $this->translations = $translations;
        $this->supportedLocales = $supportedLocales;

        $this->lastSentTime = time();
    }

    /**
     * @inheritdoc
     */
    public function onEverySecond()
    {
        if ($this->lastSentTime + $this->frequencyConfig->get() < time()) {
            $this->lastSentTime = time();
            $this->sendRandomMessage();
        }
    }

    /**
     * Send a random information message to all players.
     */
    protected function sendRandomMessage()
    {
        $messagesPerLocale = [];

        foreach ($this->supportedLocales as $locale) {
            // Ideally the list of configs should have been passed in the construct, to achieve this we would need
            // to inject the config services throught the Extension. We have chose not to do this to simply simplify
            // the code to make it a bit easier for people that are new to symfony to understand.
            $values = $this->configManager->get(InfoMessagesExtension::CONFIG_PATH_PREFIX . $locale);

            if (count($values) > 0) {
                $messagesPerLocale[] = [
                    "Lang" => lcfirst($locale),
                    "Text" => $this->translations->getTranslation($values[rand(0, count($values) - 1)])
                ];
            }
        }

        if ($messagesPerLocale) {
            // Normally we should always use the ChatNotification helper, but in this cas we wish to bypass the
            // translations.
            $this->factory->getConnection()->chatSendServerMessage($messagesPerLocale);
        }
    }

    /**
     * @inheritdoc
     */
    public function onPreLoop()
    {
        // Nothing
    }

    /**
     * @inheritdoc
     */
    public function onPostLoop()
    {
        // Nothing
    }
}