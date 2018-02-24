<?php

namespace eXpansion\Bundle\Notifications\Plugins;

use eXpansion\Bundle\Notifications\Plugins\Gui\NotificationUpdater;
use eXpansion\Bundle\Notifications\Plugins\Gui\NotificationWidget;
use eXpansion\Framework\Core\Model\UserGroups\Group;
use eXpansion\Framework\Core\Plugins\StatusAwarePluginInterface;

/**
 * Class CustomUi
 *
 * @package eXpansion\Bundle\CustomUi\Plugins
 */
class Notifications implements StatusAwarePluginInterface
{

    /**@var Group */
    protected $allPlayers;

    /**
     * @var NotificationWidget
     */
    private $notificationWidget;
    /**
     * @var NotificationUpdater
     */
    private $notificationUpdater;

    /**
     * CustomUi constructor.
     *
     * @param NotificationWidget  $notificationWidget
     * @param NotificationUpdater $notificationUpdater
     * @param Group               $allPlayers
     */
    public function __construct(
        NotificationWidget $notificationWidget,
        NotificationUpdater $notificationUpdater,
        Group $allPlayers
    ) {

        $this->notificationWidget = $notificationWidget;
        $this->notificationUpdater = $notificationUpdater;
        $this->allPlayers = $allPlayers;
    }


    /**
     * @inheritdoc
     */
    public function setStatus($status)
    {
        $this->notificationWidget->create($this->allPlayers);

        $this->notificationUpdater->setNotification("info", "Hey hey hey hey, testing", "This is a test", 2500, $this->allPlayers);
        $this->notificationUpdater->create($this->allPlayers);


    }

}
