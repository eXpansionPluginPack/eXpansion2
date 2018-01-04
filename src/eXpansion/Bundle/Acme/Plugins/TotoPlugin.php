<?php

namespace eXpansion\Bundle\Acme\Plugins;

use eXpansion\Bundle\Acme\Plugins\Gui\WindowFactory;
use eXpansion\Framework\Core\DataProviders\Listener\ListenerInterfaceExpApplication;
use eXpansion\Framework\Core\Model\UserGroups\Group;
use eXpansion\Framework\Core\Plugins\StatusAwarePluginInterface;
use eXpansion\Framework\Core\Services\Console;

/**
 * TotoPlugin is a test plugin to be removed.
 *
 * @package eXpansion\Framework\Core\Plugins
 */
class TotoPlugin implements ListenerInterfaceExpApplication, StatusAwarePluginInterface
{
    /** @var Console */
    protected $console;

    /** @var WindowFactory */
    protected $mlFactory;

    /** @var Group */
    protected $playersGroup;

    function __construct(
        Group $players,
        Console $console,
        WindowFactory $mlFactory
    ) {
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
            foreach ($this->playersGroup->getLogins() as $login) {
//                $this->mlFactory->create($login);
            }
        } else {
            $this->mlFactory->destroy($this->playersGroup);
        }
    }

    /**
     * called at eXpansion init
     *
     * @return void
     */
    public function onApplicationInit()
    {
        // do nothing
    }

    /**
     * called when init is done and callbacks are enabled
     *
     * @return void
     */
    public function onApplicationReady()
    {
        // $this->mlFactory->create($this->playersGroup);
    }

    /**
     * called when requesting application stop
     *
     * @return void
     */
    public function onApplicationStop()
    {
        // do nothing
    }
}
