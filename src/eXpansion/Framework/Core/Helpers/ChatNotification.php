<?php

namespace eXpansion\Framework\Core\Helpers;

use eXpansion\Framework\Core\Model\Helpers\ChatNotificationInterface;
use eXpansion\Framework\Core\Storage\PlayerStorage;
use Maniaplanet\DedicatedServer\Connection;

/**
 * Class ChatNotification
 *
 * @package eXpansion\Framework\Core\Helpers;
 * @author oliver de Cramer <oliverde8@gmail.com>
 */
class ChatNotification implements ChatNotificationInterface
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
    public function __construct(
        Connection $connection,
        Translations $translations,
        PlayerStorage $playerStorage
    )
    {
        $this->connection = $connection;
        $this->translations = $translations;
        $this->playerStorage = $playerStorage;
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
        if (is_string($to)) {
            $player = $this->playerStorage->getPlayerInfo($to);
            $message = $this->translations->getTranslation($messageId, $parameters, strtolower($player->getLanguage()));
        } else {
            $message = $this->translations->getTranslations($messageId, $parameters);
        }

        $this->connection->chatSendServerMessage($message, $to);
    }
}
