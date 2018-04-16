<?php

namespace eXpansion\Framework\Notifications\Plugins;

use eXpansion\Framework\Core\Model\UserGroups\Group;
use eXpansion\Framework\Core\Plugins\StatusAwarePluginInterface;
use eXpansion\Framework\Notifications\Plugins\Gui\NotificationUpdater;
use eXpansion\Framework\Notifications\Plugins\Gui\NotificationWidget;

/**
 * Class CustomUi
 *
 * @package eXpansion\Framework\CustomUi\Plugins
 */
class NotificationsPlugin implements StatusAwarePluginInterface
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
        if ($status) {
            $this->notificationWidget->create($this->allPlayers);
            $this->notificationUpdater->create($this->allPlayers);
        }
    }

}
