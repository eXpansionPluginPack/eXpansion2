<?php

namespace eXpansion\Bundle\Acme\Plugins;

use eXpansion\Framework\Core\DataProviders\Listener\ListenerInterfaceMpLegacyChat;
use eXpansion\Framework\Core\Model\UserGroups\Group;
use eXpansion\Framework\Core\Plugins\Gui\ManialinkFactory;
use eXpansion\Framework\Core\Plugins\StatusAwarePluginInterface;
use eXpansion\Framework\Core\Services\Console;
use eXpansion\Framework\Core\Storage\Data\Player;

/**
 * TotoPlugin is a test plugin to be removed.
 *
 * @package eXpansion\Framework\Core\Plugins
 */
class TotoPlugin implements StatusAwarePluginInterface
{
    /** @var Console  */
    protected $console;

    /** @var ManialinkFactory */
    protected $mlFactory;

    /** @var Group  */
    protected $playersGroup;

    function __construct(
        Console $console,
        ManialinkFactory $mlFactory,
        Group $players
    )
    {
        $this->console = $console;
        $this->mlFactory = $mlFactory;
        $this->playersGroup = $players;
    }

    /**
     * Set the status of the plugin
     *
     * @param boolean $status
     *
     * @return null
     */
    public function setStatus($status)
    {
        if ($status) {
            $this->mlFactory->create($this->playersGroup);
        } else {
            $this->mlFactory->destroy($this->playersGroup);
        }
    }
}
