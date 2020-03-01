<?php

namespace eXpansion\Framework\Core\Plugins;

use eXpansion\Framework\Core\DataProviders\Listener\ListenerInterfaceExpApplication;
use eXpansion\Framework\Core\Helpers\ChatNotification;
use eXpansion\Framework\Core\Helpers\Version;
use eXpansion\Framework\Core\Services\Application\AbstractApplication;

class StartGreeter implements ListenerInterfaceExpApplication
{
    /**
     * @var ChatNotification
     */
    private $chatNotification;

    /** @var Version */
    protected $version;

    /**
     * StartGreeter constructor.
     * @param ChatNotification $chatNotification
     */
    public function __construct(ChatNotification $chatNotification, Version $version)
    {
        $this->chatNotification = $chatNotification;
        $this->version = $version;
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
            '$z$o$000                version$o '. $this->version->getExpansionVersion(), null, []);

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
