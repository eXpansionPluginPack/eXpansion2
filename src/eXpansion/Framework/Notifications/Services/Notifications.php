<?php
/**
 * Created by PhpStorm.
 * User: php_r
 * Date: 25.2.2018
 * Time: 21.50
 */

namespace eXpansion\Framework\Notifications\Services;


use eXpansion\Framework\Core\Model\UserGroups\Group;
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
     * Notifications constructor.
     * @param Group               $group
     * @param NotificationUpdater $notificationUpdater
     * @param LoggerInterface     $logger
     */
    public function __construct(
        $group,
        NotificationUpdater $notificationUpdater,
        LoggerInterface $logger
    ) {
        $this->notificationsUpdater = $notificationUpdater;
        $this->group = $group;
        $this->logger = $logger;
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
    public function info($message, $params = [], $title = "Info", $duration = 5500, $group = null)
    {
        $this->sendNotification('$3af ', $title, $message, $params, $duration, $group);
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
     * @param string     $message Message of notification
     * @param array      $params replacements for translation, note: same params are used for title and message replacement
     * @param string     $title Title of the notification
     * @param int        $duration use 0 for permanent
     * @param Group|null $group
     */

    public function error($message, $params = [], $title = "Error", $duration = 10500, $group = null)
    {
        $this->sendNotification('$f00 ', $title, $message, $params, $duration, $group);
    }


    /**
     * @param      $prefix
     * @param      $title
     * @param      $message
     * @param      $params
     * @param      $duration
     * @param null $group
     */
    protected function sendNotification($prefix, $title, $message, $params, $duration, $group = null)
    {
        if ($group == null) {
            $group = $this->group;
        }

        $this->notificationsUpdater->setNotification($prefix, $title, $message, $params, $duration, $group);
    }


}