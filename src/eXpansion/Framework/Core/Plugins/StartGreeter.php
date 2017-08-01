<?php

namespace eXpansion\Framework\Core\Plugins;

use eXpansion\Framework\Core\DataProviders\Listener\ListenerInterfaceExpApplication;
use eXpansion\Framework\Core\Helpers\ChatNotification;
use eXpansion\Framework\Core\Services\Application\AbstractApplication;

class StartGreeter implements ListenerInterfaceExpApplication
{
    /**
     * @var ChatNotification
     */
    private $chatNotification;

    /**
     * StartGreeter constructor.
     * @param ChatNotification $chatNotification
     */
    public function __construct(ChatNotification $chatNotification)
    {
        $this->chatNotification = $chatNotification;
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
        $this->chatNotification->sendMessage("");
        $this->chatNotification->sendMessage("");
        $this->chatNotification->sendMessage('$z$fff$s       -  -   –    –     —     —    –    –   -   -');
        $this->chatNotification->sendMessage('$z$s$w$i$fff       e X p a n s i o n ²', null, []);
        $this->chatNotification->sendMessage(
            '$z$o$000                version$o '.AbstractApplication::EXPANSION_VERSION, null, []);

        $this->chatNotification->sendMessage('$z$s$fff                 —————————— ');
        $this->chatNotification->sendMessage("");
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
