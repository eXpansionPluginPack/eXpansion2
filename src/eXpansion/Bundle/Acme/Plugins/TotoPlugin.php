<?php

namespace eXpansion\Bundle\Acme\Plugins;

use eXpansion\Bundle\Acme\Plugins\Gui\TotoWindowFactory;
use eXpansion\Framework\Core\DataProviders\Listener\ListenerInterfaceExpApplication;
use eXpansion\Framework\Core\Helpers\Time;
use eXpansion\Framework\Core\Model\UserGroups\Group;
use eXpansion\Framework\Core\Plugins\StatusAwarePluginInterface;
use eXpansion\Framework\Core\Services\Console;
use eXpansion\Framework\Notifications\Services\Notifications;

/**
 * TotoPlugin is a test plugin to be removed.
 *
 * @package eXpansion\Framework\Core\Plugins
 */
class TotoPlugin implements ListenerInterfaceExpApplication, StatusAwarePluginInterface
{
    /** @var Console */
    protected $console;

    /** @var TotoWindowFactory */
    protected $mlFactory;

    /** @var Group */
    protected $playersGroup;
    /**
     * @var Notifications
     */
    private $notifications;
    /**
     * @var Time
     */
    private $time;

    /**
     * TotoPlugin constructor.
     * @param Group             $players
     * @param Console           $console
     * @param Notifications     $notifications
     * @param TotoWindowFactory $mlFactory
     * @param Time              $time
     */
    function __construct(
        Group $players,
        Console $console,
        Notifications $notifications,
        TotoWindowFactory $mlFactory,
        Time $time
    ) {
        $this->console = $console;
        $this->playersGroup = $players;
        $this->notifications = $notifications;
        $this->mlFactory = $mlFactory;
        $this->time = $time;
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
