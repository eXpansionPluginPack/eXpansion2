<?php

namespace eXpansion\Bundle\JoinLeaveMessages\Plugins;

use eXpansion\Bundle\AdminChat\AdminChatBundle;
use eXpansion\Framework\AdminGroups\Helpers\AdminGroups;
use eXpansion\Framework\Core\DataProviders\Listener\ListenerInterfaceMpLegacyPlayer;
use eXpansion\Framework\Core\Helpers\ChatNotification;
use eXpansion\Framework\Core\Services\Console;
use eXpansion\Framework\Core\Storage\Data\Player;
use Maniaplanet\DedicatedServer\Connection;

class JoinLeaveMessages implements ListenerInterfaceMpLegacyPlayer
{
    /** @var Connection */
    protected $connection;
    /** @var Console */
    protected $console;
    /** @var bool $enabled is output enabled */
    private $enabled = true;
    /** @var ChatNotification $chat */
    protected $chat;
    /** @var AdminGroups */
    protected $adminGroups;

    /**
     * JoinLeaveMessages constructor.
     *
     * @param Connection $connection
     * @param Console $console
     */
    public function __construct(
        Connection $connection,
        Console $console,
        ChatNotification $chatNotification,
        AdminGroups $adminGroups
    ) {
        $this->connection = $connection;
        $this->console = $console;
        $this->chat = $chatNotification;
        $this->adminGroups = $adminGroups;
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
        $groupName = $this->adminGroups->getLoginUserGroups($player->getLogin())->getName();

        $this->chat->sendMessage(
            "expansion_join_leave_messages.connect",
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
    public function onPlayerDisconnect(Player $player, $disconnectionReason)
    {
        $groupName = $this->adminGroups->getLoginUserGroups($player->getLogin())->getName();

        $this->chat->sendMessage(
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
//#endregion

}
