<?php


namespace eXpansion\Framework\Core\Plugins;
use eXpansion\Framework\Core\DataProviders\Listener\ListenerInterfaceExpTimer;
use eXpansion\Framework\Core\Helpers\ChatNotification;
use eXpansion\Framework\Core\Services\Console;


/**
 * Class DevModeNotifier
 *
 * @package eXpansion\Framework\Core\Plugins;
 * @author  oliver de Cramer <oliverde8@gmail.com>
 */
class DevModeNotifier implements ListenerInterfaceExpTimer
{
    /** @var ChatNotification */
    protected $chatNotification;

    /** @var Console */
    protected $console;

    /** @var int How often notificaiton needs to be displayed */
    protected $interval = 300;

    /** @var int last time it was displayed. */
    protected $lastDisplayTime;

    /**
     * DevModeNotifier constructor.
     *
     * @param ChatNotification $chatNotification
     * @param Console         $console
     */
    public function __construct(ChatNotification $chatNotification, Console $console)
    {
        $this->chatNotification = $chatNotification;
        $this->console = $console;

        // Display once 30 seconds after start.
        $this->lastDisplayTime = time() - $this->interval + 10;
    }

    /**
     * @inheritdoc
     */
    public function onPreLoop()
    {
        // nothing
    }

    /**
     * @inheritdoc
     */
    public function onPostLoop()
    {
        // nothing
    }

    /**
     * @inheritdoc
     */
    public function onEverySecond()
    {
        if (time() > $this->lastDisplayTime + $this->interval) {
            $this->lastDisplayTime = time();

            $this->console->getSfStyleOutput()->warning(
                [
                    "!! eXpansion is running in dev mode !!",
                    "In dev mode eXpansion is not stable and will leak memory which will cause crash.",
                    "This is normal behaviour. Please use prod mode. ",
                    "",
                    "If you are currently developing please ignore this message.",
                ]
            );
            $this->chatNotification->sendMessage("{warning} eXpansion is in dev mode. Memory leaks are normal.");
        }
    }
}