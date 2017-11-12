<?php


namespace eXpansion\Bundle\Menu\Plugins;

use eXpansion\Bundle\Menu\Plugins\Gui\MenuContentFactory;
use eXpansion\Bundle\Menu\Plugins\Gui\MenuFactory;
use eXpansion\Framework\AdminGroups\Helpers\AdminGroups;
use eXpansion\Framework\Core\DataProviders\Listener\ListenerInterfaceMpLegacyMap;
use eXpansion\Framework\Core\Plugins\StatusAwarePluginInterface;
use Maniaplanet\DedicatedServer\Structures\Map;


/**
 * Class Menu
 *
 * @package eXpansion\Bundle\Menu\Plugins;
 * @author  oliver de Cramer <oliverde8@gmail.com>
 */
class Menu implements StatusAwarePluginInterface, ListenerInterfaceMpLegacyMap
{
    /** @var  AdminGroups */
    protected $adminGroups;

    /** @var MenuFactory */
    protected $menuGuiFactory;

    /**
     * Menu constructor.
     *
     * @param AdminGroups $adminGroups
     * @param MenuFactory $menuGuiFactory
     */
    public function __construct(
        AdminGroups $adminGroups,
        MenuFactory $menuGuiFactory,
        MenuContentFactory $menuContentFactory
    ) {
        $this->adminGroups = $adminGroups;
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
        foreach ($this->adminGroups->getUserGroups() as $userGroup) {
            $this->menuGuiFactory->create($userGroup);
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
