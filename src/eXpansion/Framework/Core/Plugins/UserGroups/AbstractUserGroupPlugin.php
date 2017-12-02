<?php


namespace eXpansion\Framework\Core\Plugins\UserGroups;

use eXpansion\Framework\GameManiaplanet\DataProviders\Listener\ListenerInterfaceMpLegacyPlayer;
use eXpansion\Framework\Core\Model\UserGroups\Group;
use eXpansion\Framework\Core\Plugins\StatusAwarePluginInterface;
use eXpansion\Framework\Core\Storage\Data\Player;
use eXpansion\Framework\Core\Storage\PlayerStorage;

abstract class AbstractUserGroupPlugin implements ListenerInterfaceMpLegacyPlayer, StatusAwarePluginInterface
{
    /** @var Group  */
    protected $userGroup;

    /** @var PlayerStorage  */
    protected $playerStorage;

    /**
     * AbstractUserGroupPlugin constructor.
     *
     * @param Group $userGroup
     */
    public function __construct(Group $userGroup, PlayerStorage $playerStorage)
    {
        $this->userGroup = $userGroup;
        $this->playerStorage = $playerStorage;
    }

    /**
     * @inheritdoc
     */
    public function setStatus($status)
    {
        if ($status && !empty($this->playerStorage->getOnline())) {
            foreach ($this->playerStorage->getOnline() as $player) {
                $this->onPlayerConnect($player);
            }
        }
    }

    /**
     * @inheritdoc
     */
    public function onPlayerDisconnect(Player $player, $disconnectionReason)
    {
        $this->userGroup->removeLogin($player->getLogin());
    }

    /**
     * @inheritdoc
     */
    public function onPlayerInfoChanged(Player $oldPlayer, Player $player)
    {
        // By default nothing.
    }

    /**
     * @inheritdoc
     */
    public function onPlayerAlliesChanged(Player $oldPlayer, Player $player)
    {
        // By default nothing.
    }
}
