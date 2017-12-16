<?php

namespace eXpansion\Bundle\JoinLeaveMessages\Plugins;

use eXpansion\Framework\AdminGroups\Helpers\AdminGroups;
use eXpansion\Framework\Core\Helpers\ChatNotification;
use eXpansion\Framework\Core\Services\Application\AbstractApplication;
use eXpansion\Framework\Core\Services\Console;
use eXpansion\Framework\Core\Storage\Data\Player;
use eXpansion\Framework\GameManiaplanet\DataProviders\Listener\ListenerInterfaceMpLegacyPlayer;
use Maniaplanet\DedicatedServer\Connection;

class JoinLeaveMessages implements ListenerInterfaceMpLegacyPlayer
{
    /** @var Connection */
    protected $connection;

    /** @var Console */
    protected $console;

    /** @var ChatNotification $chat */
    protected $chatNotification;

    /** @var AdminGroups */
    protected $adminGroups;

    /**
     * JoinLeaveMessages constructor.
     *
     * @param Connection       $connection
     * @param Console          $console
     * @param ChatNotification $chatNotification
     * @param AdminGroups      $adminGroups
     */
    public function __construct(
        Connection $connection,
        Console $console,
        ChatNotification $chatNotification,
        AdminGroups $adminGroups
    ) {
        $this->connection = $connection;
        $this->console = $console;
        $this->chatNotification = $chatNotification;
        $this->adminGroups = $adminGroups;
    }

    /**
     * @inheritdoc
     */
    public function onPlayerConnect(Player $player)
    {
        $groupName = $this->adminGroups->getLoginUserGroups($player->getLogin())->getName();
        $this->chatNotification->sendMessage(
            "expansion_join_leave_messages.connect",
            null,
            [
                "%group%" => $this->adminGroups->getGroupLabel($groupName),
                "%nickname%" => $player->getNickName(),
                "%login%" => $player->getLogin(),
                "%path%" => $player->getPath(),
                "%ladder%" => $player->getLadderScore(),
            ]);

        $this->chatNotification->sendMessage(
            'expansion_join_leave_messages.applicationGreeter',
            $player->getLogin(),
            ["%version%" => AbstractApplication::EXPANSION_VERSION]);

    }

    /**
     * @inheritdoc
     */
    public function onPlayerDisconnect(Player $player, $disconnectionReason)
    {
        $groupName = $this->adminGroups->getLoginUserGroups($player->getLogin())->getName();

        $this->chatNotification->sendMessage(
            "expansion_join_leave_messages.disconnect",
            null,
            [
                "%group%" => $this->adminGroups->getGroupLabel($groupName),
                "%nickname%" => $player->getNickName(),
                "%login%" => $player->getLogin(),
                "%path%" => $player->getPath(),
                "%ladder%" => $player->getLadderScore(),
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
}
