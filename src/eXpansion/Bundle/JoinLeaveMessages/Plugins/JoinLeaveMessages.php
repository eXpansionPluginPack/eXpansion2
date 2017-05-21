<?php

namespace eXpansion\Bundle\JoinLeaveMessages\Plugins;

use eXpansion\Framework\Core\DataProviders\Listener\PlayerDataListenerInterface;
use eXpansion\Framework\Core\Helpers\ChatNotification;
use eXpansion\Framework\Core\Services\Console;
use eXpansion\Framework\Core\Storage\Data\Player;
use Maniaplanet\DedicatedServer\Connection;

class JoinLeaveMessages implements PlayerDataListenerInterface
{
    /** @var Connection */
    protected $connection;
    /** @var Console */
    protected $console;
    /** @var bool $enabled is output enabled */
    private $enabled = true;
    /**
     * @var ChatNotification $chat
     */
    protected $chat;

    /**
     * JoinLeaveMessages constructor.
     *
     * @param Connection $connection
     * @param Console $console
     */
    public function __construct(Connection $connection, Console $console, ChatNotification $chatNotification)
    {
        $this->connection = $connection;
        $this->console = $console;
        $this->chat = $chatNotification;
    }

//#region Callbacks

    /**
     * @inheritdoc
     */
    public function onRun()
    {
        // @todo make this callback work!
        $this->enabled = true;
    }

    /**
     * @inheritdoc
     */
    public function onPlayerConnect(Player $player)
    {
        $this->chat->sendMessage(
            "expansion_join_leave_messages.connect",
            null,
            ["%nickname%" => $player->getNickName(),
                "%login%" => $player->getLogin()
            ]);
    }

    /**
     * @inheritdoc
     */
    public function onPlayerDisconnect(Player $player, $disconnectionReason)
    {
        $this->chat->sendMessage(
            "expansion_join_leave_messages.disconnect",
            null,
            ["%nickname%" => $player->getNickName(),
             "%login%" => $player->getLogin()
             ]);
    }

    /**
     * @inheritdoc
     */
    public function onPlayerInfoChanged(Player $oldPlayer, Player $player)
    {
    }

    /**
     * @inheritdoc
     */
    public function onPlayerAlliesChanged(Player $oldPlayer, Player $player)
    {
    }
//#endregion

}
