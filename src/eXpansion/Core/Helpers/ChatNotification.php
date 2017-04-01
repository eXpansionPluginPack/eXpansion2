<?php

namespace eXpansion\Core\Helpers;

use eXpansion\Core\Storage\PlayerStorage;
use Maniaplanet\DedicatedServer\Connection;

/**
 * Class ChatNotification
 *
 * @package eXpansion\Core\Helpers;
 * @author oliver de Cramer <oliverde8@gmail.com>
 */
class ChatNotification
{
    /** @var  Connection */
    protected $connection;

    /** @var Translations */
    protected $translations;

    /** @var PlayerStorage */
    protected $playerStorage;

    /**
     * ChatNotification constructor.
     *
     * @param Connection $connection
     * @param Translations $translations
     */
    public function __construct(Connection $connection, Translations $translations, PlayerStorage $playerStorage)
    {
        $this->connection = $connection;
        $this->translations = $translations;
        $this->playerStorage = $playerStorage;
    }

    /**
     * Send message.
     *
     * @param $messageId
     * @param $to
     * @param $parameters
     */
    public function sendMessage($messageId, $to = null, $parameters = [])
    {
        if (is_string($to)) {
            $player = $this->playerStorage->getPlayerInfo($to);
            $message = $this->translations->getTranslation($messageId, $parameters, strtolower($player->getLanguage()));

            // @TODO process color codes.
        } else {
            $message = $this->translations->getTranslations($messageId, $parameters);

            // @TODO process color codes.
        }

        $this->connection->chatSendServerMessage($message, $to);
    }
}