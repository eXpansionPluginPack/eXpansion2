<?php

namespace eXpansion\Bundle\JoinLeaveMessages\Plugins;

use eXpansion\Framework\AdminGroups\Helpers\AdminGroups;
use eXpansion\Framework\Core\Helpers\ChatNotification;
use eXpansion\Framework\Core\Helpers\Countries;
use eXpansion\Framework\Core\Helpers\Version;
use eXpansion\Framework\Core\Services\Console;
use eXpansion\Framework\Core\Storage\Data\Player;
use eXpansion\Framework\GameManiaplanet\DataProviders\Listener\ListenerInterfaceMpLegacyPlayer;

class JoinLeaveMessages implements ListenerInterfaceMpLegacyPlayer
{
    /** @var Console */
    protected $console;

    /** @var ChatNotification $chat */
    protected $chatNotification;

    /** @var AdminGroups */
    protected $adminGroups;

    /** @var Countries */
    protected $countries;

    /** @var Version */
    protected $version;

    /**
     * JoinLeaveMessages constructor.
     *
     * @param Console $console
     * @param ChatNotification $chatNotification
     * @param AdminGroups $adminGroups
     * @param Countries $countries
     * @param Version $version
     */
    public function __construct(
        Console $console,
        ChatNotification $chatNotification,
        AdminGroups $adminGroups,
        Countries $countries,
        Version $version
    ) {
        $this->console = $console;
        $this->chatNotification = $chatNotification;
        $this->adminGroups = $adminGroups;
        $this->countries = $countries;
        $this->version = $version;
    }

    /**
     * @inheritdoc
     */
    public function onPlayerConnect(Player $player)
    {
        $this->chatNotification->sendMessage(
            'expansion_join_leave_messages.applicationGreeter',
            $player->getLogin(),
            ["%version%" => $this->version->getExpansionVersion()]);

        $groupName = $this->adminGroups->getLoginUserGroups($player->getLogin())->getName();
        $this->chatNotification->sendMessage(
            "expansion_join_leave_messages.connect",
            null,
            [
                "%group%" => $this->adminGroups->getGroupLabel($groupName),
                "%nickname%" => $player->getNickName(),
                "%login%" => $player->getLogin(),
                "%path%" => $this->countries->parseCountryFromPath($player->getPath()),
                "%ladder%" => $player->getLadderScore(),
            ]);
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
                "%path%" => $this->countries->parseCountryFromPath($player->getPath()),
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
