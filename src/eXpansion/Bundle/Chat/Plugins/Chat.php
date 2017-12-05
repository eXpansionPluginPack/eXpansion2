<?php

namespace eXpansion\Bundle\Chat\Plugins;

use eXpansion\Bundle\Chat\Plugins\Gui\Widget\ChatWidgetFactory;
use eXpansion\Bundle\Chat\Plugins\Gui\Widget\UpdateChatWidgetFactory;
use eXpansion\Framework\AdminGroups\Helpers\AdminGroups;
use eXpansion\Framework\Core\DataProviders\Listener\ListenerInterfaceExpApplication;
use eXpansion\Framework\Core\DataProviders\Listener\ListenerInterfaceExpConsole;
use eXpansion\Framework\Core\DataProviders\Listener\ListenerInterfaceExpTimer;
use eXpansion\Framework\Core\Model\UserGroups\Group;
use eXpansion\Framework\Core\Storage\Data\Player;
use eXpansion\Framework\Core\Storage\PlayerStorage;
use eXpansion\Framework\GameManiaplanet\DataProviders\Listener\ListenerInterfaceMpLegacyPlayer;
use Maniaplanet\DedicatedServer\Connection;


class Chat implements ListenerInterfaceExpApplication, ListenerInterfaceMpLegacyPlayer,
    ListenerInterfaceExpConsole, ListenerInterfaceExpTimer
{
    /** @var bool */
    protected $updateRequired = false;

    /** @var Connection */
    protected $connection;
    /**
     * @var PlayerStorage
     */
    private $playerStorage;
    /**
     * @var Group
     */
    private $players;
    /**
     * @var ChatWidgetFactory
     */
    private $chatWidget;
    /**
     * @var UpdateChatWidgetFactory
     */
    private $updateChatWidget;
    /**
     * @var AdminGroups
     */
    private $adminGroups;

    /**
     * Chat constructor.
     *
     * @param Group $players
     * @param Connection $connection
     * @param PlayerStorage $playerStorage
     * @param AdminGroups $adminGroups
     * @param ChatWidgetFactory $chatWidget
     * @param UpdateChatWidgetFactory $updateChatWidget
     */
    public function __construct(
        Group $players,
        Connection $connection,
        PlayerStorage $playerStorage,
        AdminGroups $adminGroups,
        ChatWidgetFactory $chatWidget,
        UpdateChatWidgetFactory $updateChatWidget
    ) {
        $this->connection = $connection;
        $this->playerStorage = $playerStorage;
        $this->players = $players;
        $this->chatWidget = $chatWidget;
        $this->updateChatWidget = $updateChatWidget;
        $this->adminGroups = $adminGroups;
    }

    /**
     * called at eXpansion init
     *
     * @return void
     */
    public function onApplicationInit()
    {
        //
    }


    public function onApplicationReady()
    {
        $this->chatWidget->create($this->players);
        foreach ($this->adminGroups->getUserGroups() as $group) {
            if ($this->adminGroups->hasGroupPermission($group->getName(), "console")) {
                $this->updateRequired = true;
                $this->updateChatWidget->create($group);
            }
        }
    }

    /**
     * called when requesting application stop
     *
     * @return void
     */
    public function onApplicationStop()
    {
        //
    }

    public function onPlayerConnect(Player $player)
    {

    }

    public function onPlayerDisconnect(Player $player, $disconnectionReason)
    {
        //
    }

    public function onPlayerInfoChanged(Player $oldPlayer, Player $player)
    {
        //
    }

    public function onPlayerAlliesChanged(Player $oldPlayer, Player $player)
    {
        //
    }

    public function onConsoleMessage($message)
    {
        $this->updateRequired = true;
        $this->updateChatWidget->updateConsole($message);
    }

    public function onPreLoop()
    {
        if ($this->updateRequired) {
            $this->updateRequired = false;
            foreach ($this->adminGroups->getUserGroups() as $group) {
                if ($this->adminGroups->hasGroupPermission($group->getName(), "console")) {
                    $this->updateChatWidget->update($group);
                }
            }
        }
    }

    public function onPostLoop()
    {
        //
    }

    public function onEverySecond()
    {
        //
    }
}
