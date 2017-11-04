<?php


namespace eXpansion\Bundle\Menu\Plugins;

use eXpansion\Bundle\Menu\Plugins\Gui\MenuFactory;
use eXpansion\Framework\AdminGroups\Helpers\AdminGroups;
use eXpansion\Framework\Core\DataProviders\Listener\ListenerInterfaceMpLegacyMap;
use eXpansion\Framework\Core\Model\UserGroups\Group;
use eXpansion\Framework\Core\Plugins\StatusAwarePluginInterface;
use eXpansion\Framework\Core\Storage\PlayerStorage;
use Maniaplanet\DedicatedServer\Structures\Map;


/**
 * Class Menu
 *
 * @package eXpansion\Bundle\Menu\Plugins;
 * @author  oliver de Cramer <oliverde8@gmail.com>
 */
class Menu implements StatusAwarePluginInterface, ListenerInterfaceMpLegacyMap
{
    /** @var  PlayerStorage */
    protected $playerStorage;

    /** @var MenuFactory */
    protected $menuGuiFactory;

    /**
     * Menu constructor.
     *
     * @param Group $userGroups
     * @param MenuFactory $menuGuiFactory
     */
    public function __construct(PlayerStorage $playerStorage, MenuFactory $menuGuiFactory)
    {
        $this->playerStorage = $playerStorage;
        $this->menuGuiFactory = $menuGuiFactory;
    }


    /**
     * Set the status of the plugin
     *
     * @param boolean $status
     *
     * @return $this
     */
    public function setStatus($status)
    {
        if ($status) {
            $this->displayMenu();
        }

        return $this;
    }

    /**
     * Display a menu for each user group
     */
    protected function displayMenu()
    {
        foreach ($this->playerStorage->getOnline() as $player) {
            $this->menuGuiFactory->create($player);
        }
    }


    /**
     * @inheritdoc
     */
    public function onBeginMap(Map $map)
    {
    }

    /**
     * @inheritdoc
     */
    public function onEndMap(Map $map)
    {
    }
}
