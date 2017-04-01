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

    protected $colorCodes = [];

    /**
     * ChatNotification constructor.
     *
     * @param Connection $connection
     * @param Translations $translations
     */
    public function __construct(
        Connection $connection,
        Translations $translations,
        PlayerStorage $playerStorage,
        $colorCodes
    ){
        $this->connection = $connection;
        $this->translations = $translations;
        $this->playerStorage = $playerStorage;

        foreach ($colorCodes as $code => $colorCode) {
            $this->colorCodes["{" . $code . "}"] = '$z' . $colorCode;
        }
    }

    /**
     * Send message.
     *
     * @param string $messageId
     * @param string|string[]|null $to
     * @param string[] $parameters
     */
    public function sendMessage($messageId, $to = null, $parameters = [])
    {
        $parameters = array_merge($this->colorCodes, $parameters);

        if (is_string($to)) {
            $player = $this->playerStorage->getPlayerInfo($to);
            $message = $this->translations->getTranslation($messageId, $parameters, strtolower($player->getLanguage()));
        } else {
            $message = $this->translations->getTranslations($messageId, $parameters);
        }

        $this->connection->chatSendServerMessage($message, $to);
    }
}