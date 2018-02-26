<?php

namespace eXpansion\Framework\Core\Helpers;

use eXpansion\Framework\Core\Model\Helpers\ChatNotificationInterface;
use eXpansion\Framework\Core\Model\UserGroups\Group;
use eXpansion\Framework\Core\Services\Console;
use eXpansion\Framework\Core\Storage\PlayerStorage;
use Maniaplanet\DedicatedServer\Connection;
use Maniaplanet\DedicatedServer\InvalidArgumentException;
use Maniaplanet\DedicatedServer\Xmlrpc\UnknownPlayerException;
use Psr\Log\LoggerInterface;

/**
 * Class ChatNotification
 *
 * @package eXpansion\Framework\Core\Helpers;
 * @author  oliver de Cramer <oliverde8@gmail.com>
 */
class ChatNotification implements ChatNotificationInterface
{
    /** @var  Connection */
    protected $connection;

    /** @var Translations */
    protected $translations;

    /** @var PlayerStorage */
    protected $playerStorage;

    /** @var Console */
    protected $console;
    /**
     * @var LoggerInterface
     */
    private $logger;

    /**
     * ChatNotification constructor.
     *
     * @param Connection      $connection
     * @param Translations    $translations
     * @param PlayerStorage   $playerStorage
     * @param Console         $console
     * @param LoggerInterface $logger
     */
    public function __construct(
        Connection $connection,
        Translations $translations,
        PlayerStorage $playerStorage,
        Console $console,
        LoggerInterface $logger
    ) {
        $this->connection = $connection;
        $this->translations = $translations;
        $this->playerStorage = $playerStorage;
        $this->console = $console;
        $this->logger = $logger;
    }

    /**
     * Send message.
     *
     * @param string                     $messageId
     * @param string|string[]|Group|null $to
     * @param string[]                   $parameters
     */
    public function sendMessage($messageId, $to = null, $parameters = [])
    {
        $message = $messageId;

        if (is_string($to)) {
            $player = $this->playerStorage->getPlayerInfo($to);
            $message = $this->translations->getTranslation($messageId, $parameters, strtolower($player->getLanguage()));
        }

        if (is_array($to)) {
            $to = implode(",", $to);
            $message = $this->translations->getTranslations($messageId, $parameters);
        }

        if ($to instanceof Group) {
            $to = implode(",", $to->getLogins());
            $message = $this->translations->getTranslations($messageId, $parameters);
        }

        if ($to === null || $to instanceof Group) {
            $message = $this->translations->getTranslations($messageId, $parameters);
            $this->console->writeln(end($message)['Text']);
        }

        try {
            $this->connection->chatSendServerMessage($message, $to);
        } catch (UnknownPlayerException $e) {
            $this->logger->info("can't send chat message: $message", ["to" => $to, "exception" => $e]);
            // Nothing to do, it happens.
        } catch (InvalidArgumentException $ex) {
            // Nothing to do
        }
    }

    /**
     * Return messageId with arguments as a string
     * Usage: used for retrieving partials for chat messages
     *  * defaults to English locale, without parameters
     *
     * @param string $messageId
     * @param array  $parameters
     * @param string $locale
     * @return string
     */
    public function getMessage($messageId, $parameters = [], $locale = "en")
    {
        return $this->translations->getTranslation($messageId, $parameters, $locale);
    }

    /**
     * Return messageId with arguments as a string
     * Usage: used for retrieving partials for chat messages
     *  * defaults to English locale, without parameters
     *
     * @param string $messageId
     * @param array  $parameters
     * @param string $locale
     * @return string[]
     */
    public function getMessages($messageId, $parameters = [])
    {
        return $this->translations->getTranslations($messageId, $parameters);
    }

}
