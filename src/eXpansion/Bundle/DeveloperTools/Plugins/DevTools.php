<?php

namespace eXpansion\Bundle\DeveloperTools\Plugins;

use eXpansion\Bundle\DeveloperTools\Plugins\Gui\MemoryWidget;
use eXpansion\Bundle\DeveloperTools\Plugins\Gui\MemoryWidgetFactory;
use eXpansion\Framework\Core\DataProviders\Listener\ListenerInterfaceExpApplication;
use eXpansion\Framework\Core\DataProviders\Listener\ListenerInterfaceExpTimer;
use eXpansion\Framework\Core\Helpers\Time;
use eXpansion\Framework\Core\Model\UserGroups\Group;
use eXpansion\Framework\Core\Plugins\StatusAwarePluginInterface;
use eXpansion\Framework\Core\Services\Console;
use eXpansion\Framework\Core\Services\DedicatedConnection\Factory;
use Maniaplanet\DedicatedServer\Connection;

class DevTools implements ListenerInterfaceExpApplication, ListenerInterfaceExpTimer, StatusAwarePluginInterface
{

    public $connectQueue = 0;

    /** @var Factory */
    protected $factory;

    /** @var Console */
    protected $console;

    /** @var Time */
    protected $time;

    /** @var float|int */
    private $previousMemoryValue = 0;

    /** @var int */
    private $startMemValue = 0;

    /**
     * @var MemoryWidget
     */
    private $memoryWidget;
    /**
     * @var Group
     */
    private $allPlayersGroup;


    /**
     * Test constructor.
     * @param Factory   $factory
     * @param Console      $console
     * @param Time         $time
     * @param Group        $players
     * @param MemoryWidget $memoryWidget
     */
    function __construct(
        Factory $factory,
        Console $console,
        Time $time,
        Group $players,
        MemoryWidget $memoryWidget
    ) {
        $this->factory = $factory;
        $this->console = $console;
        $this->time = $time;
        $this->memoryWidget = $memoryWidget;
        $this->allPlayersGroup = $players;
    }

    public function onPreLoop()
    {
        if ($this->connectQueue > 0) {
            $this->factory->getConnection()->connectFakePlayer();
            $this->connectQueue--;
        }
    }

    public function onPostLoop()
    {

    }

    public function onEverySecond()
    {
        $mem = memory_get_usage(false);

        if ($this->previousMemoryValue != $mem) {
            $diff = ($mem - $this->previousMemoryValue);
            $msg = 'Memory: $0d0'.round($mem / 1024)."kb ";

            if ($this->previousMemoryValue < $mem) {
                $msg .= ' $f00+'.round($diff / 1024)."kb";
            } else {
                $msg .= ' $0f0'.round($diff / 1024)."kb";
            }

            $diff = ($mem - $this->startMemValue);
            if ($this->startMemValue > $mem) {
                $msg .= ' $0f0('.round($diff / 1024)."kb)";
            } else {
                $msg .= ' $f00('.round($diff / 1024)."kb)";
            }

            $this->memoryWidget->setMemoryMessage($msg);
        //    $this->memoryWidget->update($this->allPlayersGroup);
            $this->console->writeln($msg);
        }

        $this->previousMemoryValue = $mem;

    }

    /**
     * Set the status of the plugin
     *
     * @param boolean $status
     *
     * @return void
     */
    public function setStatus($status)
    {
        if ($status) {
            $this->startMemValue = memory_get_usage(false);
            $this->memoryWidget->create($this->allPlayersGroup);
        } else {
            $this->memoryWidget->destroy($this->allPlayersGroup);
        }
    }

    /**
     * called at eXpansion init
     *
     * @return void
     */
    public function onApplicationInit()
    {

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

    }
}
