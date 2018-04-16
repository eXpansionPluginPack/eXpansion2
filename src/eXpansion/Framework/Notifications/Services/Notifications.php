<?php
/**
 * Created by PhpStorm.
 * User: php_r
 * Date: 25.2.2018
 * Time: 21.50
 */

namespace eXpansion\Framework\Notifications\Services;


use eXpansion\Framework\Core\Model\UserGroups\Group;
use eXpansion\Framework\Core\Plugins\UserGroups\Factory as GroupFactory;
use eXpansion\Framework\Notifications\Plugins\Gui\NotificationUpdater;
use Psr\Log\LoggerInterface;

/**
 * Class Notifications
 * @package eXpansion\Framework\Notifications\Services
 */
class Notifications
{
    /**
     * @var NotificationUpdater
     */
    private $notificationsUpdater;

    /** @var Group */
    private $group;
    /**
     * @var LoggerInterface
     */
    private $logger;
    /**
     * @var GroupFactory
     */
    private $groupFactory;

    /**
     * Notifications constructor.
     * @param Group               $group
     * @param NotificationUpdater $notificationUpdater
     * @param GroupFactory        $groupFactory
     * @param LoggerInterface     $logger
     */
    public function __construct(
        $group,
        NotificationUpdater $notificationUpdater,
        GroupFactory $groupFactory,
        LoggerInterface $logger
    ) {
        $this->notificationsUpdater = $notificationUpdater;
        $this->group = $group;
        $this->logger = $logger;
        $this->groupFactory = $groupFactory;
    }

    /**
     * Sends notification as info level
     * Notifications are automatically logged
     *
     * @param string            $message Message of notification
     * @param array             $params replacements for translation, note: same params are used for title and message replacement
     * @param string            $title Title of the notification
     * @param int               $duration use 0 for permanent
     * @param string|Group|null $group login, group instance or null for everyone
     */
    public function info($message, $params = [], $title = "Info", $duration = 5500, $group = null)
    {
        $this->sendNotification('$3af ', $title, $message, $params, $duration, $group);
    }

    /**
     * Sends notification as info level
     * Notifications are automatically logged
     *
     * @param string            $message Message of notification
     * @param array             $params replacements for translation, note: same params are used for title and message replacement
     * @param string            $title Title of the notification
     * @param int               $duration use 0 for permanent
     * @param string|Group|null $group login, group instance or null for everyone
     */

    public function notice($message, $params = [], $title = "Notice", $duration = 5500, $group = null)
    {
        $this->sendNotification("", $title, $message, $params, $duration, $group);
    }

    /**
     * Sends notification as info level
     * Notifications are automatically logged
     *
     * @param string     $message Message of notification
     * @param array      $params replacements for translation, note: same params are used for title and message replacement
     * @param string     $title Title of the notification
     * @param int        $duration use 0 for permanent
     * @param Group|null $group
     */

    public function warning($message, $params = [], $title = "Warning", $duration = 8500, $group = null)
    {

        $this->sendNotification('$ff0 ', $title, $message, $params, $duration, $group);
    }

    /**
     * Sends notification as info level
     * Notifications are automatically logged
     *
     * @param string            $message Message of notification
     * @param array             $params replacements for translation, note: same params are used for title and message replacement
     * @param string            $title Title of the notification
     * @param int               $duration use 0 for permanent
     * @param string|Group|null $group login, group instance or null for everyone
     */

    public function error($message, $params = [], $title = "Error", $duration = 10500, $group = null)
    {
        $this->sendNotification('$f00 ', $title, $message, $params, $duration, $group);
    }


    /**
     * @param                    $prefix
     * @param                    $title
     * @param                    $message
     * @param                    $params
     * @param                    $duration
     * @param  string|Group|null $group
     */
    protected function sendNotification($prefix, $title, $message, $params, $duration, $group = null)
    {
        if ($group == null) {
            $group = $this->group;
        }

        if (is_string($group)) {
            $group = $this->groupFactory->createForPlayer($group);
        }

        if (is_array($group)) {
            $group = $this->groupFactory->createForPlayers($group);
        }

        $this->notificationsUpdater->setNotification($prefix, $title, $message, $params, $duration, $group);
    }


}