<?php

namespace eXpansion\Bundle\Chat\Plugins;

use eXpansion\Bundle\Chat\Plugins\Gui\Widget\ChatWidgetFactory;
use eXpansion\Bundle\Chat\Plugins\Gui\Widget\UpdateChatWidgetFactory;
use eXpansion\Framework\Core\DataProviders\Listener\ListenerInterfaceExpApplication;
use eXpansion\Framework\Core\Model\UserGroups\Group;
use eXpansion\Framework\Core\Storage\Data\Player;
use eXpansion\Framework\Core\Storage\PlayerStorage;
use eXpansion\Framework\GameManiaplanet\DataProviders\Listener\ListenerInterfaceMpLegacyPlayer;
use Maniaplanet\DedicatedServer\Connection;


class Chat implements ListenerInterfaceExpApplication, ListenerInterfaceMpLegacyPlayer
{
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
     * Chat constructor.
     *
     * @param Group $players
     * @param Connection $connection
     * @param PlayerStorage $playerStorage
     * @param ChatWidgetFactory $chatWidget
     * @param UpdateChatWidgetFactory $updateChatWidget
     */
    public function __construct(
        Group $players,
        Connection $connection,
        PlayerStorage $playerStorage,
        ChatWidgetFactory $chatWidget,
        UpdateChatWidgetFactory $updateChatWidget
    ) {
        $this->connection = $connection;
        $this->playerStorage = $playerStorage;
        $this->players = $players;
        $this->chatWidget = $chatWidget;
        $this->updateChatWidget = $updateChatWidget;
    }

    /**
     * called at eXpansion init
     *
     * @return void
     */
    public function onApplicationInit()
    {
        // TODO: Implement onApplicationInit() method.
    }

    /**
     * called when init is done and callbacks are enabled
     *
     * @return void
     */
    public function onApplicationReady()
    {
        $this->chatWidget->create($this->players);
    }

    /**
     * called when requesting application stop
     *
     * @return void
     */
    public function onApplicationStop()
    {
        // TODO: Implement onApplicationStop() method.
    }

    public function onPlayerConnect(Player $player)
    {

    }

    public function onPlayerDisconnect(Player $player, $disconnectionReason)
    {
        // TODO: Implement onPlayerDisconnect() method.
    }

    public function onPlayerInfoChanged(Player $oldPlayer, Player $player)
    {
        // TODO: Implement onPlayerInfoChanged() method.
    }

    public function onPlayerAlliesChanged(Player $oldPlayer, Player $player)
    {
        // TODO: Implement onPlayerAlliesChanged() method.
    }
}
